<?php

namespace App\Controllers;

use App\Laboratory\ScenarioGenerator;
use App\Services\AuthService;
use App\Services\QuestionService;

class LaboratoryController
{
    private ScenarioGenerator $generator;
    private AuthService $auth;
    private QuestionService $questions;

    public function __construct()
    {
        $this->generator = new ScenarioGenerator();
        $this->auth = new AuthService();
        $this->questions = new QuestionService();
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

        $scenarios = $this->generator->scenarios();
        $generatedCompanies = $this->generator->listGenerated();

        ob_start();
        require __DIR__ . '/../Views/laboratory/index.php';
        return ob_get_clean();
    }

    public function generate(): string
    {
        $this->protect();

        $scenario = trim($_POST['scenario'] ?? '');
        $employeesCount = (int) ($_POST['employees_count'] ?? 10);
        $surveysCount = (int) ($_POST['surveys_count'] ?? 1);
        $respondentsCount = (int) ($_POST['respondents_count'] ?? $employeesCount);

        $result = $this->generator->generate($scenario, $employeesCount, $surveysCount, $respondentsCount);

        ob_start();
        require __DIR__ . '/../Views/laboratory/result.php';
        return ob_get_clean();
    }

    public function clear(): void
    {
        $this->protect();

        $companyId = (int) ($_GET['company_id'] ?? 0);

        $this->generator->clear($companyId);

        header('Location: ' . BASE_URL . '/laboratory');
        exit;
    }

    private function hasActiveQuestions(): bool
    {
        foreach ($this->questions->all() as $question) {
            if ((int) $question['active'] === 1) {
                return true;
            }
        }

        return false;
    }

    public function demo(): string
    {
        $this->protect();

        $scenarios = $this->generator->scenarios();
        $currentDemo = $this->generator->currentDemoCompany();
        $noActiveQuestions = !$this->hasActiveQuestions();

        ob_start();
        require __DIR__ . '/../Views/laboratory/demo.php';
        return ob_get_clean();
    }

    public function generateDemo(): string
    {
        $this->protect();

        if (!$this->hasActiveQuestions()) {
            $scenarios = $this->generator->scenarios();
            $currentDemo = $this->generator->currentDemoCompany();
            $noActiveQuestions = true;

            ob_start();
            require __DIR__ . '/../Views/laboratory/demo.php';
            return ob_get_clean();
        }

        $scenario = trim($_POST['scenario'] ?? '') ?: 'saudavel';

        $result = $this->generator->generateDemo($scenario);
        $dashboardUrl = BASE_URL . '/dashboard?company_id=' . $result['company_id'];

        ob_start();
        require __DIR__ . '/../Views/laboratory/demo_result.php';
        return ob_get_clean();
    }
}
