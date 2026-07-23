<?php

namespace App\Controllers;

use App\Services\AuthService;

class AdministrationController
{
    private AuthService $auth;

    public function __construct(?AuthService $auth = null)
    {
        $this->auth = $auth ?? new AuthService();
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

        $user = $this->auth->user();

        ob_start();
        require __DIR__ . '/../Views/administration/index.php';
        return ob_get_clean();
    }
}
