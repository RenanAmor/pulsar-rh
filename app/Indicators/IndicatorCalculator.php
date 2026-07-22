<?php

namespace App\Indicators;

class IndicatorCalculator
{
    public function normalizeAnswer(array $answer): ?float
    {
        if ($answer['answer_type'] === 'Escala') {
            if ($answer['score'] === null) {
                return null;
            }

            $min = (float) $answer['scale_min'];
            $max = (float) $answer['scale_max'];

            if ($max <= $min) {
                return null;
            }

            $ratio = ((float) $answer['score'] - $min) / ($max - $min);

            return max(0.0, min(100.0, $ratio * 100));
        }

        if ($answer['answer_type'] === 'SimNão') {
            if ($answer['answer_text'] === null) {
                return null;
            }

            return $answer['answer_text'] === 'Sim' ? 100.0 : 0.0;
        }

        return null;
    }

    public function average(array $values): ?float
    {
        $values = array_values(array_filter($values, fn ($value) => $value !== null));

        if (empty($values)) {
            return null;
        }

        return round(array_sum($values) / count($values), 2);
    }

    public function weightedAverage(array $answers): ?float
    {
        $weightedSum = 0.0;
        $weightTotal = 0.0;

        foreach ($answers as $answer) {
            $normalized = $this->normalizeAnswer($answer);

            if ($normalized === null) {
                continue;
            }

            $weight = isset($answer['weight']) ? (float) $answer['weight'] : 1.0;

            $weightedSum += $normalized * $weight;
            $weightTotal += $weight;
        }

        if ($weightTotal <= 0.0) {
            return null;
        }

        return round($weightedSum / $weightTotal, 2);
    }

    public function participation(int $responded, int $eligible): float
    {
        if ($eligible <= 0) {
            return 0.0;
        }

        return round(min($responded, $eligible) / $eligible * 100, 2);
    }

    public function classify(?float $finalScore): string
    {
        if ($finalScore === null) {
            return 'Crítico';
        }

        if ($finalScore >= 80) {
            return 'Excelente';
        }

        if ($finalScore >= 60) {
            return 'Saudável';
        }

        if ($finalScore >= 40) {
            return 'Atenção';
        }

        return 'Crítico';
    }

    public function burnoutRisk(?float $burnoutAverage): float
    {
        return $burnoutAverage === null ? 0.0 : $burnoutAverage;
    }
}
