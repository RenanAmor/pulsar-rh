<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\UserController;

$base = BASE_URL;

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($base !== '' && str_starts_with($path, $base)) {
    $path = substr($path, strlen($base));
}

$path = $path ?: '/';

switch ($path) {

    case '/':

        echo (new AuthController())->login();
        break;

    case '/dashboard':

        echo (new DashboardController())->index();
        break;

    case '/users':

        echo (new UserController())->index();
        break;

    case '/users/create':

        echo (new UserController())->create();
        break;

    case '/users/store':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new UserController())->store();
        }
        break;

    case '/users/edit':

        echo (new UserController())->edit();
        break;

    case '/users/update':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new UserController())->update();
        }
        break;

    case '/users/delete':

        (new UserController())->delete();
        break;

    case '/logout':

        (new AuthController())->logout();
        break;

    default:

        http_response_code(404);

        echo "<h1>404</h1>";
        echo "<p>Página não encontrada.</p>";
}