<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\UserService;

class UserController
{
    private UserService $service;
    private AuthService $auth;

    public function __construct()
    {
        $this->service = new UserService();
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

        $users = $this->service->all();

        ob_start();
        require __DIR__ . '/../Views/users/index.php';
        return ob_get_clean();
    }

    public function create(): string
    {
        $this->protect();

        ob_start();
        require __DIR__ . '/../Views/users/create.php';
        return ob_get_clean();
    }

    public function store(): void
    {
        $this->protect();

        $this->service->create([
            'name'     => trim($_POST['name']),
            'email'    => trim($_POST['email']),
            'password' => $_POST['password'],
            'role'     => $_POST['role'],
            'active'   => (int) $_POST['active']
        ]);

        header('Location: ' . BASE_URL . '/users');
        exit;
    }

    public function edit(): string
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);

        $user = $this->service->find($id);

        if (!$user) {
            header('Location: ' . BASE_URL . '/users');
            exit;
        }

        ob_start();
        require __DIR__ . '/../Views/users/edit.php';
        return ob_get_clean();
    }

    public function update(): void
    {
        $this->protect();

        $id = (int) $_POST['id'];

        $this->service->update($id, [
            'name'   => trim($_POST['name']),
            'email'  => trim($_POST['email']),
            'role'   => $_POST['role'],
            'active' => (int) $_POST['active']
        ]);

        header('Location: ' . BASE_URL . '/users');
        exit;
    }

    public function delete(): void
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);

        $this->service->delete($id);

        header('Location: ' . BASE_URL . '/users');
        exit;
    }
}