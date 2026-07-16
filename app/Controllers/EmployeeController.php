<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\BranchService;
use App\Services\CompanyService;
use App\Services\DepartmentService;
use App\Services\EmployeeService;
use App\Services\PositionService;

class EmployeeController
{
    private EmployeeService $service;
    private CompanyService $companies;
    private BranchService $branches;
    private DepartmentService $departments;
    private PositionService $positions;
    private AuthService $auth;

    public function __construct()
    {
        $this->service = new EmployeeService();
        $this->companies = new CompanyService();
        $this->branches = new BranchService();
        $this->departments = new DepartmentService();
        $this->positions = new PositionService();
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

        $employees = $this->service->all();

        ob_start();
        require __DIR__ . '/../Views/employees/index.php';
        return ob_get_clean();
    }

    public function create(): string
    {
        $this->protect();

        $companies = $this->companies->listForSelect();
        $branches = $this->branches->all();
        $departments = $this->departments->all();
        $positions = $this->positions->all();
        $managers = $this->service->listForSelect();

        ob_start();
        require __DIR__ . '/../Views/employees/create.php';
        return ob_get_clean();
    }

    public function store(): void
    {
        $this->protect();

        $companyId = (int) $_POST['company_id'];
        $branchId = (int) $_POST['branch_id'];
        $departmentId = (int) $_POST['department_id'];
        $positionId = (int) $_POST['position_id'];

        if (!$this->service->isValidHierarchy($companyId, $branchId, $departmentId, $positionId)) {
            header('Location: ' . BASE_URL . '/employees/create');
            exit;
        }

        $managerId = ($_POST['manager_id'] ?? '') !== '' ? (int) $_POST['manager_id'] : null;

        if ($managerId !== null) {
            $manager = $this->service->find($managerId);

            if (
                !$manager
                || (int) $manager['company_id'] !== $companyId
                || (int) $manager['branch_id'] !== $branchId
                || (int) $manager['department_id'] !== $departmentId
            ) {
                header('Location: ' . BASE_URL . '/employees/create');
                exit;
            }
        }

        $this->service->create([
            'company_id' => $companyId,
            'branch_id' => $branchId,
            'department_id' => $departmentId,
            'team_id' => ($_POST['team_id'] ?? '') !== '' ? (int) $_POST['team_id'] : null,
            'position_id' => $positionId,
            'manager_id' => $managerId,
            'registration' => trim($_POST['registration']) ?: null,
            'name' => trim($_POST['name']),
            'cpf' => trim($_POST['cpf']) ?: null,
            'birth_date' => trim($_POST['birth_date']) ?: null,
            'gender' => trim($_POST['gender']) ?: null,
            'email' => trim($_POST['email']) ?: null,
            'phone' => trim($_POST['phone']) ?: null,
            'admission_date' => trim($_POST['admission_date']) ?: null,
            'termination_date' => trim($_POST['termination_date']) ?: null,
            'employment_type' => trim($_POST['employment_type']) ?: 'CLT',
            'status' => trim($_POST['status']) ?: 'Ativo'
        ]);

        header('Location: ' . BASE_URL . '/employees');
        exit;
    }

    public function edit(): string
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);

        $employee = $this->service->find($id);

        if (!$employee) {
            header('Location: ' . BASE_URL . '/employees');
            exit;
        }

        $companies = $this->companies->listForSelect();
        $branches = $this->branches->all();
        $departments = $this->departments->all();
        $positions = $this->positions->all();
        $managers = $this->service->listForSelect();

        ob_start();
        require __DIR__ . '/../Views/employees/edit.php';
        return ob_get_clean();
    }

    public function update(): void
    {
        $this->protect();

        $id = (int) $_POST['id'];

        $companyId = (int) $_POST['company_id'];
        $branchId = (int) $_POST['branch_id'];
        $departmentId = (int) $_POST['department_id'];
        $positionId = (int) $_POST['position_id'];

        if (!$this->service->isValidHierarchy($companyId, $branchId, $departmentId, $positionId)) {
            header('Location: ' . BASE_URL . '/employees/edit?id=' . $id);
            exit;
        }

        $managerId = ($_POST['manager_id'] ?? '') !== '' ? (int) $_POST['manager_id'] : null;

        if ($managerId !== null) {
            $manager = $this->service->find($managerId);

            if (
                $managerId === $id
                || !$manager
                || (int) $manager['company_id'] !== $companyId
                || (int) $manager['branch_id'] !== $branchId
                || (int) $manager['department_id'] !== $departmentId
            ) {
                header('Location: ' . BASE_URL . '/employees/edit?id=' . $id);
                exit;
            }
        }

        $this->service->update($id, [
            'company_id' => $companyId,
            'branch_id' => $branchId,
            'department_id' => $departmentId,
            'team_id' => ($_POST['team_id'] ?? '') !== '' ? (int) $_POST['team_id'] : null,
            'position_id' => $positionId,
            'manager_id' => $managerId,
            'registration' => trim($_POST['registration']) ?: null,
            'name' => trim($_POST['name']),
            'cpf' => trim($_POST['cpf']) ?: null,
            'birth_date' => trim($_POST['birth_date']) ?: null,
            'gender' => trim($_POST['gender']) ?: null,
            'email' => trim($_POST['email']) ?: null,
            'phone' => trim($_POST['phone']) ?: null,
            'admission_date' => trim($_POST['admission_date']) ?: null,
            'termination_date' => trim($_POST['termination_date']) ?: null,
            'employment_type' => trim($_POST['employment_type']) ?: 'CLT',
            'status' => trim($_POST['status']) ?: 'Ativo'
        ]);

        header('Location: ' . BASE_URL . '/employees');
        exit;
    }

    public function delete(): void
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);

        $this->service->delete($id);

        header('Location: ' . BASE_URL . '/employees');
        exit;
    }
}
