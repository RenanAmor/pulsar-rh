<?php

namespace App\AI;

use App\AI\Providers\ClaudeProvider;
use App\AI\Providers\GeminiProvider;
use App\AI\Providers\NullProvider;
use App\AI\Providers\OpenAIProvider;
use App\Indicators\IndicatorEngine;
use App\OIE\OIE;
use App\OIE\OrganizationalHistory;
use Database\Database;
use PDO;

class AIManager
{
    /** @var AIProvider[] */
    private array $providers;

    private OIE $oie;
    private PromptBuilder $promptBuilder;
    private ContextSerializer $contextSerializer;
    private AIResponseParser $responseParser;
    private AIValidator $validator;
    private IndicatorEngine $indicatorEngine;
    private OrganizationalHistory $history;
    private PDO $db;
    private string $logPath;

    public function __construct(?array $providers = null)
    {
        $this->providers = $providers ?? [
            new OpenAIProvider(),
            new ClaudeProvider(),
            new GeminiProvider(),
            new NullProvider(),
        ];

        $this->oie = new OIE();
        $this->promptBuilder = new PromptBuilder();
        $this->contextSerializer = new ContextSerializer();
        $this->responseParser = new AIResponseParser();
        $this->validator = new AIValidator();
        $this->indicatorEngine = new IndicatorEngine();
        $this->history = new OrganizationalHistory($this->indicatorEngine);
        $this->db = Database::connect();
        $this->logPath = __DIR__ . '/../../storage/ai_executions.log';
    }

    /**
     * @return AIProvider[]
     */
    public function providers(): array
    {
        return $this->providers;
    }

    public function selectProvider(): AIProvider
    {
        foreach ($this->providers as $provider) {
            if ($provider->isAvailable()) {
                return $provider;
            }
        }

        // NullProvider está sempre disponível; este ponto nunca deveria
        // ser alcançado, mas garante que o sistema nunca fique sem uma
        // resposta utilizável.
        return new NullProvider();
    }

    private function estimateCost(AIProvider $provider, array $usage): float
    {
        if ($provider->name() === 'NullProvider') {
            return 0.0;
        }

        $tokens = ($usage['prompt_tokens'] ?? 0) + ($usage['completion_tokens'] ?? 0)
            + ($usage['input_tokens'] ?? 0) + ($usage['output_tokens'] ?? 0);

        // Estimativa simples e conservadora (não substitui o faturamento real do provedor).
        return round($tokens * 0.00001, 6);
    }

    private function logExecution(array $entry): void
    {
        $directory = dirname($this->logPath);

        if (!is_dir($directory)) {
            @mkdir($directory, 0777, true);
        }

        @file_put_contents(
            $this->logPath,
            json_encode($entry, JSON_UNESCAPED_UNICODE) . PHP_EOL,
            FILE_APPEND
        );
    }

    private function ensureSnapshot(int $companyId, int $surveyId): ?int
    {
        $latest = $this->history->latest($companyId, $surveyId);

        if ($latest === null) {
            $this->indicatorEngine->run($surveyId, [], true);
            $latest = $this->history->latest($companyId, $surveyId);
        }

        return $latest['id'] ?? null;
    }

    private function persistReport(int $companyId, int $surveyId, ?int $ohiId, array $parsed, string $generatedBy): ?int
    {
        if ($ohiId === null) {
            return null;
        }

        $stmt = $this->db->prepare("
            INSERT INTO ai_reports
            (
                company_id,
                survey_id,
                organizational_health_index_id,
                executive_summary,
                strengths,
                weaknesses,
                opportunities,
                threats,
                priorities,
                recommendations,
                generated_by
            )
            VALUES
            (
                :company_id,
                :survey_id,
                :ohi_id,
                :executive_summary,
                :strengths,
                :weaknesses,
                :opportunities,
                :threats,
                :priorities,
                :recommendations,
                :generated_by
            )
        ");

        $stmt->execute([
            'company_id'         => $companyId,
            'survey_id'          => $surveyId,
            'ohi_id'             => $ohiId,
            'executive_summary'  => $parsed['executive_summary'],
            'strengths'          => $parsed['detailed_analysis'],
            'weaknesses'         => implode("\n", $parsed['risks']),
            'opportunities'      => implode("\n", $parsed['opportunities']),
            'threats'            => implode("\n", $parsed['risks']),
            'priorities'         => implode("\n", $parsed['recommendations']),
            'recommendations'    => implode("\n", $parsed['recommendations']),
            'generated_by'       => $generatedBy,
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Gera uma análise para a pesquisa informada: constrói o contexto via
     * OIE, monta o prompt, tenta o primeiro provedor disponível (com uma
     * nova tentativa em caso de resposta inválida) e cai para o
     * NullProvider (sem IA) caso nenhum provedor funcione — o sistema
     * nunca fica sem uma resposta utilizável.
     */
    public function generateReport(int $surveyId): array
    {
        $context = $this->oie->analyze($surveyId);

        if (isset($context['error'])) {
            return ['error' => $context['error']];
        }

        $companyId = (int) $context['organization']['company_id'];
        $prompt = $this->promptBuilder->build($context);

        $provider = $this->selectProvider();
        $attempt = $this->executeAttempt($provider, $prompt, $context);

        if (!$attempt['validation']['valid'] && $provider->name() !== 'NullProvider') {
            $attempt = $this->executeAttempt($provider, $prompt, $context);
        }

        if (!$attempt['validation']['valid'] && $provider->name() !== 'NullProvider') {
            $fallbackProvider = new NullProvider();
            $attempt = $this->executeAttempt($fallbackProvider, $prompt, $context);
            $provider = $fallbackProvider;
        }

        $ohiId = $this->ensureSnapshot($companyId, $surveyId);
        $reportId = $this->persistReport($companyId, $surveyId, $ohiId, $attempt['parsed'], $provider->name());

        $this->logExecution([
            'timestamp'         => (new \DateTimeImmutable())->format(DATE_ATOM),
            'company_id'        => $companyId,
            'survey_id'         => $surveyId,
            'provider'          => $provider->name(),
            'model'             => $attempt['model'],
            'prompt_length'     => strlen($prompt),
            'duration_ms'       => $attempt['duration_ms'],
            'estimated_cost'    => $attempt['estimated_cost'],
            'status'            => $attempt['validation']['valid'] ? 'ok' : 'invalid_response',
            'validation_errors' => $attempt['validation']['errors'],
            'ai_report_id'      => $reportId,
        ]);

        return [
            'provider'       => $provider->name(),
            'model'          => $attempt['model'],
            'duration_ms'    => $attempt['duration_ms'],
            'estimated_cost' => $attempt['estimated_cost'],
            'parsed'         => $attempt['parsed'],
            'validation'     => $attempt['validation'],
            'ai_report_id'   => $reportId,
            'context'        => $this->contextSerializer->toArray($context),
        ];
    }

    private function executeAttempt(AIProvider $provider, string $prompt, array $context): array
    {
        $start = microtime(true);
        $response = $provider->send($prompt, $context);
        $durationMs = (int) round((microtime(true) - $start) * 1000);

        $parsed = $this->responseParser->parse($response['content'] ?? null);
        $validation = $this->validator->validate($parsed);

        return [
            'parsed'         => $parsed,
            'validation'     => $validation,
            'model'          => $response['model'] ?? 'desconhecido',
            'duration_ms'    => $durationMs,
            'estimated_cost' => $this->estimateCost($provider, $response['usage'] ?? []),
        ];
    }
}
