<?php

namespace App\OIE;

use App\Indicators\IndicatorEngine;
use App\Services\CompanyService;
use App\Services\SurveyService;

class OIEEngine
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

    private IndicatorEngine $indicatorEngine;
    private OrganizationalHistory $history;
    private PatternDetector $patterns;
    private OrganizationalAnalyzer $analyzer;
    private RecommendationBuilder $recommendations;
    private ContextBuilder $contextBuilder;
    private SurveyService $surveys;
    private CompanyService $companies;

    public function __construct(
        ?IndicatorEngine $indicatorEngine = null,
        ?OrganizationalHistory $history = null,
        ?PatternDetector $patterns = null,
        ?OrganizationalAnalyzer $analyzer = null,
        ?RecommendationBuilder $recommendations = null,
        ?ContextBuilder $contextBuilder = null,
        ?SurveyService $surveys = null,
        ?CompanyService $companies = null
    ) {
        $this->indicatorEngine = $indicatorEngine ?? new IndicatorEngine();
        $this->history = $history ?? new OrganizationalHistory($this->indicatorEngine);
        $this->patterns = $patterns ?? new PatternDetector();
        $this->analyzer = $analyzer ?? new OrganizationalAnalyzer($this->indicatorEngine, $this->patterns);
        $this->recommendations = $recommendations ?? new RecommendationBuilder();
        $this->contextBuilder = $contextBuilder ?? new ContextBuilder();
        $this->surveys = $surveys ?? new SurveyService();
        $this->companies = $companies ?? new CompanyService();
    }

    public function toSnapshotShape(array $result): array
    {
        $shape = [];

        foreach (self::CATEGORY_TO_COLUMN as $categoryName => $column) {
            $shape[$column] = 0;

            foreach ($result['categories'] ?? [] as $category) {
                if ($category['category'] === $categoryName) {
                    $shape[$column] = $category['average'] ?? 0;
                }
            }
        }

        $shape['final_score'] = $result['final_score'] ?? 0;
        $shape['burnout_risk'] = $result['burnout_risk'] ?? 0;
        $shape['turnover_risk'] = $result['turnover_risk'] ?? 0;

        return $shape;
    }

    private function findPreviousSurveyId(int $companyId, int $currentSurveyId): ?int
    {
        $surveys = array_values(array_filter(
            $this->surveys->all(),
            fn (array $survey): bool => (int) $survey['company_id'] === $companyId
        ));

        usort($surveys, fn (array $a, array $b): int => $a['id'] <=> $b['id']);

        $previousId = null;

        foreach ($surveys as $survey) {
            if ((int) $survey['id'] === $currentSurveyId) {
                break;
            }

            $previousId = (int) $survey['id'];
        }

        return $previousId;
    }

    public function analyze(int $surveyId, array $scope = []): array
    {
        $survey = $this->surveys->find($surveyId);

        if (!$survey) {
            return ['error' => 'Pesquisa não encontrada.'];
        }

        $companyId = (int) $survey['company_id'];
        $company = $this->companies->find($companyId);

        $currentResult = $this->indicatorEngine->run($surveyId, $scope, false);

        $previousSurveyId = $this->findPreviousSurveyId($companyId, $surveyId);
        $previousResult = $previousSurveyId !== null
            ? $this->indicatorEngine->run($previousSurveyId, $scope, false)
            : null;

        $patterns = $this->patterns->compare(
            $this->toSnapshotShape($currentResult),
            $previousResult !== null ? $this->toSnapshotShape($previousResult) : null
        );

        $risks = $this->analyzer->detectRisks($currentResult, $previousResult);

        $categoryAverages = [];
        foreach ($currentResult['categories'] ?? [] as $category) {
            $categoryAverages[$category['category']] = $category['average'];
        }

        $recommendations = $this->recommendations->build(
            $categoryAverages,
            $currentResult['final_score'] ?? null,
            (float) ($currentResult['burnout_risk'] ?? 0)
        );

        $historySeries = $this->history->series($companyId);

        $organization = [
            'company_id'        => $companyId,
            'company_name'      => $company['trade_name'] ?? null,
            'survey_id'         => $surveyId,
            'survey_title'      => $survey['title'],
            'scope'             => $scope,
            'previous_survey_id' => $previousSurveyId,
        ];

        return $this->contextBuilder->build(
            $organization,
            $currentResult,
            $historySeries,
            $patterns,
            $risks,
            $recommendations
        );
    }

    public function compareSurveys(int $surveyIdA, int $surveyIdB, array $scope = []): array
    {
        return $this->analyzer->compareSurveys($surveyIdA, $surveyIdB, $scope);
    }

    public function behaviorByDepartment(int $surveyId, array $departmentIds): array
    {
        return $this->analyzer->behaviorByDepartment($surveyId, $departmentIds);
    }

    public function behaviorByTeam(int $surveyId, array $teamIds): array
    {
        return $this->analyzer->behaviorByTeam($surveyId, $teamIds);
    }

    public function behaviorByManager(int $surveyId, array $managerIds): array
    {
        return $this->analyzer->behaviorByManager($surveyId, $managerIds);
    }

    public function behaviorByBranch(int $surveyId, array $branchIds): array
    {
        return $this->analyzer->behaviorByBranch($surveyId, $branchIds);
    }
}
