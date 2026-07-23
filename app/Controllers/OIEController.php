<?php

namespace App\Controllers;

use App\OIE\OIE;
use App\Services\AuthService;
use App\Services\SurveyService;

class OIEController
{
    private OIE $oie;
    private SurveyService $surveys;
    private AuthService $auth;

    public function __construct()
    {
        $this->oie = new OIE();
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
        require __DIR__ . '/../Views/oie/index.php';
        return ob_get_clean();
    }

    public function show(): string
    {
        $this->protect();

        $surveyId = (int) ($_GET['survey_id'] ?? 0);

        $survey = $this->surveys->find($surveyId);

        if (!$survey) {
            header('Location: ' . BASE_URL . '/oie');
            exit;
        }

        $context = $this->oie->analyze($surveyId);

        ob_start();
        require __DIR__ . '/../Views/oie/show.php';
        return ob_get_clean();
    }
}
