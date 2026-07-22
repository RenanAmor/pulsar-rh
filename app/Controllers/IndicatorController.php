<?php

namespace App\Controllers;

use App\Indicators\IndicatorEngine;
use App\Services\AuthService;
use App\Services\SurveyService;

class IndicatorController
{
    private IndicatorEngine $engine;
    private SurveyService $surveys;
    private AuthService $auth;

    public function __construct()
    {
        $this->engine = new IndicatorEngine();
        $this->surveys = new SurveyService();
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

        $surveys = $this->surveys->all();

        ob_start();
        require __DIR__ . '/../Views/indicators/index.php';
        return ob_get_clean();
    }

    public function show(): string
    {
        $this->protect();

        $surveyId = (int) ($_GET['survey_id'] ?? 0);

        $survey = $this->surveys->find($surveyId);

        if (!$survey) {
            header('Location: ' . BASE_URL . '/indicators');
            exit;
        }

        $result = $this->engine->run($surveyId);
        $history = $this->engine->history((int) $survey['company_id'], $surveyId);

        ob_start();
        require __DIR__ . '/../Views/indicators/show.php';
        return ob_get_clean();
    }
}
