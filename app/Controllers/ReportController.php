<?php

namespace App\Controllers;

use App\AI\AIManager;
use App\Services\AuthService;
use App\Services\SurveyService;

/**
 * Centro de Relatórios do Pulsar RH — hoje reutiliza integralmente o
 * relatório executivo já produzido pela IA (App\AI\AIManager), sem nenhuma
 * lógica nova. A estrutura de navegação já reserva espaço para os próximos
 * tipos de relatório (comparativo, histórico, PDF) para evitar retrabalho.
 */
class ReportController
{
    private AIManager $manager;
    private SurveyService $surveys;
    private AuthService $auth;

    public function __construct(?AIManager $manager = null, ?SurveyService $surveys = null, ?AuthService $auth = null)
    {
        $this->manager = $manager ?? new AIManager();
        $this->surveys = $surveys ?? new SurveyService();
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

        $surveys = $this->surveys->all();

        ob_start();
        require __DIR__ . '/../Views/reports/index.php';
        return ob_get_clean();
    }

    public function generate(): string
    {
        $this->protect();

        $surveyId = (int) ($_POST['survey_id'] ?? 0);

        $survey = $this->surveys->find($surveyId);
        $result = $this->manager->generateReport($surveyId);

        ob_start();
        require __DIR__ . '/../Views/reports/result.php';
        return ob_get_clean();
    }
}
