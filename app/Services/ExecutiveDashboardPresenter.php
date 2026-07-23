<?php

namespace App\Services;

/**
 * Traduz o contexto já produzido pelo OIE (App\OIE\OIE::analyze()) em um
 * modelo de apresentação para o Dashboard Executivo. Não recalcula nada,
 * não acessa o banco e não conhece OIE/Indicators internamente — apenas
 * seleciona, ordena e formata o que já foi produzido.
 */
class ExecutiveDashboardPresenter
{
    private const SEVERITY_WEIGHT = [
        'Crítica' => 3,
        'Alta'    => 2,
        'Média'   => 1,
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

    public function present(array $context): array
    {
        if (isset($context['error'])) {
            return ['error' => $context['error']];
        }

        return [
            'greeting'           => $this->buildGreeting($context),
            'health'             => $this->buildHealth($context),
            'topAlerts'          => $this->buildAlerts($context['risks'] ?? []),
            'changes'            => $this->buildChanges($context['patterns'] ?? ['has_previous' => false]),
            'priorityOfDay'      => $this->buildPriorityOfDay($context['risks'] ?? [], $context['recommendations'] ?? []),
            'mainRecommendation' => $this->buildMainRecommendation($context['recommendations'] ?? []),
        ];
    }

    private function buildGreeting(array $context): array
    {
        $hour = (int) (new \DateTimeImmutable())->format('G');

        $salutation = 'Boa noite';
        if ($hour >= 5 && $hour < 12) {
            $salutation = 'Bom dia';
        } elseif ($hour >= 12 && $hour < 18) {
            $salutation = 'Boa tarde';
        }

        return [
            'salutation'  => $salutation,
            'companyName' => $context['organization']['company_name'] ?? null,
            'analyzedAt'  => $this->formatDate($context['generated_at'] ?? null),
        ];
    }

    private function formatDate(?string $isoDate): ?string
    {
        if ($isoDate === null) {
            return null;
        }

        $date = \DateTimeImmutable::createFromFormat(DATE_ATOM, $isoDate) ?: null;

        if ($date === null) {
            return null;
        }

        $month = self::MONTHS_PT[(int) $date->format('n')];

        return sprintf(
            '%d de %s de %s às %s',
            (int) $date->format('j'),
            $month,
            $date->format('Y'),
            $date->format('H:i')
        );
    }

    private function buildHealth(array $context): array
    {
        $classification = $context['indicators']['classification'] ?? null;

        return [
            'score'          => $context['indicators']['final_score'] ?? null,
            'classification' => $classification,
            'tierClass'      => self::TIER_CLASSES[$classification] ?? 'tier-desconhecido',
            'participation'  => $context['indicators']['participation']['percentage'] ?? null,
        ];
    }

    private function severityWeight(?string $severity): int
    {
        return self::SEVERITY_WEIGHT[$severity] ?? 0;
    }

    /**
     * @param array<int, array{key:string,label:string,severity:string,value:float}> $risks
     */
    private function buildAlerts(array $risks): array
    {
        usort($risks, fn (array $a, array $b): int => $this->severityWeight($b['severity']) <=> $this->severityWeight($a['severity']));

        return array_map(
            fn (array $risk): array => [
                'headline' => self::RISK_HEADLINES[$risk['key']] ?? $risk['label'] . '.',
                'severity' => $risk['severity'],
            ],
            array_slice($risks, 0, 4)
        );
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
                'label' => self::DIMENSION_LABELS[$column],
                'arrow' => $dimension['delta'] >= 0 ? '↑' : '↓',
                'delta' => $dimension['delta'],
            ];
        }

        usort($items, fn (array $a, array $b): int => abs($b['delta']) <=> abs($a['delta']));

        $items = array_slice($items, 0, 4);

        return [
            'hasPrevious' => true,
            'items'       => $items,
            'message'     => empty($items) ? 'Nenhuma mudança significativa desde a última pesquisa.' : null,
        ];
    }

    /**
     * @param array<int, array{key:string,label:string,severity:string,value:float}> $risks
     * @param array<int, array{title:string,reason:string,priority:string}> $recommendations
     */
    private function buildPriorityOfDay(array $risks, array $recommendations): array
    {
        if (!empty($risks)) {
            usort($risks, fn (array $a, array $b): int => $this->severityWeight($b['severity']) <=> $this->severityWeight($a['severity']));
            $top = $risks[0];

            return [
                'type'     => 'risk',
                'title'    => self::RISK_HEADLINES[$top['key']] ?? $top['label'] . '.',
                'severity' => $top['severity'],
            ];
        }

        if (!empty($recommendations)) {
            $sorted = $recommendations;
            usort($sorted, fn (array $a, array $b): int => $this->severityWeight($b['priority']) <=> $this->severityWeight($a['priority']));
            $top = $sorted[0];

            return [
                'type'     => 'recommendation',
                'title'    => $top['title'],
                'severity' => $top['priority'],
            ];
        }

        return [
            'type'     => 'none',
            'title'    => 'Nenhuma prioridade crítica identificada hoje. Continue monitorando os indicadores.',
            'severity' => null,
        ];
    }

    /**
     * @param array<int, array{title:string,reason:string,priority:string}> $recommendations
     */
    private function buildMainRecommendation(array $recommendations): array
    {
        if (empty($recommendations)) {
            return [
                'title'    => null,
                'reason'   => null,
                'priority' => null,
                'message'  => 'Nenhuma recomendação crítica no momento — mantenha as boas práticas atuais.',
            ];
        }

        usort($recommendations, fn (array $a, array $b): int => $this->severityWeight($b['priority']) <=> $this->severityWeight($a['priority']));

        $top = $recommendations[0];

        return [
            'title'    => $top['title'],
            'reason'   => $top['reason'],
            'priority' => $top['priority'],
            'message'  => null,
        ];
    }
}
