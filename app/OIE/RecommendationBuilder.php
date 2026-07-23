<?php

namespace App\OIE;

class RecommendationBuilder
{
    private const THRESHOLD = 50.0;
    private const CRITICAL_THRESHOLD = 40.0;
    private const BURNOUT_THRESHOLD = 65.0;

    private function priorityFor(float $value, float $threshold): string
    {
        $gap = $threshold - $value;

        if ($gap >= 25) {
            return 'Crítica';
        }

        if ($gap >= 15) {
            return 'Alta';
        }

        return 'Média';
    }

    /**
     * @param array $categoryAverages Mapa 'Categoria' => média (0-100) ou null
     */
    public function build(array $categoryAverages, ?float $finalScore, float $burnoutRisk): array
    {
        $recommendations = [];

        $communication = $categoryAverages['Comunicação'] ?? null;
        if ($communication !== null && $communication < self::THRESHOLD) {
            $recommendations[] = [
                'title'    => 'Fortalecer Comunicação',
                'reason'   => 'Indicador de Comunicação abaixo do esperado (' . number_format($communication, 2) . ').',
                'priority' => $this->priorityFor($communication, self::THRESHOLD),
            ];
        }

        $leadership = $categoryAverages['Liderança'] ?? null;
        if ($leadership !== null && $leadership < self::THRESHOLD) {
            $recommendations[] = [
                'title'    => 'Treinamento de Liderança',
                'reason'   => 'Indicador de Liderança abaixo do esperado (' . number_format($leadership, 2) . ').',
                'priority' => $this->priorityFor($leadership, self::THRESHOLD),
            ];
        }

        $engagement = $categoryAverages['Engajamento'] ?? null;
        if ($engagement !== null && $engagement < self::THRESHOLD) {
            $recommendations[] = [
                'title'    => 'Feedback Contínuo',
                'reason'   => 'Indicador de Engajamento abaixo do esperado (' . number_format($engagement, 2) . ').',
                'priority' => $this->priorityFor($engagement, self::THRESHOLD),
            ];
        }

        if ($burnoutRisk >= self::BURNOUT_THRESHOLD) {
            $recommendations[] = [
                'title'    => 'Acompanhamento Psicológico',
                'reason'   => 'Risco de burnout elevado (' . number_format($burnoutRisk, 2) . ').',
                'priority' => $this->priorityFor(100 - $burnoutRisk, 100 - self::BURNOUT_THRESHOLD),
            ];
        }

        $development = $categoryAverages['Desenvolvimento'] ?? null;
        if ($development !== null && $development < self::THRESHOLD) {
            $recommendations[] = [
                'title'    => 'Plano de Desenvolvimento',
                'reason'   => 'Indicador de Desenvolvimento abaixo do esperado (' . number_format($development, 2) . ').',
                'priority' => $this->priorityFor($development, self::THRESHOLD),
            ];
        }

        if ($finalScore !== null && $finalScore < self::CRITICAL_THRESHOLD) {
            $recommendations[] = [
                'title'    => 'Revisão Organizacional',
                'reason'   => 'Índice Geral em nível crítico (' . number_format($finalScore, 2) . ').',
                'priority' => 'Crítica',
            ];
        }

        return $recommendations;
    }
}
