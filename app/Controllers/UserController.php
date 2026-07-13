<?php

namespace App\Controllers;

use App\Services\UserService;

class UserController
{
    private UserService $service;

    public function __construct()
    {
        $this->service = new UserService();
    }

    public function index(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }

        $users = $this->service->all();

        ob_start();

        require __DIR__ . '/../Views/users/index.php';

        return ob_get_clean();
    }
}