<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\CompanyService;
use App\Services\SurveyService;

class SurveyController
{
    private SurveyService $service;
    private CompanyService $companies;
    private AuthService $auth;

    public function __construct()
    {
        $this->service = new SurveyService();
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

        $surveys = $this->service->all();

        ob_start();
        require __DIR__ . '/../Views/surveys/index.php';
        return ob_get_clean();
    }

    public function create(): string
    {
        $this->protect();

        $companies = $this->companies->listForSelect();

        ob_start();
        require __DIR__ . '/../Views/surveys/create.php';
        return ob_get_clean();
    }

    public function store(): void
    {
        $this->protect();

        $this->service->create([
            'company_id'  => (int) $_POST['company_id'],
            'title'       => trim($_POST['title']),
            'description' => trim($_POST['description']) ?: null,
            'start_date'  => trim($_POST['start_date']) ?: null,
            'end_date'    => trim($_POST['end_date']) ?: null,
            'anonymous'   => (int) $_POST['anonymous'],
            'status'      => trim($_POST['status'])
        ]);

        header('Location: ' . BASE_URL . '/surveys');
        exit;
    }

    public function edit(): string
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);

        $survey = $this->service->find($id);

        if (!$survey) {
            header('Location: ' . BASE_URL . '/surveys');
            exit;
        }

        $companies = $this->companies->listForSelect();

        ob_start();
        require __DIR__ . '/../Views/surveys/edit.php';
        return ob_get_clean();
    }

    public function update(): void
    {
        $this->protect();

        $id = (int) $_POST['id'];

        $this->service->update($id, [
            'company_id'  => (int) $_POST['company_id'],
            'title'       => trim($_POST['title']),
            'description' => trim($_POST['description']) ?: null,
            'start_date'  => trim($_POST['start_date']) ?: null,
            'end_date'    => trim($_POST['end_date']) ?: null,
            'anonymous'   => (int) $_POST['anonymous'],
            'status'      => trim($_POST['status'])
        ]);

        header('Location: ' . BASE_URL . '/surveys');
        exit;
    }

    public function delete(): void
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);

        $this->service->delete($id);

        header('Location: ' . BASE_URL . '/surveys');
        exit;
    }
}
