<?php

namespace App\OIE;

/**
 * Organizational Intelligence Engine — ponto de entrada público do OIE.
 *
 * O OIE não calcula indicadores (isso é responsabilidade do Motor de
 * Indicadores) e não realiza nenhuma chamada a modelos de IA. Ele apenas
 * interpreta os indicadores já calculados, constrói o contexto
 * organizacional e produz padrões, riscos e recomendações através de
 * regras de negócio.
 *
 * Esta classe é o único ponto que outras camadas (controllers e, no
 * futuro, o AIProvider) devem usar para consumir o OIE.
 */
class OIE
{
    private OIEEngine $engine;

    public function __construct(?OIEEngine $engine = null)
    {
        $this->engine = $engine ?? new OIEEngine();
    }

    public function analyze(int $surveyId, array $scope = []): array
    {
        return $this->engine->analyze($surveyId, $scope);
    }

    public function compareSurveys(int $surveyIdA, int $surveyIdB, array $scope = []): array
    {
        return $this->engine->compareSurveys($surveyIdA, $surveyIdB, $scope);
    }

    public function behaviorByDepartment(int $surveyId, array $departmentIds): array
    {
        return $this->engine->behaviorByDepartment($surveyId, $departmentIds);
    }

    public function behaviorByTeam(int $surveyId, array $teamIds): array
    {
        return $this->engine->behaviorByTeam($surveyId, $teamIds);
    }

    public function behaviorByManager(int $surveyId, array $managerIds): array
    {
        return $this->engine->behaviorByManager($surveyId, $managerIds);
    }

    public function behaviorByBranch(int $surveyId, array $branchIds): array
    {
        return $this->engine->behaviorByBranch($surveyId, $branchIds);
    }
}
