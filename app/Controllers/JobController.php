<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\CompanyService;
use App\Services\JobService;

class JobController
{
    private JobService $service;
    private CompanyService $companies;
    private AuthService $auth;

    public function __construct()
    {
        $this->service = new JobService();
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

        $jobs = $this->service->all();

        ob_start();

        require __DIR__ . '/../Views/jobs/index.php';

        return ob_get_clean();
    }

    public function create(): string
    {
        $this->protect();

        $companies = $this->companies->listForSelect();

        ob_start();

        require __DIR__ . '/../Views/jobs/create.php';

        return ob_get_clean();
    }

    public function store(): void
    {
        $this->protect();

        $this->service->create([
            'company_id'    => (int) $_POST['company_id'],
            'title'         => trim($_POST['title']),
            'department'    => trim($_POST['department']),
            'workplace'     => $_POST['workplace'],
            'contract_type' => $_POST['contract_type'],
            'vacancies'     => (int) $_POST['vacancies'],
            'salary'        => $_POST['salary'] ?: null,
            'city'          => trim($_POST['city']),
            'state'         => trim($_POST['state']),
            'description'   => trim($_POST['description']),
            'requirements'  => trim($_POST['requirements']),
            'benefits'      => trim($_POST['benefits']),
            'active'        => (int) $_POST['active']
        ]);

        header('Location: ' . BASE_URL . '/jobs');
        exit;
    }
}