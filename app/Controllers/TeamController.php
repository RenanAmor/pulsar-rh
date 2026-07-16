<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\BranchService;
use App\Services\CompanyService;
use App\Services\DepartmentService;
use App\Services\EmployeeService;
use App\Services\TeamService;

class TeamController
{
    private TeamService $service;
    private CompanyService $companies;
    private BranchService $branches;
    private DepartmentService $departments;
    private EmployeeService $employees;
    private AuthService $auth;

    public function __construct()
    {
        $this->service = new TeamService();
        $this->companies = new CompanyService();
        $this->branches = new BranchService();
        $this->departments = new DepartmentService();
        $this->employees = new EmployeeService();
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

        $teams = $this->service->all();

        ob_start();
        require __DIR__ . '/../Views/teams/index.php';
        return ob_get_clean();
    }

    public function create(): string
    {
        $this->protect();

        $companies = $this->companies->listForSelect();
        $branches = $this->branches->all();
        $departments = $this->departments->all();
        $leaders = $this->employees->listForSelect();

        ob_start();
        require __DIR__ . '/../Views/teams/create.php';
        return ob_get_clean();
    }

    public function store(): void
    {
        $this->protect();

        $companyId = (int) $_POST['company_id'];
        $branchId = (int) $_POST['branch_id'];
        $departmentId = (int) $_POST['department_id'];
        $leaderId = ($_POST['leader_id'] ?? '') !== '' ? (int) $_POST['leader_id'] : null;

        if (!$this->service->isValidHierarchy($companyId, $branchId, $departmentId)) {
            header('Location: ' . BASE_URL . '/teams/create');
            exit;
        }

        if (
            $leaderId !== null
            && !$this->service->isValidLeader($leaderId, $companyId, $branchId, $departmentId)
        ) {
            header('Location: ' . BASE_URL . '/teams/create');
            exit;
        }

        $this->service->create([
            'company_id' => $companyId,
            'branch_id' => $branchId,
            'department_id' => $departmentId,
            'leader_id' => $leaderId,
            'name' => trim($_POST['name']),
            'code' => trim($_POST['code']) ?: null,
            'description' => trim($_POST['description']) ?: null,
            'active' => (int) $_POST['active']
        ]);

        header('Location: ' . BASE_URL . '/teams');
        exit;
    }

    public function edit(): string
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);

        $team = $this->service->find($id);

        if (!$team) {
            header('Location: ' . BASE_URL . '/teams');
            exit;
        }

        $companies = $this->companies->listForSelect();
        $branches = $this->branches->all();
        $departments = $this->departments->all();
        $leaders = $this->employees->listForSelect();

        ob_start();
        require __DIR__ . '/../Views/teams/edit.php';
        return ob_get_clean();
    }

    public function update(): void
    {
        $this->protect();

        $id = (int) $_POST['id'];
        $companyId = (int) $_POST['company_id'];
        $branchId = (int) $_POST['branch_id'];
        $departmentId = (int) $_POST['department_id'];
        $leaderId = ($_POST['leader_id'] ?? '') !== '' ? (int) $_POST['leader_id'] : null;

        if (!$this->service->isValidHierarchy($companyId, $branchId, $departmentId)) {
            header('Location: ' . BASE_URL . '/teams/edit?id=' . $id);
            exit;
        }

        if (
            $leaderId !== null
            && !$this->service->isValidLeader($leaderId, $companyId, $branchId, $departmentId)
        ) {
            header('Location: ' . BASE_URL . '/teams/edit?id=' . $id);
            exit;
        }

        $this->service->update($id, [
            'company_id' => $companyId,
            'branch_id' => $branchId,
            'department_id' => $departmentId,
            'leader_id' => $leaderId,
            'name' => trim($_POST['name']),
            'code' => trim($_POST['code']) ?: null,
            'description' => trim($_POST['description']) ?: null,
            'active' => (int) $_POST['active']
        ]);

        header('Location: ' . BASE_URL . '/teams');
        exit;
    }

    public function delete(): void
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);

        $this->service->delete($id);

        header('Location: ' . BASE_URL . '/teams');
        exit;
    }
}
