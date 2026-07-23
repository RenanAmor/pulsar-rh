<?php

namespace App\Laboratory;

class ScenarioProfile
{
    public const SCENARIOS = [
        'saudavel' => [
            'label'     => 'Empresa Saudável',
            'default'   => [75, 95],
            'overrides' => [],
        ],
        'crescimento' => [
            'label'     => 'Empresa em Crescimento',
            'default'   => [60, 80],
            'overrides' => [
                'Engajamento'           => [75, 95],
                'Motivação'             => [70, 90],
                'Desenvolvimento'       => [70, 90],
                'Cultura'               => [50, 75],
                'Clima Organizacional'  => [50, 75],
            ],
        ],
        'alta_rotatividade' => [
            'label'     => 'Empresa com Alta Rotatividade',
            'default'   => [55, 70],
            'overrides' => [
                'Satisfação'     => [15, 35],
                'Engajamento'    => [20, 40],
                'Reconhecimento' => [15, 35],
                'Pertencimento'  => [15, 35],
            ],
        ],
        'problemas_lideranca' => [
            'label'     => 'Empresa com Problemas de Liderança',
            'default'   => [60, 80],
            'overrides' => [
                'Liderança' => [15, 35],
            ],
        ],
        'baixa_comunicacao' => [
            'label'     => 'Empresa com Baixa Comunicação',
            'default'   => [60, 80],
            'overrides' => [
                'Comunicação'         => [15, 35],
                'Trabalho em Equipe'  => [30, 50],
            ],
        ],
        'baixo_engajamento' => [
            'label'     => 'Empresa com Baixo Engajamento',
            'default'   => [60, 80],
            'overrides' => [
                'Engajamento' => [15, 35],
                'Motivação'   => [20, 40],
                'Satisfação'  => [25, 45],
            ],
        ],
        'alto_burnout' => [
            'label'     => 'Empresa com Alto Burnout',
            'default'   => [55, 75],
            'overrides' => [
                'Burnout'   => [75, 95],
                'Bem-estar' => [15, 35],
            ],
        ],
        'conflitos_equipes' => [
            'label'     => 'Empresa com Conflitos entre Equipes',
            'default'   => [60, 80],
            'overrides' => [
                'Trabalho em Equipe'      => [15, 35],
                'Segurança Psicológica'   => [20, 40],
            ],
        ],
        'baixa_satisfacao' => [
            'label'     => 'Empresa com Baixa Satisfação',
            'default'   => [60, 80],
            'overrides' => [
                'Satisfação'     => [15, 35],
                'Reconhecimento' => [25, 45],
            ],
        ],
        'mudanca_organizacional' => [
            'label'     => 'Empresa em Processo de Mudança Organizacional',
            'default'   => [55, 75],
            'overrides' => [
                'Cultura'               => [35, 65],
                'Clima Organizacional'  => [30, 60],
                'Comunicação'           => [40, 60],
                'Desenvolvimento'       => [60, 80],
            ],
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
