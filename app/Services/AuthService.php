<?php

namespace App\Services;

use App\Models\User;

class AuthService
{
    public static function login(string $email, string $password): bool
    {
        $user = User::findByEmail($email);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        $_SESSION['user'] = $user;

        return true;
    }
}