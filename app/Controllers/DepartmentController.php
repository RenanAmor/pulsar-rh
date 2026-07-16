<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\BranchService;
use App\Services\CompanyService;
use App\Services\DepartmentService;

class DepartmentController
{
    private DepartmentService $service;
    private CompanyService $companies;
    private BranchService $branches;
    private AuthService $auth;

    public function __construct()
    {
        $this->service = new DepartmentService();
        $this->companies = new CompanyService();
        $this->branches = new BranchService();
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

        $departments = $this->service->all();

        ob_start();
        require __DIR__ . '/../Views/departments/index.php';
        return ob_get_clean();
    }

    public function create(): string
    {
        $this->protect();

        $companies = $this->companies->listForSelect();
        $branches = $this->branches->all();

        ob_start();
        require __DIR__ . '/../Views/departments/create.php';
        return ob_get_clean();
    }

    public function store(): void
    {
        $this->protect();

        $this->service->create([
            'company_id'  => (int) $_POST['company_id'],
            'branch_id'   => (int) $_POST['branch_id'],
            'manager_id'  => ($_POST['manager_id'] ?? '') !== '' ? (int) $_POST['manager_id'] : null,
            'name'        => trim($_POST['name']),
            'code'        => trim($_POST['code']) ?: null,
            'description' => trim($_POST['description']) ?: null,
            'email'       => trim($_POST['email']) ?: null,
            'phone'       => trim($_POST['phone']) ?: null,
            'active'      => (int) $_POST['active']
        ]);

        header('Location: ' . BASE_URL . '/departments');
        exit;
    }

    public function edit(): string
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);

        $department = $this->service->find($id);

        if (!$department) {
            header('Location: ' . BASE_URL . '/departments');
            exit;
        }

        $companies = $this->companies->listForSelect();
        $branches = $this->branches->all();

        ob_start();
        require __DIR__ . '/../Views/departments/edit.php';
        return ob_get_clean();
    }

    public function update(): void
    {
        $this->protect();

        $id = (int) $_POST['id'];

        $this->service->update($id, [
            'company_id'  => (int) $_POST['company_id'],
            'branch_id'   => (int) $_POST['branch_id'],
            'manager_id'  => ($_POST['manager_id'] ?? '') !== '' ? (int) $_POST['manager_id'] : null,
            'name'        => trim($_POST['name']),
            'code'        => trim($_POST['code']) ?: null,
            'description' => trim($_POST['description']) ?: null,
            'email'       => trim($_POST['email']) ?: null,
            'phone'       => trim($_POST['phone']) ?: null,
            'active'      => (int) $_POST['active']
        ]);

        header('Location: ' . BASE_URL . '/departments');
        exit;
    }

    public function delete(): void
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);

        $this->service->delete($id);

        header('Location: ' . BASE_URL . '/departments');
        exit;
    }
}
