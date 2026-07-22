<?php

namespace App\Indicators;

use App\Services\SurveyService;

class IndicatorEngine
{
    private const CATEGORY_TO_COLUMN = [
        'Liderança'       => 'leadership',
        'Comunicação'     => 'communication',
        'Engajamento'     => 'engagement',
        'Cultura'         => 'culture',
        'Bem-estar'       => 'wellbeing',
        'Desenvolvimento' => 'development',
        'Colaboração'     => 'collaboration',
        'Reconhecimento'  => 'recognition',
    ];

    private const SCOPE_KEYS = [
        'branch_id',
        'department_id',
        'team_id',
        'manager_id',
        'position_id',
    ];

    private IndicatorService $service;
    private IndicatorCalculator $calculator;
    private SurveyService $surveys;

    public function __construct(
        ?IndicatorService $service = null,
        ?IndicatorCalculator $calculator = null,
        ?SurveyService $surveys = null
    ) {
        $this->service = $service ?? new IndicatorService();
        $this->calculator = $calculator ?? new IndicatorCalculator();
        $this->surveys = $surveys ?? new SurveyService();
    }

    public function run(int $surveyId, array $scope = [], bool $persist = true): array
    {
        $survey = $this->surveys->find($surveyId);

        if (!$survey) {
            return [
                'error' => 'Pesquisa não encontrada.',
            ];
        }

        $companyId = (int) $survey['company_id'];
        $fullScope = array_merge(['company_id' => $companyId], $scope);

        $categories = $this->service->calculateAllCategories($surveyId, $fullScope);
        $dimensions = $this->service->calculateAllDimensions($surveyId, $fullScope);
        $participation = $this->service->participation($surveyId, $fullScope);
        $responsesCount = $this->service->responsesCount($surveyId, $fullScope);

        $categoryAverages = [];

        foreach ($categories as $category) {
            $categoryAverages[$category['category']] = $category['average'];
        }

        $finalScore = $this->calculator->average(array_values($categoryAverages));
        $classification = $this->calculator->classify($finalScore);

        $burnoutAverage = null;

        foreach ($dimensions as $dimension) {
            if ($dimension['dimension'] === 'Burnout') {
                $burnoutAverage = $dimension['average'];
                break;
            }
        }

        $burnoutRisk = $this->calculator->burnoutRisk($burnoutAverage);

        $isCompanyGrain = empty(array_intersect_key($scope, array_flip(self::SCOPE_KEYS)));
        $persisted = false;

        if ($persist && $isCompanyGrain) {
            $snapshot = ['company_id' => $companyId, 'survey_id' => $surveyId];

            foreach (self::CATEGORY_TO_COLUMN as $categoryName => $column) {
                $snapshot[$column] = $categoryAverages[$categoryName] ?? 0;
            }

            $snapshot['turnover_risk'] = 0;
            $snapshot['burnout_risk'] = $burnoutRisk;
            $snapshot['final_score'] = $finalScore ?? 0;
            $snapshot['classification'] = $classification;

            $persisted = $this->service->saveSnapshot($snapshot);
        }

        return [
            'survey_id'       => $surveyId,
            'company_id'      => $companyId,
            'scope'           => $scope,
            'categories'      => $categories,
            'dimensions'      => $dimensions,
            'participation'   => $participation,
            'responses_count' => $responsesCount,
            'final_score'     => $finalScore,
            'classification'  => $classification,
            'burnout_risk'    => $burnoutRisk,
            'turnover_risk'   => 0.0,
            'persisted'       => $persisted,
        ];
    }

    public function runForCompany(int $surveyId): array
    {
        return $this->run($surveyId, []);
    }

    public function runForBranch(int $surveyId, int $branchId): array
    {
        return $this->run($surveyId, ['branch_id' => $branchId], false);
    }

    public function runForDepartment(int $surveyId, int $departmentId): array
    {
        return $this->run($surveyId, ['department_id' => $departmentId], false);
    }

    public function runForTeam(int $surveyId, int $teamId): array
    {
        return $this->run($surveyId, ['team_id' => $teamId], false);
    }

    public function runForManager(int $surveyId, int $managerId): array
    {
        return $this->run($surveyId, ['manager_id' => $managerId], false);
    }

    public function runForPosition(int $surveyId, int $positionId): array
    {
        return $this->run($surveyId, ['position_id' => $positionId], false);
    }

    public function history(int $companyId, ?int $surveyId = null): array
    {
        return $this->service->history($companyId, $surveyId);
    }
}
