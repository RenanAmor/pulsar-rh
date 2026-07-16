<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\BranchService;
use App\Services\CompanyService;
use App\Services\DepartmentService;
use App\Services\PositionService;

class PositionController
{
    private PositionService $service;
    private CompanyService $companies;
    private BranchService $branches;
    private DepartmentService $departments;
    private AuthService $auth;

    public function __construct()
    {
        $this->service = new PositionService();
        $this->companies = new CompanyService();
        $this->branches = new BranchService();
        $this->departments = new DepartmentService();
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

        $positions = $this->service->all();

        ob_start();
        require __DIR__ . '/../Views/positions/index.php';
        return ob_get_clean();
    }

    public function create(): string
    {
        $this->protect();

        $companies = $this->companies->listForSelect();
        $branches = $this->branches->all();
        $departments = $this->departments->all();

        ob_start();
        require __DIR__ . '/../Views/positions/create.php';
        return ob_get_clean();
    }

    public function store(): void
    {
        $this->protect();

        $this->service->create([
            'company_id'    => (int) $_POST['company_id'],
            'branch_id'     => (int) $_POST['branch_id'],
            'department_id' => (int) $_POST['department_id'],
            'name'          => trim($_POST['name']),
            'code'          => trim($_POST['code']) ?: null,
            'cbo'           => trim($_POST['cbo']) ?: null,
            'description'   => trim($_POST['description']) ?: null,
            'salary_min'    => ($_POST['salary_min'] ?? '') !== '' ? (float) $_POST['salary_min'] : null,
            'salary_max'    => ($_POST['salary_max'] ?? '') !== '' ? (float) $_POST['salary_max'] : null,
            'active'        => (int) $_POST['active']
        ]);

        header('Location: ' . BASE_URL . '/positions');
        exit;
    }

    public function edit(): string
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);

        $position = $this->service->find($id);

        if (!$position) {
            header('Location: ' . BASE_URL . '/positions');
            exit;
        }

        $companies = $this->companies->listForSelect();
        $branches = $this->branches->all();
        $departments = $this->departments->all();

        ob_start();
        require __DIR__ . '/../Views/positions/edit.php';
        return ob_get_clean();
    }

    public function update(): void
    {
        $this->protect();

        $id = (int) $_POST['id'];

        $this->service->update($id, [
            'company_id'    => (int) $_POST['company_id'],
            'branch_id'     => (int) $_POST['branch_id'],
            'department_id' => (int) $_POST['department_id'],
            'name'          => trim($_POST['name']),
            'code'          => trim($_POST['code']) ?: null,
            'cbo'           => trim($_POST['cbo']) ?: null,
            'description'   => trim($_POST['description']) ?: null,
            'salary_min'    => ($_POST['salary_min'] ?? '') !== '' ? (float) $_POST['salary_min'] : null,
            'salary_max'    => ($_POST['salary_max'] ?? '') !== '' ? (float) $_POST['salary_max'] : null,
            'active'        => (int) $_POST['active']
        ]);

        header('Location: ' . BASE_URL . '/positions');
        exit;
    }

    public function delete(): void
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);

        $this->service->delete($id);

        header('Location: ' . BASE_URL . '/positions');
        exit;
    }
}
