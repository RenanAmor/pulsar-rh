<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\CandidateService;
use App\Services\CompanyService;
use App\Services\JobService;

class CandidateController
{
    private AuthService $auth;
    private CandidateService $service;

    public function __construct()
    {
        $this->auth = new AuthService();
        $this->service = new CandidateService();

        if (!$this->auth->check()) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    public function index()
    {
        $user = $this->auth->user();

        $candidates = $this->service->all();

        require __DIR__ . '/../Views/candidates/index.php';
    }

    public function create()
    {
        $user = $this->auth->user();

        $companies = (new CompanyService())->all();
        $jobs = (new JobService())->all();

        require __DIR__ . '/../Views/candidates/create.php';
    }

    public function store()
    {
        $this->service->create($_POST);

        header('Location: ' . BASE_URL . '/candidates');
        exit;
    }
}