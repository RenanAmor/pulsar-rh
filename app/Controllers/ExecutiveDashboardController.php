<?php

namespace App\Controllers;

use App\OIE\OIE;
use App\Services\AuthService;
use App\Services\ExecutiveContextResolver;
use App\Services\ExecutiveDashboardPresenter;

class ExecutiveDashboardController
{
    private OIE $oie;
    private ExecutiveContextResolver $resolver;
    private ExecutiveDashboardPresenter $presenter;
    private AuthService $auth;

    public function __construct(
        ?OIE $oie = null,
        ?ExecutiveContextResolver $resolver = null,
        ?ExecutiveDashboardPresenter $presenter = null,
        ?AuthService $auth = null
    ) {
        $this->oie = $oie ?? new OIE();
        $this->resolver = $resolver ?? new ExecutiveContextResolver();
        $this->presenter = $presenter ?? new ExecutiveDashboardPresenter();
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

        $user = $this->auth->user();

        $companyId = isset($_GET['company_id']) ? (int) $_GET['company_id'] : null;
        $surveyId = $this->resolver->resolveSurveyId($companyId);

        $dashboard = $surveyId !== null
            ? $this->presenter->present($this->oie->analyze($surveyId))
            : ['error' => 'Ainda não há pesquisas suficientes para gerar a visão executiva.'];

        ob_start();
        require __DIR__ . '/../Views/executive/dashboard.php';
        return ob_get_clean();
    }
}
