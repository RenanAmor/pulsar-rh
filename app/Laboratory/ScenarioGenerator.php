<?php

namespace App\Laboratory;

use App\Indicators\IndicatorEngine;
use App\Services\CompanyService;
use App\Services\SurveyQuestionService;

class ScenarioGenerator
{
    private const LAB_TAG = '[LAB]';
    private const DEMO_TAG = '[DEMO]';

    private const DEMO_EMPLOYEES = 50;
    private const DEMO_SURVEYS = 1;
    private const DEMO_RESPONDENTS = 40;

    private OrganizationGenerator $organizationGenerator;
    private EmployeeGenerator $employeeGenerator;
    private SurveyGenerator $surveyGenerator;
    private AnswerGenerator $answerGenerator;
    private IndicatorEngine $indicatorEngine;
    private CompanyService $companies;
    private SurveyQuestionService $surveyQuestions;
    private ScenarioProfile $profile;

    public function __construct()
    {
        $this->organizationGenerator = new OrganizationGenerator();
        $this->employeeGenerator = new EmployeeGenerator();
        $this->surveyGenerator = new SurveyGenerator();
        $this->answerGenerator = new AnswerGenerator();
        $this->indicatorEngine = new IndicatorEngine();
        $this->companies = new CompanyService();
        $this->surveyQuestions = new SurveyQuestionService();
        $this->profile = new ScenarioProfile();
    }

    public function scenarios(): array
    {
        return $this->profile->labels();
    }

    public function generate(
        string $scenarioKey,
        int $employeesCount,
        int $surveysCount,
        int $respondentsPerSurvey,
        string $tag = self::LAB_TAG
    ): array {
        $label = $this->profile->labelFor($scenarioKey);

        $organization = $this->organizationGenerator->generate($label, $tag);
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
                'survey_id'      => $surveyId,
                'respondents'    => $respondents,
                'questions_count' => count($this->surveyQuestions->allForSurvey($surveyId)),
                'indicators'     => $indicators,
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

    public function currentDemoCompany(): ?array
    {
        foreach ($this->companies->all() as $company) {
            if (str_starts_with($company['trade_name'], self::DEMO_TAG)) {
                return $company;
            }
        }

        return null;
    }

    private function clearDemoCompany(): void
    {
        foreach ($this->companies->all() as $company) {
            if (str_starts_with($company['trade_name'], self::DEMO_TAG)) {
                $this->companies->delete((int) $company['id']);
            }
        }
    }

    /**
     * Gera (ou regenera, substituindo a anterior) a empresa de demonstração
     * de um clique: volumes fixos, tag própria e o Motor de Indicadores já
     * calculado e persistido ao final (via generate()).
     */
    public function generateDemo(string $scenarioKey): array
    {
        $start = microtime(true);

        $this->clearDemoCompany();

        $result = $this->generate(
            $scenarioKey,
            self::DEMO_EMPLOYEES,
            self::DEMO_SURVEYS,
            self::DEMO_RESPONDENTS,
            self::DEMO_TAG
        );

        $answersCount = 0;

        foreach ($result['surveys'] as $survey) {
            $answersCount += $survey['respondents'] * $survey['questions_count'];
        }

        $result['answers_count'] = $answersCount;
        $result['duration_seconds'] = round(microtime(true) - $start, 1);

        return $result;
    }
}
