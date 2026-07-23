<?php

namespace App\Laboratory;

use App\Indicators\IndicatorEngine;
use App\Services\CompanyService;

class ScenarioGenerator
{
    private const LAB_TAG = '[LAB]';

    private OrganizationGenerator $organizationGenerator;
    private EmployeeGenerator $employeeGenerator;
    private SurveyGenerator $surveyGenerator;
    private AnswerGenerator $answerGenerator;
    private IndicatorEngine $indicatorEngine;
    private CompanyService $companies;
    private ScenarioProfile $profile;

    public function __construct()
    {
        $this->organizationGenerator = new OrganizationGenerator();
        $this->employeeGenerator = new EmployeeGenerator();
        $this->surveyGenerator = new SurveyGenerator();
        $this->answerGenerator = new AnswerGenerator();
        $this->indicatorEngine = new IndicatorEngine();
        $this->companies = new CompanyService();
        $this->profile = new ScenarioProfile();
    }

    public function scenarios(): array
    {
        return $this->profile->labels();
    }

    public function generate(string $scenarioKey, int $employeesCount, int $surveysCount, int $respondentsPerSurvey): array
    {
        $label = $this->profile->labelFor($scenarioKey);

        $organization = $this->organizationGenerator->generate($label);
        $employeeIds = $this->employeeGenerator->generate($organization, max(1, $employeesCount));
        $surveyIds = $this->surveyGenerator->generate($organization['company_id'], $label, max(1, $surveysCount));

        $surveys = [];

        foreach ($surveyIds as $surveyId) {
            $respondents = $this->answerGenerator->generate(
                $surveyId,
                $employeeIds,
                $scenarioKey,
                max(1, $respondentsPerSurvey)
            );

            $indicators = $this->indicatorEngine->run($surveyId);

            $surveys[] = [
                'survey_id'  => $surveyId,
                'respondents' => $respondents,
                'indicators' => $indicators,
            ];
        }

        return [
            'scenario'        => $scenarioKey,
            'label'           => $label,
            'company_id'      => $organization['company_id'],
            'company_name'    => $organization['company_name'],
            'employees_count' => count($employeeIds),
            'surveys'         => $surveys,
        ];
    }

    public function listGenerated(): array
    {
        return array_values(array_filter(
            $this->companies->all(),
            fn (array $company): bool => str_starts_with($company['trade_name'], self::LAB_TAG)
        ));
    }

    public function clear(int $companyId): bool
    {
        $company = $this->companies->find($companyId);

        if (!$company || !str_starts_with($company['trade_name'], self::LAB_TAG)) {
            return false;
        }

        return $this->companies->delete($companyId);
    }
}
