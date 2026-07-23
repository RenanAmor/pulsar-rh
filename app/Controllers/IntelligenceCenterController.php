<?php

namespace App\Controllers;

use App\OIE\OIE;
use App\Services\AuthService;
use App\Services\BranchService;
use App\Services\DepartmentService;
use App\Services\ExecutiveContextResolver;
use App\Services\IntelligenceCenterPresenter;
use App\Services\TeamService;

/**
 * Centro de Inteligência Organizacional: traduz o que o OIE já produz em
 * uma experiência executiva, organizada em cinco áreas de navegação
 * (situação atual, mudanças, alertas, recomendações e evolução). Não
 * calcula indicadores nem interpreta padrões — apenas consome o OIE e o
 * IntelligenceCenterPresenter para apresentar o resultado.
 */
class IntelligenceCenterController
{
    private OIE $oie;
    private ExecutiveContextResolver $resolver;
    private IntelligenceCenterPresenter $presenter;
    private BranchService $branches;
    private DepartmentService $departments;
    private TeamService $teams;
    private AuthService $auth;

    public function __construct(
        ?OIE $oie = null,
        ?ExecutiveContextResolver $resolver = null,
        ?IntelligenceCenterPresenter $presenter = null,
        ?BranchService $branches = null,
        ?DepartmentService $departments = null,
        ?TeamService $teams = null,
        ?AuthService $auth = null
    ) {
        $this->oie = $oie ?? new OIE();
        $this->resolver = $resolver ?? new ExecutiveContextResolver();
        $this->presenter = $presenter ?? new IntelligenceCenterPresenter();
        $this->branches = $branches ?? new BranchService();
        $this->departments = $departments ?? new DepartmentService();
        $this->teams = $teams ?? new TeamService();
        $this->auth = $auth ?? new AuthService();
    }

    private function protect(): void
    {
        if (!$this->auth->check()) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    /**
     * @return array{0: array|null, 1: array}
     */
    private function resolve(): array
    {
        $companyId = isset($_GET['company_id']) ? (int) $_GET['company_id'] : null;
        $surveyId = $this->resolver->resolveSurveyId($companyId);

        if ($surveyId === null) {
            return [null, ['error' => 'Ainda não há pesquisas suficientes para gerar a inteligência organizacional.']];
        }

        $context = $this->oie->analyze($surveyId);

        return [$context, $this->presenter->present($context)];
    }

    private function render(string $view, string $activeTab, array $extra = []): string
    {
        $this->protect();

        [$context, $center] = $this->resolve();

        ob_start();
        require __DIR__ . '/../Views/intelligence/' . $view . '.php';
        return ob_get_clean();
    }

    public function situation(): string
    {
        return $this->render('situation', 'situation');
    }

    public function changes(): string
    {
        return $this->render('changes', 'changes');
    }

    public function alerts(): string
    {
        return $this->render('alerts', 'alerts');
    }

    public function recommendations(): string
    {
        return $this->render('recommendations', 'recommendations');
    }

    public function evolution(): string
    {
        $this->protect();

        [$context, $center] = $this->resolve();

        $scopeOptions = ['branch' => [], 'department' => [], 'team' => []];
        $comparison = null;
        $scopeType = null;
        $entityId = null;

        $entity = $_GET['entity'] ?? '';
        if (is_string($entity) && str_contains($entity, ':')) {
            [$scopeType, $rawId] = explode(':', $entity, 2);
            $entityId = ctype_digit($rawId) ? (int) $rawId : null;
        }

        if ($context !== null && !isset($context['error'])) {
            $companyId = (int) $context['organization']['company_id'];

            $scopeOptions['branch'] = array_values(array_filter(
                $this->branches->all(),
                fn (array $branch): bool => (int) $branch['company_id'] === $companyId
            ));

            $scopeOptions['department'] = array_values(array_filter(
                $this->departments->all(),
                fn (array $department): bool => (int) $department['company_id'] === $companyId
            ));

            $scopeOptions['team'] = array_values(array_filter(
                $this->teams->all(),
                fn (array $team): bool => (int) $team['company_id'] === $companyId
            ));

            if ($scopeType !== null && $entityId !== null) {
                $comparison = $this->buildComparison((int) $context['organization']['survey_id'], $scopeType, $entityId);
            }
        }

        ob_start();
        require __DIR__ . '/../Views/intelligence/evolution.php';
        return ob_get_clean();
    }

    private function buildComparison(int $surveyId, string $scopeType, int $entityId): ?array
    {
        $behavior = match ($scopeType) {
            'branch'     => $this->oie->behaviorByBranch($surveyId, [$entityId]),
            'department' => $this->oie->behaviorByDepartment($surveyId, [$entityId]),
            'team'       => $this->oie->behaviorByTeam($surveyId, [$entityId]),
            default      => [],
        };

        return $behavior[0] ?? null;
    }
}
