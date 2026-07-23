<?php

namespace App\Services;

/**
 * Traduz o contexto já produzido pelo OIE (App\OIE\OIE::analyze()) na
 * experiência do Centro de Inteligência Organizacional: situação atual,
 * mudanças recentes, alertas, recomendações e evolução. Não recalcula
 * nada, não acessa o banco e não conhece OIE/Indicators internamente —
 * apenas seleciona, ordena e traduz o que já foi produzido para
 * linguagem executiva.
 */
class IntelligenceCenterPresenter
{
    private const SEVERITY_WEIGHT = [
        'Crítica' => 3,
        'Alta'    => 2,
        'Média'   => 1,
    ];

    private const TIER_CLASSES = [
        'Excelente' => 'tier-excelente',
        'Saudável'  => 'tier-saudavel',
        'Atenção'   => 'tier-atencao',
        'Crítico'   => 'tier-critico',
    ];

    private const MONTHS_PT = [
        1 => 'janeiro', 2 => 'fevereiro', 3 => 'março', 4 => 'abril',
        5 => 'maio', 6 => 'junho', 7 => 'julho', 8 => 'agosto',
        9 => 'setembro', 10 => 'outubro', 11 => 'novembro', 12 => 'dezembro',
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
        'burnout_risk'  => 'Burnout',
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

    private const RISK_SECTOR = [
        'baixa_lideranca'         => 'Liderança',
        'baixa_comunicacao'       => 'Comunicação',
        'baixo_engajamento'       => 'Engajamento',
        'risco_burnout'           => 'Bem-estar',
        'risco_turnover'          => 'Retenção de Talentos',
        'conflitos_entre_equipes' => 'Colaboração',
        'queda_de_clima'          => 'Clima Organizacional',
        'perda_de_satisfacao'     => 'Satisfação',
    ];

    private const RISK_IMPACT = [
        'baixa_lideranca'         => 'Pode comprometer a confiança das equipes em seus líderes e a qualidade das decisões do dia a dia.',
        'baixa_comunicacao'       => 'Aumenta o risco de ruídos, retrabalho e desalinhamento entre áreas.',
        'baixo_engajamento'       => 'Tende a reduzir a produtividade e o senso de pertencimento das equipes.',
        'risco_burnout'           => 'Eleva o risco de afastamentos, queda de desempenho e adoecimento das equipes.',
        'risco_turnover'          => 'Aumenta a probabilidade de pedidos de desligamento nos próximos meses.',
        'conflitos_entre_equipes' => 'Pode gerar atritos, perda de confiança e queda na qualidade da entrega em equipe.',
        'queda_de_clima'          => 'Sinaliza deterioração do ambiente de trabalho e da percepção geral da empresa.',
        'perda_de_satisfacao'     => 'Pode antecipar pedidos de desligamento e queda de engajamento.',
    ];

    private const RISK_TREND_COLUMN = [
        'baixa_lideranca'         => 'leadership',
        'baixa_comunicacao'       => 'communication',
        'baixo_engajamento'       => 'engagement',
        'risco_burnout'           => 'burnout_risk',
        'risco_turnover'          => 'turnover_risk',
        'conflitos_entre_equipes' => null,
        'queda_de_clima'          => 'culture',
        'perda_de_satisfacao'     => null,
    ];

    private const RECOMMENDATION_IMPACT = [
        'Fortalecer Comunicação'      => 'Reduz ruídos internos e melhora o alinhamento entre as equipes.',
        'Treinamento de Liderança'    => 'Fortalece a confiança das equipes em seus líderes e melhora a tomada de decisão.',
        'Feedback Contínuo'           => 'Aumenta o engajamento e o senso de pertencimento dos colaboradores.',
        'Acompanhamento Psicológico'  => 'Reduz o risco de afastamentos e protege a saúde mental das equipes.',
        'Plano de Desenvolvimento'    => 'Amplia a retenção de talentos e acelera o crescimento interno.',
        'Revisão Organizacional'      => 'Previne uma deterioração mais ampla dos indicadores organizacionais.',
    ];

    public function present(array $context): array
    {
        if (isset($context['error'])) {
            return ['error' => $context['error']];
        }

        $risks = $context['risks'] ?? [];
        $recommendations = $context['recommendations'] ?? [];
        $patterns = $context['patterns'] ?? ['has_previous' => false, 'dimensions' => [], 'overall' => 'Sem histórico anterior para comparação'];

        return [
            'meta'            => $this->buildMeta($context),
            'situation'       => $this->buildSituation($context, $risks),
            'changes'         => $this->buildChanges($patterns),
            'alerts'          => $this->buildAlerts($risks, $patterns),
            'recommendations' => $this->buildRecommendations($recommendations),
            'evolution'       => $this->buildEvolution($context['history'] ?? []),
        ];
    }

    private function buildMeta(array $context): array
    {
        return [
            'companyName' => $context['organization']['company_name'] ?? null,
            'surveyTitle' => $context['organization']['survey_title'] ?? null,
            'analyzedAt'  => $this->formatDateTime($context['generated_at'] ?? null),
        ];
    }

    private function formatDateTime(?string $isoDate): ?string
    {
        if ($isoDate === null) {
            return null;
        }

        $date = \DateTimeImmutable::createFromFormat(DATE_ATOM, $isoDate) ?: null;

        if ($date === null) {
            return null;
        }

        return sprintf(
            '%d de %s de %s às %s',
            (int) $date->format('j'),
            self::MONTHS_PT[(int) $date->format('n')],
            $date->format('Y'),
            $date->format('H:i')
        );
    }

    private function formatDate(?string $raw): string
    {
        if ($raw === null) {
            return '—';
        }

        $timestamp = strtotime($raw);

        if ($timestamp === false) {
            return $raw;
        }

        $date = (new \DateTimeImmutable())->setTimestamp($timestamp);

        return sprintf('%d de %s de %s', (int) $date->format('j'), self::MONTHS_PT[(int) $date->format('n')], $date->format('Y'));
    }

    private function severityWeight(?string $severity): int
    {
        return self::SEVERITY_WEIGHT[$severity] ?? 0;
    }

    /**
     * @param array<int, array{key:string,label:string,severity:string,value:float}> $risks
     */
    private function buildSituation(array $context, array $risks): array
    {
        $classification = $context['indicators']['classification'] ?? null;
        $score = $context['indicators']['final_score'] ?? null;

        return [
            'score'          => $score,
            'classification' => $classification,
            'tierClass'      => self::TIER_CLASSES[$classification] ?? 'tier-desconhecido',
            'participation'  => $context['indicators']['participation']['percentage'] ?? null,
            'summary'        => $this->buildSummary($classification, $score, $risks),
        ];
    }

    /**
     * @param array<int, array{key:string,label:string,severity:string,value:float}> $risks
     */
    private function buildSummary(?string $classification, ?float $score, array $risks): string
    {
        if ($classification === null || $score === null) {
            return 'Ainda não há dados suficientes para avaliar a organização.';
        }

        $base = sprintf('A organização está classificada como %s, com Índice Geral de %s pontos.', $classification, number_format($score, 0));

        if (empty($risks)) {
            return $base . ' Nenhum alerta crítico foi identificado no momento.';
        }

        $sorted = $risks;
        usort($sorted, fn (array $a, array $b): int => $this->severityWeight($b['severity']) <=> $this->severityWeight($a['severity']));
        $topSeverity = $sorted[0]['severity'];

        return $base . sprintf(' %d ponto(s) de atenção identificado(s), o mais urgente em nível %s.', count($risks), $topSeverity);
    }

    private function buildChanges(array $patterns): array
    {
        if (!($patterns['has_previous'] ?? false)) {
            return [
                'hasPrevious' => false,
                'items'       => [],
                'message'     => 'Ainda não há pesquisa anterior para comparação.',
            ];
        }

        $items = [];

        foreach ($patterns['dimensions'] ?? [] as $column => $dimension) {
            if ($column === 'final_score' || !isset(self::DIMENSION_LABELS[$column])) {
                continue;
            }

            if (($dimension['classification'] ?? 'Estável') === 'Estável') {
                continue;
            }

            $items[] = [
                'label'     => self::DIMENSION_LABELS[$column],
                'arrow'     => $dimension['delta'] >= 0 ? '↑' : '↓',
                'delta'     => $dimension['delta'],
                'intensity' => $dimension['classification'],
            ];
        }

        usort($items, fn (array $a, array $b): int => abs($b['delta']) <=> abs($a['delta']));

        return [
            'hasPrevious' => true,
            'items'       => $items,
            'message'     => empty($items) ? 'Nenhuma mudança relevante desde a última pesquisa.' : null,
        ];
    }

    /**
     * @param array<int, array{key:string,label:string,severity:string,value:float}> $risks
     */
    private function buildAlerts(array $risks, array $patterns): array
    {
        usort($risks, fn (array $a, array $b): int => $this->severityWeight($b['severity']) <=> $this->severityWeight($a['severity']));

        $items = array_map(function (array $risk) use ($patterns): array {
            $trendColumn = self::RISK_TREND_COLUMN[$risk['key']] ?? null;
            $trend = 'Sem dado comparativo';

            if ($trendColumn !== null && ($patterns['has_previous'] ?? false) && isset($patterns['dimensions'][$trendColumn])) {
                $trend = $patterns['dimensions'][$trendColumn]['classification'];
            }

            return [
                'headline' => self::RISK_HEADLINES[$risk['key']] ?? $risk['label'] . '.',
                'severity' => $risk['severity'],
                'sector'   => self::RISK_SECTOR[$risk['key']] ?? $risk['label'],
                'trend'    => $trend,
                'impact'   => self::RISK_IMPACT[$risk['key']] ?? 'Requer atenção da liderança para evitar agravamento.',
            ];
        }, $risks);

        return [
            'items' => $items,
            'empty' => empty($items),
        ];
    }

    /**
     * @param array<int, array{title:string,reason:string,priority:string}> $recommendations
     */
    private function buildRecommendations(array $recommendations): array
    {
        usort($recommendations, fn (array $a, array $b): int => $this->severityWeight($b['priority']) <=> $this->severityWeight($a['priority']));

        $items = array_map(fn (array $recommendation): array => [
            'objective' => $recommendation['title'],
            'reason'    => $recommendation['reason'],
            'priority'  => $recommendation['priority'],
            'impact'    => self::RECOMMENDATION_IMPACT[$recommendation['title']] ?? 'Contribui para a melhoria contínua do ambiente organizacional.',
        ], $recommendations);

        return [
            'items' => $items,
            'empty' => empty($items),
        ];
    }

    private function buildEvolution(array $history): array
    {
        if (empty($history)) {
            return [
                'hasHistory' => false,
                'series'     => [],
                'trend'      => null,
            ];
        }

        $history = array_values($history);
        $series = [];

        foreach ($history as $i => $snapshot) {
            $previous = $history[$i - 1] ?? null;

            $series[] = [
                'date'           => $this->formatDate($snapshot['created_at'] ?? null),
                'score'          => (float) ($snapshot['final_score'] ?? 0),
                'classification' => $snapshot['classification'] ?? null,
                'arrow'          => $previous === null ? null : (((float) $snapshot['final_score']) >= ((float) $previous['final_score']) ? '↑' : '↓'),
            ];
        }

        $count = count($series);
        $trend = $count < 2 ? 'Histórico Insuficiente' : ($series[$count - 1]['score'] >= $series[0]['score'] ? 'Crescente' : 'Decrescente');

        return [
            'hasHistory' => true,
            'series'     => $series,
            'trend'      => $trend,
        ];
    }
}
