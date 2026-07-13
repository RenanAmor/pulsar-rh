<?php

namespace App\Controllers;

use App\Services\AuthService;

class DashboardController
{
    private AuthService $auth;

    public function __construct()
    {
        $this->auth = new AuthService();
    }

    public function index(): string
    {
        if (!$this->auth->check()) {

            header('Location: ' . BASE_URL . '/');

            exit;
        }

        $user = $this->auth->user();

        ob_start();

        require __DIR__ . '/../Views/dashboard.php';

        return ob_get_clean();
    }
}