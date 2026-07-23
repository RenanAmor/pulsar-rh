<?php

namespace App\Controllers;

use App\OIE\OrganizationalHistory;
use App\Services\AuthService;
use App\Services\CompanyService;
use App\Services\ExecutiveContextResolver;
use App\Services\SurveyService;

class HistoryController
{
    private OrganizationalHistory $history;
    private ExecutiveContextResolver $resolver;
    private SurveyService $surveys;
    private CompanyService $companies;
    private AuthService $auth;

    public function __construct(
        ?OrganizationalHistory $history = null,
        ?ExecutiveContextResolver $resolver = null,
        ?SurveyService $surveys = null,
        ?CompanyService $companies = null,
        ?AuthService $auth = null
    ) {
        $this->history = $history ?? new OrganizationalHistory();
        $this->resolver = $resolver ?? new ExecutiveContextResolver();
        $this->surveys = $surveys ?? new SurveyService();
        $this->companies = $companies ?? new CompanyService();
        $this->auth = $auth ?? new AuthService();
    }

    private function protect(): void
    {
        if (!$this->auth->check()) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    public function index(): string
    {
        $this->protect();

        $companyId = isset($_GET['company_id']) ? (int) $_GET['company_id'] : null;
        $surveyId = $this->resolver->resolveSurveyId($companyId);

        $company = null;
        $snapshots = [];

        if ($surveyId !== null) {
            $survey = $this->surveys->find($surveyId);
            $company = $this->companies->find((int) $survey['company_id']);
            $snapshots = array_reverse($this->history->series((int) $survey['company_id']));
        }

        ob_start();
        require __DIR__ . '/../Views/history/index.php';
        return ob_get_clean();
    }
}
