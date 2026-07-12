<?php

namespace App\Controllers;

class AuthController
{
    public function login(): string
    {
        ob_start();
        require __DIR__ . '/../Views/login.php';
        return ob_get_clean();
    }
}