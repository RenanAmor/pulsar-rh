<?php

namespace App\Controllers;

class DashboardController
{
    public function index(): string
    {
        ob_start();
        require __DIR__ . '/../Views/dashboard.php';
        return ob_get_clean();
    }
}