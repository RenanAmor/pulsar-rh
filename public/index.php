<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\UserController;

$base = '/humania-rh/public';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (str_starts_with($path, $base)) {
    $path = substr($path, strlen($base));
}

if ($path === '') {
    $path = '/';
}

switch ($path) {

    case '/':

        $controller = new AuthController();
        echo $controller->login();
        break;

    case '/dashboard':

        $controller = new DashboardController();
        echo $controller->index();
        break;

    case '/users':

        $controller = new UserController();
        echo $controller->index();
        break;

    case '/logout':

        $controller = new AuthController();
        $controller->logout();
        break;

    default:

        http_response_code(404);
        echo "<h1>404</h1>";
        echo "<p>Página não encontrada.</p>";
        break;
}