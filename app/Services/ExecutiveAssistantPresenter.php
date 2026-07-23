<?php

namespace App\Services;

/**
 * Traduz o contexto já produzido pelo OIE (App\OIE\OIE::analyze()) na
 * experiência do Assistente Executivo: uma leitura em linguagem natural,
 * como a de um consultor organizacional, sobre a situação da empresa. Não
 * recalcula nada, não acessa o banco, não chama IA e não conhece
 * OIE/Indicators internamente — apenas seleciona, ordena e traduz o que já
 * foi produzido para linguagem executiva.
 */
class ExecutiveAssistantPresenter
{
    private const SEVERITY_WEIGHT = [
        'Crítica' => 3,
        'Alta'    => 2,
        'Média'   => 1,
    ];

    private const HEALTH_PHRASES = [
        'Excelente' => 'em excelente momento',
        'Saudável'  => 'saudável',
        'Atenção'   => 'em ponto de atenção',
        'Crítico'   => 'em situação crítica',
    ];

    private const DIMENSION_LABELS = [
        'leadership'    => 'Liderança',
        'communication' => 'Comunicação',
        'engagement'    => 'Engajamento',
        'wellbeing'     => 'Bem-estar',
        'development'   => 'Desenvolvimento',
        'culture'       => 'Clima Organizacional',
        'collaboration' => 'Colaboração',
        'recognition'   => 'Reconhecimento',
        'burnout_risk'  => 'Risco de Burnout',
        'turnover_risk' => 'Risco de Turnover',
    ];

    private const RISK_HEADLINES = [
        'baixa_lideranca'         => 'Liderança crítica.',
        'baixa_comunicacao'       => 'Comunicação em queda.',
        'baixo_engajamento'       => 'Engajamento em queda.',
        'risco_burnout'           => 'Burnout elevado.',
        'risco_turnover'          => 'Alto risco de turnover.',
        'conflitos_entre_equipes' => 'Conflitos entre equipes identificados.',
        'queda_de_clima'          => 'Clima organizacional em queda.',
        'perda_de_satisfacao'     => 'Queda de satisfação identificada.',
    ];

    private const RISK_THEME = [
        'baixa_lideranca'         => 'Liderança',
        'baixa_comunicacao'       => 'Comunicação',
        'baixo_engajamento'       => 'Engajamento',
        'risco_burnout'           => 'Burnout',
        'risco_turnover'          => 'Turnover',
        'conflitos_entre_equipes' => 'Colaboração entre Equipes',
        'queda_de_clima'          => 'Clima Organizacional',
        'perda_de_satisfacao'     => 'Satisfação',
    ];

    private const RISK_QUESTIONS = [
        'baixa_lideranca'         => 'Ocorreram mudanças recentes na liderança?',
        'baixa_comunicacao'       => 'Você percebe esta situação também na operação?',
        'baixo_engajamento'       => 'Os colaboradores têm participado ativamente das iniciativas da empresa?',
        'risco_burnout'           => 'As equipes têm relatado sobrecarga de trabalho?',
        'risco_turnover'          => 'Houve aumento de pedidos de desligamento recentemente?',
        'conflitos_entre_equipes' => 'As equipes passaram por alguma reorganização recente?',
        'queda_de_clima'          => 'Algo mudou no ambiente de trabalho nas últimas semanas?',
        'perda_de_satisfacao'     => 'Existem sinais de insatisfação sendo relatados pelas equipes?',
    ];

    private const GENERIC_QUESTIONS = [
        'Quais conquistas recentes merecem ser reforçadas junto às equipes?',
        'O que a liderança pode fazer para sustentar esse resultado nos próximos meses?',
    ];

    public function present(array $context): array
    {
        if (isset($context['error'])) {
            return ['error' => $context['error']];
        }

        $risks = $context['risks'] ?? [];
        $recommendations = $context['recommendations'] ?? [];
        $patterns = $context['patterns'] ?? ['has_previous' => false, 'dimensions' => [], 'overall' => 'Sem histórico anterior para comparação'];
        $history = $context['history'] ?? [];

        return [
            'executiveMessage'   => $this->buildExecutiveMessage($context, $patterns),
            'summary'            => [
                'currentSituation' => $this->buildCurrentSituation($context),
                'evolution'        => $this->buildEvolution($patterns),
                'trend'            => $this->buildTrend($history),
            ],
            'priorityTopics'     => $this->buildPriorityTopics($risks),
            'recommendedActions' => $this->buildRecommendedActions($recommendations),
            'strategicQuestions' => $this->buildStrategicQuestions($risks),
        ];
    }

    private function severityWeight(?string $severity): int
    {
        return self::SEVERITY_WEIGHT[$severity] ?? 0;
    }

    private function salutation(): string
    {
        $hour = (int) (new \DateTimeImmutable())->format('G');

        if ($hour >= 5 && $hour < 12) {
            return 'Bom dia';
        }

        if ($hour >= 12 && $hour < 18) {
            return 'Boa tarde';
        }

        return 'Boa noite';
    }

    /**
     * Conta quantas dimensões relevantes deixaram de estar Estáveis desde a
     * última análise. Retorna null quando não há pesquisa anterior.
     */
    private function countRelevantChanges(array $patterns): ?int
    {
        if (!($patterns['has_previous'] ?? false)) {
            return null;
        }

        $count = 0;

        foreach ($patterns['dimensions'] ?? [] as $column => $dimension) {
            if ($column === 'final_score' || !isset(self::DIMENSION_LABELS[$column])) {
                continue;
            }

            if (($dimension['classification'] ?? 'Estável') !== 'Estável') {
                $count++;
            }
        }

        return $count;
    }

    private function buildExecutiveMessage(array $context, array $patterns): string
    {
        $classification = $context['indicators']['classification'] ?? null;
        $healthPhrase = self::HEALTH_PHRASES[$classification] ?? 'em avaliação';
        $changeCount = $this->countRelevantChanges($patterns);

        $sentences = [
            $this->salutation() . '.',
            "Sua organização encontra-se {$healthPhrase}.",
        ];

        if ($changeCount === null) {
            $sentences[] = 'Esta é a primeira análise disponível — ainda não há um histórico anterior para comparação.';
        } elseif ($changeCount === 0) {
            $sentences[] = 'Nenhuma mudança relevante foi identificada desde a última análise.';
        } else {
            $word = $changeCount === 1 ? 'mudança importante' : 'mudanças importantes';
            $sentences[] = "Nas últimas análises foram identificadas {$changeCount} {$word}.";
        }

        return implode(' ', $sentences);
    }

    private function buildCurrentSituation(array $context): string
    {
        $classification = $context['indicators']['classification'] ?? null;
        $score = $context['indicators']['final_score'] ?? null;
        $participation = $context['indicators']['participation']['percentage'] ?? null;

        if ($classification === null || $score === null) {
            return 'Ainda não há dados suficientes para avaliar a organização.';
        }

        $sentence = sprintf(
            'A organização está avaliada como %s, com Índice Geral de %s pontos.',
            $classification,
            number_format($score, 0)
        );

        if ($participation !== null) {
            $sentence .= sprintf(' A participação nesta análise foi de %s%% dos colaboradores.', number_format($participation, 0));
        }

        return $sentence;
    }

    private function buildEvolution(array $patterns): string
    {
        if (!($patterns['has_previous'] ?? false)) {
            return 'Esta é a primeira análise disponível para esta organização.';
        }

        $improved = 0;
        $worsened = 0;

        foreach ($patterns['dimensions'] ?? [] as $column => $dimension) {
            if ($column === 'final_score' || !isset(self::DIMENSION_LABELS[$column])) {
                continue;
            }

            $classification = $dimension['classification'] ?? 'Estável';

            if (str_contains($classification, 'Melhora')) {
                $improved++;
            } elseif (str_contains($classification, 'Piora')) {
                $worsened++;
            }
        }

        if ($improved === 0 && $worsened === 0) {
            return 'Os indicadores permanecem estáveis desde a última análise.';
        }

        $parts = [];

        if ($improved > 0) {
            $parts[] = sprintf('%d indicador(es) melhoraram', $improved);
        }

        if ($worsened > 0) {
            $parts[] = sprintf('%d indicador(es) pioraram', $worsened);
        }

        return 'Desde a última análise, ' . implode(' e ', $parts) . '.';
    }

    private function buildTrend(array $history): string
    {
        $history = array_values($history);
        $count = count($history);

        if ($count < 2) {
            return 'Ainda não há histórico suficiente para identificar uma tendência.';
        }

        $first = (float) ($history[0]['final_score'] ?? 0);
        $last = (float) ($history[$count - 1]['final_score'] ?? 0);
        $delta = $last - $first;

        if (abs($delta) < 3.0) {
            return 'A tendência é de estabilidade nos últimos períodos.';
        }

        return $delta > 0
            ? 'A tendência é de crescimento nos últimos períodos.'
            : 'A tendência é de queda nos últimos períodos — recomenda-se atenção.';
    }

    /**
     * @param array<int, array{key:string,label:string,severity:string,value:float}> $risks
     */
    private function buildPriorityTopics(array $risks): array
    {
        usort($risks, fn (array $a, array $b): int => $this->severityWeight($b['severity']) <=> $this->severityWeight($a['severity']));

        return array_map(
            fn (array $risk): array => [
                'theme'    => self::RISK_THEME[$risk['key']] ?? $risk['label'],
                'headline' => self::RISK_HEADLINES[$risk['key']] ?? $risk['label'] . '.',
                'severity' => $risk['severity'],
            ],
            array_slice($risks, 0, 5)
        );
    }

    /**
     * @param array<int, array{title:string,reason:string,priority:string}> $recommendations
     */
    private function buildRecommendedActions(array $recommendations): array
    {
        usort($recommendations, fn (array $a, array $b): int => $this->severityWeight($b['priority']) <=> $this->severityWeight($a['priority']));

        return array_map(
            fn (array $recommendation): array => [
                'title'    => $recommendation['title'],
                'reason'   => $recommendation['reason'],
                'priority' => $recommendation['priority'],
            ],
            $recommendations
        );
    }

    /**
     * @param array<int, array{key:string,label:string,severity:string,value:float}> $risks
     */
    private function buildStrategicQuestions(array $risks): array
    {
        if (empty($risks)) {
            return self::GENERIC_QUESTIONS;
        }

        usort($risks, fn (array $a, array $b): int => $this->severityWeight($b['severity']) <=> $this->severityWeight($a['severity']));

        $questions = [];

        foreach ($risks as $risk) {
            $question = self::RISK_QUESTIONS[$risk['key']] ?? null;

            if ($question !== null && !in_array($question, $questions, true)) {
                $questions[] = $question;
            }

            if (count($questions) >= 3) {
                break;
            }
        }

        return empty($questions) ? self::GENERIC_QUESTIONS : $questions;
    }
}
