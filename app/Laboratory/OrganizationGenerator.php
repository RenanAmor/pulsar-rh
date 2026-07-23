<?php

namespace App\Laboratory;

use App\Services\BranchService;
use App\Services\CompanyService;
use App\Services\DepartmentService;
use App\Services\PositionService;
use App\Services\TeamService;

class OrganizationGenerator
{
    private const DEPARTMENTS = ['Recursos Humanos', 'Comercial', 'Tecnologia'];
    private const POSITIONS_PER_DEPARTMENT = ['Analista', 'Coordenador'];

    private CompanyService $companies;
    private BranchService $branches;
    private DepartmentService $departments;
    private TeamService $teams;
    private PositionService $positions;
    private NameGenerator $names;

    public function __construct()
    {
        $this->companies = new CompanyService();
        $this->branches = new BranchService();
        $this->departments = new DepartmentService();
        $this->teams = new TeamService();
        $this->positions = new PositionService();
        $this->names = new NameGenerator();
    }

    private function findCompanyIdByTradeName(string $tradeName): ?int
    {
        foreach ($this->companies->all() as $company) {
            if ($company['trade_name'] === $tradeName) {
                return (int) $company['id'];
            }
        }

        return null;
    }

    private function findBranchIdByDocument(string $document): ?int
    {
        foreach ($this->branches->all() as $branch) {
            if ($branch['document'] === $document) {
                return (int) $branch['id'];
            }
        }

        return null;
    }

    private function findDepartmentIdByCode(string $code): ?int
    {
        foreach ($this->departments->all() as $department) {
            if ($department['code'] === $code) {
                return (int) $department['id'];
            }
        }

        return null;
    }

    private function findTeamIdByCode(string $code): ?int
    {
        foreach ($this->teams->all() as $team) {
            if ($team['code'] === $code) {
                return (int) $team['id'];
            }
        }

        return null;
    }

    private function findPositionIdByCode(string $code): ?int
    {
        foreach ($this->positions->all() as $position) {
            if ($position['code'] === $code) {
                return (int) $position['id'];
            }
        }

        return null;
    }

    public function generate(string $scenarioLabel, string $tag = '[LAB]'): array
    {
        $uniqueTag = $this->names->uniqueTag();
        $tradeName = "{$tag} {$scenarioLabel} {$uniqueTag}";

        $this->companies->create([
            'corporate_name' => "{$tradeName} Ltda",
            'trade_name'     => $tradeName,
            'document'       => "LAB{$uniqueTag}00000191",
            'email'          => "contato@lab-{$uniqueTag}.local",
            'phone'          => '(00) 0000-0000',
            'website'        => null,
            'sector'         => 'Laboratório Organizacional',
            'size'           => 'Média',
            'employees'      => 0,
            'city'           => 'São Paulo',
            'state'          => 'SP',
            'active'         => 1,
        ]);

        $companyId = $this->findCompanyIdByTradeName($tradeName);

        $branchDocument = "LAB-BR-{$uniqueTag}";

        $this->branches->create([
            'company_id' => $companyId,
            'name'       => 'Matriz',
            'document'   => $branchDocument,
            'email'      => "matriz@lab-{$uniqueTag}.local",
            'phone'      => '(00) 0000-0000',
            'city'       => 'São Paulo',
            'state'      => 'SP',
            'active'     => 1,
        ]);

        $branchId = $this->findBranchIdByDocument($branchDocument);

        $departmentIds = [];
        $teamIds = [];
        $positionIds = [];

        foreach (self::DEPARTMENTS as $index => $departmentName) {
            $departmentCode = "LAB-DEP-{$uniqueTag}-{$index}";

            $this->departments->create([
                'company_id'  => $companyId,
                'branch_id'   => $branchId,
                'manager_id'  => null,
                'name'        => $departmentName,
                'code'        => $departmentCode,
                'description' => "Setor gerado pelo Laboratório Organizacional ({$departmentName})",
                'email'       => null,
                'phone'       => null,
                'active'      => 1,
            ]);

            $departmentId = $this->findDepartmentIdByCode($departmentCode);
            $departmentIds[] = $departmentId;

            $teamCode = "LAB-TEAM-{$uniqueTag}-{$index}";

            $this->teams->create([
                'company_id'    => $companyId,
                'branch_id'     => $branchId,
                'department_id' => $departmentId,
                'leader_id'     => null,
                'name'          => "Equipe {$departmentName}",
                'code'          => $teamCode,
                'description'   => null,
                'active'        => 1,
            ]);

            $teamIds[] = $this->findTeamIdByCode($teamCode);

            foreach (self::POSITIONS_PER_DEPARTMENT as $positionIndex => $positionName) {
                $positionCode = "LAB-POS-{$uniqueTag}-{$index}-{$positionIndex}";

                $this->positions->create([
                    'company_id'    => $companyId,
                    'branch_id'     => $branchId,
                    'department_id' => $departmentId,
                    'name'          => "{$positionName} de {$departmentName}",
                    'code'          => $positionCode,
                    'cbo'           => null,
                    'description'   => null,
                    'salary_min'    => null,
                    'salary_max'    => null,
                    'active'        => 1,
                ]);

                $positionIds[] = $this->findPositionIdByCode($positionCode);
            }
        }

        return [
            'company_id'      => $companyId,
            'company_name'    => $tradeName,
            'branch_id'       => $branchId,
            'department_ids'  => $departmentIds,
            'team_ids'        => $teamIds,
            'position_ids'    => $positionIds,
        ];
    }
}
