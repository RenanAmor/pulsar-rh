<?php

namespace App\OIE;

class PatternDetector
{
    private const COMPARABLE_COLUMNS = [
        'leadership', 'communication', 'engagement', 'wellbeing',
        'development', 'culture', 'collaboration', 'recognition',
        'final_score',
    ];

    private const INVERTED_COLUMNS = [
        'burnout_risk', 'turnover_risk',
    ];

    private const STABLE_THRESHOLD = 3.0;
    private const ABRUPT_THRESHOLD = 15.0;

    private function classifyDelta(float $delta, bool $inverted): string
    {
        $effectiveDelta = $inverted ? -$delta : $delta;

        if (abs($effectiveDelta) < self::STABLE_THRESHOLD) {
            return 'Estável';
        }

        if ($effectiveDelta >= self::ABRUPT_THRESHOLD) {
            return 'Mudança Brusca (Melhora)';
        }

        if ($effectiveDelta >= self::STABLE_THRESHOLD) {
            return 'Melhora';
        }

        if ($effectiveDelta <= -self::ABRUPT_THRESHOLD) {
            return 'Mudança Brusca (Piora)';
        }

        return 'Piora';
    }

    public function compare(array $current, ?array $previous): array
    {
        if ($previous === null) {
            return [
                'has_previous' => false,
                'dimensions'   => [],
                'overall'      => 'Sem histórico anterior para comparação',
            ];
        }

        $dimensions = [];

        foreach (array_merge(self::COMPARABLE_COLUMNS, self::INVERTED_COLUMNS) as $column) {
            $currentValue = (float) ($current[$column] ?? 0);
            $previousValue = (float) ($previous[$column] ?? 0);
            $delta = round($currentValue - $previousValue, 2);
            $inverted = in_array($column, self::INVERTED_COLUMNS, true);

            $dimensions[$column] = [
                'current'        => $currentValue,
                'previous'       => $previousValue,
                'delta'          => $delta,
                'classification' => $this->classifyDelta($delta, $inverted),
            ];
        }

        return [
            'has_previous' => true,
            'dimensions'   => $dimensions,
            'overall'      => $dimensions['final_score']['classification'],
        ];
    }

    public function detectTrendSeries(array $snapshots, string $column): string
    {
        $values = array_map(fn (array $snapshot) => (float) ($snapshot[$column] ?? 0), $snapshots);

        if (count($values) < 2) {
            return 'Histórico Insuficiente';
        }

        $deltas = [];

        for ($i = 1; $i < count($values); $i++) {
            $deltas[] = $values[$i] - $values[$i - 1];
        }

        $averageDelta = array_sum($deltas) / count($deltas);
        $inverted = in_array($column, self::INVERTED_COLUMNS, true);
        $effectiveAverage = $inverted ? -$averageDelta : $averageDelta;

        if (abs($effectiveAverage) < 1.0) {
            return 'Estável';
        }

        return $effectiveAverage > 0 ? 'Crescente' : 'Decrescente';
    }
}
