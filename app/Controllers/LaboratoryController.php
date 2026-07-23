<?php

namespace App\Controllers;

use App\Laboratory\ScenarioGenerator;
use App\Services\AuthService;

class LaboratoryController
{
    private ScenarioGenerator $generator;
    private AuthService $auth;

    public function __construct()
    {
        $this->generator = new ScenarioGenerator();
        $this->auth = new AuthService();
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
}
