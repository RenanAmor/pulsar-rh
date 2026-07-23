<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/AI/AIProvider.php';
require_once __DIR__ . '/../app/AI/AIResponseParser.php';
require_once __DIR__ . '/../app/AI/AIValidator.php';
require_once __DIR__ . '/../app/AI/PromptBuilder.php';
require_once __DIR__ . '/../app/AI/ContextSerializer.php';
require_once __DIR__ . '/../app/AI/Providers/AbstractApiProvider.php';
require_once __DIR__ . '/../app/AI/Providers/NullProvider.php';
require_once __DIR__ . '/../app/AI/Providers/OpenAIProvider.php';
require_once __DIR__ . '/../app/AI/Providers/ClaudeProvider.php';
require_once __DIR__ . '/../app/AI/Providers/GeminiProvider.php';

use App\AI\AIResponseParser;
use App\AI\AIValidator;
use App\AI\PromptBuilder;
use App\AI\ContextSerializer;
use App\AI\Providers\NullProvider;
use App\AI\Providers\OpenAIProvider;
use App\AI\Providers\ClaudeProvider;
use App\AI\Providers\GeminiProvider;

$failures = 0;

function check(string $label, bool $condition, int &$failures): void
{
    if ($condition) {
        echo "PASS: {$label}\n";
        return;
    }

    $failures++;
    echo "FAIL: {$label}\n";
}

function sampleContext(): array
{
    return [
        'generated_at' => '2026-07-22T00:00:00+00:00',
        'organization' => ['company_id' => 1, 'company_name' => 'Empresa Teste', 'survey_title' => 'Pesquisa Teste'],
        'indicators'   => [
            'final_score'    => 45.0,
            'classification' => 'Atenção',
            'participation'  => ['percentage' => 80.0],
            'burnout_risk'   => 60.0,
            'categories'     => [['category' => 'Liderança', 'average' => 40.0]],
        ],
        'history'         => [],
        'patterns'        => ['has_previous' => false, 'overall' => 'Sem histórico anterior para comparação'],
        'risks'           => [['key' => 'baixa_lideranca', 'label' => 'Baixa Liderança', 'severity' => 'Média', 'value' => 40.0]],
        'recommendations' => [['title' => 'Treinamento de Liderança', 'reason' => 'Liderança abaixo do esperado', 'priority' => 'Média']],
    ];
}

// ---------- AIResponseParser ----------

$parser = new AIResponseParser();

$validJson = json_encode([
    'executive_summary' => 'Resumo',
    'detailed_analysis' => 'Análise',
    'opportunities'      => ['Oportunidade 1'],
    'risks'               => ['Risco 1'],
    'recommendations'    => ['Recomendação 1'],
]);

$parsed = $parser->parse($validJson);
check('Parser interpreta JSON válido corretamente', $parsed['executive_summary'] === 'Resumo' && !$parsed['parse_fallback'], $failures);

$fencedJson = "```json\n{$validJson}\n```";
$parsedFenced = $parser->parse($fencedJson);
check('Parser remove cercas de código (```json ... ```) antes de decodificar', $parsedFenced['executive_summary'] === 'Resumo', $failures);

$malformed = $parser->parse('isto não é json');
check('Parser trata texto não-JSON como fallback (sem lançar exceção)', $malformed['parse_fallback'] === true, $failures);
check('Parser preserva o texto bruto como resumo no fallback', $malformed['executive_summary'] === 'isto não é json', $failures);

$nullInput = $parser->parse(null);
check('Parser trata entrada nula como fallback vazio', $nullInput['parse_fallback'] === true && $nullInput['executive_summary'] === '', $failures);

// ---------- AIValidator ----------

$validator = new AIValidator();

$validResult = $validator->validate($parsed);
check('Validator aceita resposta bem formada', $validResult['valid'] === true, $failures);

$emptySummary = $validator->validate(['executive_summary' => '', 'detailed_analysis' => '', 'opportunities' => [], 'risks' => [], 'recommendations' => []]);
check('Validator rejeita resumo executivo vazio', $emptySummary['valid'] === false, $failures);

$missingFields = $validator->validate(['executive_summary' => 'ok']);
check('Validator rejeita campos obrigatórios ausentes', $missingFields['valid'] === false, $failures);

$fallbackResult = $validator->validate(['executive_summary' => 'texto', 'detailed_analysis' => '', 'opportunities' => [], 'risks' => [], 'recommendations' => [], 'parse_fallback' => true]);
check('Validator rejeita respostas marcadas como parse_fallback', $fallbackResult['valid'] === false, $failures);

// ---------- PromptBuilder ----------

$promptBuilder = new PromptBuilder();
$prompt = $promptBuilder->build(sampleContext());

check('Prompt contém o nome da empresa', str_contains($prompt, 'Empresa Teste'), $failures);
check('Prompt contém a seção de indicadores', str_contains($prompt, '## INDICADORES'), $failures);
check('Prompt contém os riscos detectados pelo OIE', str_contains($prompt, 'Baixa Liderança'), $failures);
check('Prompt contém as recomendações do OIE', str_contains($prompt, 'Treinamento de Liderança'), $failures);
check('Prompt instrui resposta em JSON estruturado', str_contains($prompt, 'JSON'), $failures);

// ---------- ContextSerializer ----------

$serializer = new ContextSerializer();
$serialized = $serializer->toArray(sampleContext());

check('ContextSerializer inclui schema_version', $serialized['schema_version'] === '1.0', $failures);

$json = $serializer->serialize(sampleContext());
$roundTrip = json_decode($json, true);
check('ContextSerializer produz JSON válido (round-trip)', $roundTrip['organization']['company_name'] === 'Empresa Teste', $failures);

// ---------- NullProvider ----------

$nullProvider = new NullProvider();
check('NullProvider está sempre disponível', $nullProvider->isAvailable() === true, $failures);

$context = sampleContext();
$response = $nullProvider->send($promptBuilder->build($context), $context);
$parsedNullResponse = $parser->parse($response['content']);
$validatedNullResponse = $validator->validate($parsedNullResponse);

check('Resposta do NullProvider é JSON válido e estruturado', $validatedNullResponse['valid'] === true, $failures);
check('NullProvider não tem custo estimado', $response['usage']['estimated_cost'] === 0.0, $failures);

// ---------- Disponibilidade dos provedores reais (sem chaves configuradas neste ambiente) ----------

check('OpenAIProvider fica indisponível sem OPENAI_API_KEY configurada', (new OpenAIProvider())->isAvailable() === false, $failures);
check('ClaudeProvider fica indisponível sem ANTHROPIC_API_KEY configurada', (new ClaudeProvider())->isAvailable() === false, $failures);
check('GeminiProvider fica indisponível sem GEMINI_API_KEY configurada', (new GeminiProvider())->isAvailable() === false, $failures);

echo "\n";

if ($failures > 0) {
    echo "{$failures} teste(s) falharam.\n";
    exit(1);
}

echo "Todos os testes da camada de IA passaram.\n";
exit(0);
