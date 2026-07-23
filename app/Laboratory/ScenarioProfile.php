<?php

namespace App\Laboratory;

class ScenarioProfile
{
    public const SCENARIOS = [
        'excelente' => [
            'label'     => 'Empresa Excelente',
            'default'   => [88, 98],
            'overrides' => [
                'Burnout' => [5, 20],
            ],
        ],
        'saudavel' => [
            'label'     => 'Empresa Saudável',
            'default'   => [65, 78],
            'overrides' => [
                'Burnout' => [15, 32],
            ],
        ],
        'cultura_forte' => [
            'label'     => 'Empresa com Cultura Forte',
            'default'   => [65, 80],
            'overrides' => [
                'Cultura'               => [88, 98],
                'Clima Organizacional'  => [85, 97],
                'Pertencimento'         => [85, 97],
                'Trabalho em Equipe'    => [82, 95],
                'Segurança Psicológica' => [80, 93],
                'Burnout'               => [15, 32],
            ],
        ],
        'lideranca_fraca' => [
            'label'     => 'Empresa com Liderança Fraca',
            'default'   => [58, 76],
            'overrides' => [
                'Liderança'   => [15, 32],
                'Comunicação' => [35, 55],
                'Engajamento' => [40, 58],
            ],
        ],
        'burnout_elevado' => [
            'label'     => 'Empresa com Burnout Elevado',
            'default'   => [55, 74],
            'overrides' => [
                'Burnout'     => [78, 95],
                'Bem-estar'   => [15, 32],
                'Engajamento' => [35, 52],
                'Motivação'   => [30, 50],
            ],
        ],
        'alto_turnover' => [
            'label'     => 'Empresa com Alto Turnover',
            'default'   => [55, 72],
            'overrides' => [
                'Satisfação'              => [15, 35],
                'Engajamento'             => [18, 38],
                'Reconhecimento'          => [15, 35],
                'Pertencimento'           => [15, 35],
                'Justiça Organizacional'  => [20, 40],
            ],
        ],
        'comunicacao_deficiente' => [
            'label'     => 'Empresa com Comunicação Deficiente',
            'default'   => [60, 78],
            'overrides' => [
                'Comunicação'           => [15, 35],
                'Trabalho em Equipe'    => [30, 50],
                'Liderança'             => [42, 60],
                'Clima Organizacional'  => [35, 55],
            ],
        ],
        'crise' => [
            'label'     => 'Empresa em Crise',
            'default'   => [22, 42],
            'overrides' => [
                'Burnout'     => [72, 92],
                'Liderança'   => [15, 30],
                'Engajamento' => [15, 30],
                'Comunicação' => [18, 35],
                'Bem-estar'   => [15, 30],
            ],
        ],
        'transformacao' => [
            'label'     => 'Empresa em Transformação',
            'default'   => [55, 74],
            'overrides' => [
                'Cultura'               => [38, 60],
                'Clima Organizacional'  => [32, 55],
                'Comunicação'           => [42, 62],
                'Desenvolvimento'       => [65, 85],
                'Liderança'             => [55, 74],
            ],
        ],
        'aleatoria' => [
            'label'     => 'Empresa Aleatória',
            'default'   => [0, 100],
            'overrides' => [],
        ],
    ];

    public function labels(): array
    {
        $labels = [];

        foreach (self::SCENARIOS as $key => $scenario) {
            $labels[$key] = $scenario['label'];
        }

        return $labels;
    }

    public function labelFor(string $scenarioKey): string
    {
        return self::SCENARIOS[$scenarioKey]['label'] ?? self::SCENARIOS['saudavel']['label'];
    }

    public function targetRangeFor(string $scenarioKey, string $dimension): array
    {
        $scenario = self::SCENARIOS[$scenarioKey] ?? self::SCENARIOS['saudavel'];

        return $scenario['overrides'][$dimension] ?? $scenario['default'];
    }

    public function samplePercentage(string $scenarioKey, string $dimension): float
    {
        [$min, $max] = $this->targetRangeFor($scenarioKey, $dimension);

        return round(random_int((int) ($min * 100), (int) ($max * 100)) / 100, 2);
    }

    public function denormalizeToScale(float $percentage, int $scaleMin, int $scaleMax): int
    {
        $percentage = max(0.0, min(100.0, $percentage));

        $value = $scaleMin + ($percentage / 100) * ($scaleMax - $scaleMin);

        return (int) round($value);
    }

    public function percentageToYesNo(float $percentage): string
    {
        return random_int(1, 100) <= $percentage ? 'Sim' : 'Não';
    }
}
