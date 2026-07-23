<?php

namespace App\OIE;

use App\Indicators\IndicatorEngine;

class OrganizationalAnalyzer
{
    private const LOW_THRESHOLD = 50.0;
    private const BURNOUT_THRESHOLD = 65.0;
    private const TURNOVER_THRESHOLD = 60.0;
    private const DROP_THRESHOLD = 10.0;

    private IndicatorEngine $engine;
    private PatternDetector $patterns;

    public function __construct(?IndicatorEngine $engine = null, ?PatternDetector $patterns = null)
    {
        $this->engine = $engine ?? new IndicatorEngine();
        $this->patterns = $patterns ?? new PatternDetector();
    }

    public function categoryAverage(array $result, string $name): ?float
    {
        foreach ($result['categories'] ?? [] as $category) {
            if ($category['category'] === $name) {
                return $category['average'];
            }
        }

        return null;
    }

    public function dimensionAverage(array $result, string $name): ?float
    {
        foreach ($result['dimensions'] ?? [] as $dimension) {
            if ($dimension['dimension'] === $name) {
                return $dimension['average'];
            }
        }

        return null;
    }

    public function turnoverRiskScore(array $result): float
    {
        $factors = [
            $this->categoryAverage($result, 'Engajamento'),
            $this->dimensionAverage($result, 'Satisfação'),
            $this->categoryAverage($result, 'Reconhecimento'),
            $this->dimensionAverage($result, 'Pertencimento'),
        ];

        $known = array_values(array_filter($factors, fn ($value) => $value !== null));

        if (empty($known)) {
            return 0.0;
        }

        $average = array_sum($known) / count($known);

        return round(100 - $average, 2);
    }

    /**
     * Detecta riscos organizacionais utilizando exclusivamente os
     * indicadores produzidos pelo Motor de Indicadores. Não realiza
     * nenhuma consulta ao banco e não depende de IA.
     */
    public function detectRisks(array $current, ?array $previous = null): array
    {
        $risks = [];

        $leadership = $this->categoryAverage($current, 'Liderança');
        if ($leadership !== null && $leadership < self::LOW_THRESHOLD) {
            $risks[] = [
                'key'      => 'baixa_lideranca',
                'label'    => 'Baixa Liderança',
                'severity' => $leadership < 30 ? 'Alta' : 'Média',
                'value'    => $leadership,
            ];
        }

        $communication = $this->categoryAverage($current, 'Comunicação');
        if ($communication !== null && $communication < self::LOW_THRESHOLD) {
            $risks[] = [
                'key'      => 'baixa_comunicacao',
                'label'    => 'Baixa Comunicação',
                'severity' => $communication < 30 ? 'Alta' : 'Média',
                'value'    => $communication,
            ];
        }

        $engagement = $this->categoryAverage($current, 'Engajamento');
        if ($engagement !== null && $engagement < self::LOW_THRESHOLD) {
            $risks[] = [
                'key'      => 'baixo_engajamento',
                'label'    => 'Baixo Engajamento',
                'severity' => $engagement < 30 ? 'Alta' : 'Média',
                'value'    => $engagement,
            ];
        }

        $burnoutRisk = (float) ($current['burnout_risk'] ?? 0);
        if ($burnoutRisk >= self::BURNOUT_THRESHOLD) {
            $risks[] = [
                'key'      => 'risco_burnout',
                'label'    => 'Risco de Burnout',
                'severity' => $burnoutRisk >= 80 ? 'Crítica' : 'Alta',
                'value'    => $burnoutRisk,
            ];
        }

        $turnoverRisk = $this->turnoverRiskScore($current);
        if ($turnoverRisk >= self::TURNOVER_THRESHOLD) {
            $risks[] = [
                'key'      => 'risco_turnover',
                'label'    => 'Risco de Turnover',
                'severity' => $turnoverRisk >= 80 ? 'Crítica' : 'Alta',
                'value'    => $turnoverRisk,
            ];
        }

        $teamwork = $this->dimensionAverage($current, 'Trabalho em Equipe');
        $psychSafety = $this->dimensionAverage($current, 'Segurança Psicológica');
        if (($teamwork !== null && $teamwork < self::LOW_THRESHOLD) || ($psychSafety !== null && $psychSafety < self::LOW_THRESHOLD)) {
            $risks[] = [
                'key'      => 'conflitos_entre_equipes',
                'label'    => 'Conflitos entre Equipes',
                'severity' => 'Média',
                'value'    => min(array_filter([$teamwork, $psychSafety], fn ($v) => $v !== null)),
            ];
        }

        if ($previous !== null) {
            $currentCulture = $this->categoryAverage($current, 'Cultura');
            $previousCulture = $this->categoryAverage($previous, 'Cultura');

            if ($currentCulture !== null && $previousCulture !== null && ($currentCulture - $previousCulture) <= -self::DROP_THRESHOLD) {
                $risks[] = [
                    'key'      => 'queda_de_clima',
                    'label'    => 'Queda de Clima Organizacional',
                    'severity' => 'Alta',
                    'value'    => round($currentCulture - $previousCulture, 2),
                ];
            }

            $currentSatisfaction = $this->dimensionAverage($current, 'Satisfação');
            $previousSatisfaction = $this->dimensionAverage($previous, 'Satisfação');

            if ($currentSatisfaction !== null && $previousSatisfaction !== null && ($currentSatisfaction - $previousSatisfaction) <= -self::DROP_THRESHOLD) {
                $risks[] = [
                    'key'      => 'perda_de_satisfacao',
                    'label'    => 'Perda de Satisfação',
                    'severity' => 'Alta',
                    'value'    => round($currentSatisfaction - $previousSatisfaction, 2),
                ];
            }
        }

        return $risks;
    }

    public function compareSurveys(int $surveyIdA, int $surveyIdB, array $scope = []): array
    {
        $resultA = $this->engine->run($surveyIdA, $scope, false);
        $resultB = $this->engine->run($surveyIdB, $scope, false);

        return [
            'survey_a'   => $resultA,
            'survey_b'   => $resultB,
            'comparison' => $this->patterns->compare($resultA, $resultB),
        ];
    }

    private function behaviorBy(int $surveyId, array $ids, string $runMethod): array
    {
        $behavior = [];

        foreach ($ids as $id) {
            $behavior[] = [
                'id'     => $id,
                'result' => $this->engine->{$runMethod}($surveyId, $id),
            ];
        }

        return $behavior;
    }

    public function behaviorByBranch(int $surveyId, array $branchIds): array
    {
        return $this->behaviorBy($surveyId, $branchIds, 'runForBranch');
    }

    public function behaviorByDepartment(int $surveyId, array $departmentIds): array
    {
        return $this->behaviorBy($surveyId, $departmentIds, 'runForDepartment');
    }

    public function behaviorByTeam(int $surveyId, array $teamIds): array
    {
        return $this->behaviorBy($surveyId, $teamIds, 'runForTeam');
    }

    public function behaviorByManager(int $surveyId, array $managerIds): array
    {
        return $this->behaviorBy($surveyId, $managerIds, 'runForManager');
    }

    public function behaviorByPosition(int $surveyId, array $positionIds): array
    {
        return $this->behaviorBy($surveyId, $positionIds, 'runForPosition');
    }
}
