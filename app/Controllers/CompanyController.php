<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\CompanyService;

class CompanyController
{
    private CompanyService $service;
    private AuthService $auth;

    public function __construct()
    {
        $this->service = new CompanyService();
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

        $companies = $this->service->all();

        ob_start();

        require __DIR__ . '/../Views/companies/index.php';

        return ob_get_clean();
    }

    public function create(): string
    {
        $this->protect();

        ob_start();

        require __DIR__ . '/../Views/companies/create.php';

        return ob_get_clean();
    }

    public function store(): void
    {
        $this->protect();

        $this->service->create([
            'corporate_name' => trim($_POST['corporate_name']),
            'trade_name'     => trim($_POST['trade_name']),
            'document'       => trim($_POST['document']),
            'email'          => trim($_POST['email']),
            'phone'          => trim($_POST['phone']),
            'website'        => trim($_POST['website']),
            'sector'         => trim($_POST['sector']),
            'size'           => $_POST['size'],
            'employees'      => (int) $_POST['employees'],
            'city'           => trim($_POST['city']),
            'state'          => trim($_POST['state']),
            'active'         => (int) $_POST['active']
        ]);

        header('Location: ' . BASE_URL . '/companies');
        exit;
    }

    public function edit(): string
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);

        $company = $this->service->find($id);

        if (!$company) {
            header('Location: ' . BASE_URL . '/companies');
            exit;
        }

        ob_start();

        require __DIR__ . '/../Views/companies/edit.php';

        return ob_get_clean();
    }

    public function update(): void
    {
        $this->protect();

        $id = (int) $_POST['id'];

        $this->service->update($id, [
            'corporate_name' => trim($_POST['corporate_name']),
            'trade_name'     => trim($_POST['trade_name']),
            'document'       => trim($_POST['document']),
            'email'          => trim($_POST['email']),
            'phone'          => trim($_POST['phone']),
            'website'        => trim($_POST['website']),
            'sector'         => trim($_POST['sector']),
            'size'           => $_POST['size'],
            'employees'      => (int) $_POST['employees'],
            'city'           => trim($_POST['city']),
            'state'          => trim($_POST['state']),
            'active'         => (int) $_POST['active']
        ]);

        header('Location: ' . BASE_URL . '/companies');
        exit;
    }

    public function delete(): void
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);

        $this->service->delete($id);

        header('Location: ' . BASE_URL . '/companies');
        exit;
    }
}