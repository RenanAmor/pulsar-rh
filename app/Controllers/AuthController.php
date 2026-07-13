<?php

namespace App\Controllers;

use App\Services\AuthService;

class AuthController
{
    private AuthService $auth;

    public function __construct()
    {
        $this->auth = new AuthService();
    }

    public function login(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($this->auth->attempt($email, $password)) {

                header('Location: /dashboard');
                exit;

            }

            $error = 'E-mail ou senha inválidos.';
        }

        ob_start();

        require __DIR__ . '/../Views/login.php';

        return ob_get_clean();
    }

    public function logout(): void
    {
        $this->auth->logout();

        header('Location: /');

        exit;
    }
}