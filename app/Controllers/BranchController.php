<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\BranchService;
use App\Services\CompanyService;

class BranchController
{
    private BranchService $service;
    private CompanyService $companies;
    private AuthService $auth;

    public function __construct()
    {
        $this->service = new BranchService();
        $this->companies = new CompanyService();
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

        $branches = $this->service->all();

        ob_start();
        require __DIR__ . '/../Views/branches/index.php';
        return ob_get_clean();
    }

    public function create(): string
    {
        $this->protect();

        $companies = $this->companies->listForSelect();

        ob_start();
        require __DIR__ . '/../Views/branches/create.php';
        return ob_get_clean();
    }

    public function store(): void
    {
        $this->protect();

        $this->service->create([
            'company_id' => (int) $_POST['company_id'],
            'name'       => trim($_POST['name']),
            'document'   => trim($_POST['document']),
            'email'      => trim($_POST['email']) ?: null,
            'phone'      => trim($_POST['phone']) ?: null,
            'city'       => trim($_POST['city']) ?: null,
            'state'      => trim($_POST['state']) ?: null,
            'active'     => (int) $_POST['active']
        ]);

        header('Location: ' . BASE_URL . '/branches');
        exit;
    }

    public function edit(): string
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);

        $branch = $this->service->find($id);

        if (!$branch) {
            header('Location: ' . BASE_URL . '/branches');
            exit;
        }

        $companies = $this->companies->listForSelect();

        ob_start();
        require __DIR__ . '/../Views/branches/edit.php';
        return ob_get_clean();
    }

    public function update(): void
    {
        $this->protect();

        $id = (int) $_POST['id'];

        $this->service->update($id, [
            'company_id' => (int) $_POST['company_id'],
            'name'       => trim($_POST['name']),
            'document'   => trim($_POST['document']),
            'email'      => trim($_POST['email']) ?: null,
            'phone'      => trim($_POST['phone']) ?: null,
            'city'       => trim($_POST['city']) ?: null,
            'state'      => trim($_POST['state']) ?: null,
            'active'     => (int) $_POST['active']
        ]);

        header('Location: ' . BASE_URL . '/branches');
        exit;
    }

    public function delete(): void
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);

        $this->service->delete($id);

        header('Location: ' . BASE_URL . '/branches');
        exit;
    }
}
