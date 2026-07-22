<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\CompanyService;
use App\Services\QuestionService;

class QuestionController
{
    private QuestionService $service;
    private CompanyService $companies;
    private AuthService $auth;

    public function __construct()
    {
        $this->service = new QuestionService();
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

        $questions = $this->service->all();

        ob_start();
        require __DIR__ . '/../Views/questions/index.php';
        return ob_get_clean();
    }

    public function create(): string
    {
        $this->protect();

        $companies = $this->companies->listForSelect();

        ob_start();
        require __DIR__ . '/../Views/questions/create.php';
        return ob_get_clean();
    }

    public function store(): void
    {
        $this->protect();

        $this->service->create([
            'company_id'  => ($_POST['company_id'] ?? '') !== '' ? (int) $_POST['company_id'] : null,
            'category'    => trim($_POST['category']),
            'dimension'   => trim($_POST['dimension']),
            'question'    => trim($_POST['question']),
            'answer_type' => trim($_POST['answer_type']),
            'scale_min'   => (int) $_POST['scale_min'],
            'scale_max'   => (int) $_POST['scale_max'],
            'weight'      => (float) $_POST['weight'],
            'active'      => (int) $_POST['active']
        ]);

        header('Location: ' . BASE_URL . '/questions');
        exit;
    }

    public function edit(): string
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);

        $question = $this->service->find($id);

        if (!$question) {
            header('Location: ' . BASE_URL . '/questions');
            exit;
        }

        $companies = $this->companies->listForSelect();
        $surveysUsingQuestion = $this->service->countSurveysUsingQuestion($id);

        ob_start();
        require __DIR__ . '/../Views/questions/edit.php';
        return ob_get_clean();
    }

    public function update(): void
    {
        $this->protect();

        $id = (int) $_POST['id'];

        $this->service->update($id, [
            'company_id'  => ($_POST['company_id'] ?? '') !== '' ? (int) $_POST['company_id'] : null,
            'category'    => trim($_POST['category']),
            'dimension'   => trim($_POST['dimension']),
            'question'    => trim($_POST['question']),
            'answer_type' => trim($_POST['answer_type']),
            'scale_min'   => (int) $_POST['scale_min'],
            'scale_max'   => (int) $_POST['scale_max'],
            'weight'      => (float) $_POST['weight'],
            'active'      => (int) $_POST['active']
        ]);

        header('Location: ' . BASE_URL . '/questions');
        exit;
    }

    public function delete(): void
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);

        $this->service->delete($id);

        header('Location: ' . BASE_URL . '/questions');
        exit;
    }
}
