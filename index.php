<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/app/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\CandidateController;
use App\Controllers\CompanyController;
use App\Controllers\DashboardController;
use App\Controllers\JobController;
use App\Controllers\UserController;

$base = BASE_URL;

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($base !== '' && str_starts_with($path, $base)) {
    $path = substr($path, strlen($base));
}

$path = $path ?: '/';

switch ($path) {

    // LOGIN
    case '/':
        echo (new AuthController())->login();
        break;

    case '/dashboard':
        echo (new DashboardController())->index();
        break;

    // USUÁRIOS
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

    // EMPRESAS
    case '/companies':
        echo (new CompanyController())->index();
        break;

    case '/companies/create':
        echo (new CompanyController())->create();
        break;

    case '/companies/store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new CompanyController())->store();
        }
        break;

    case '/companies/edit':
        echo (new CompanyController())->edit();
        break;

    case '/companies/update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new CompanyController())->update();
        }
        break;

    case '/companies/delete':
        (new CompanyController())->delete();
        break;

    // VAGAS
    case '/jobs':
        echo (new JobController())->index();
        break;

    case '/jobs/create':
        echo (new JobController())->create();
        break;

    case '/jobs/store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new JobController())->store();
        }
        break;

    // CANDIDATOS
    case '/candidates':
        echo (new CandidateController())->index();
        break;

    case '/candidates/create':
        echo (new CandidateController())->create();
        break;

    case '/candidates/store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new CandidateController())->store();
        }
        break;

    // LOGOUT
    case '/logout':
        (new AuthController())->logout();
        break;

    default:
        http_response_code(404);
        echo "<h1>404</h1>";
        echo "<p>Página não encontrada.</p>";
}