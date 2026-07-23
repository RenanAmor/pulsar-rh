<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/app/autoload.php';

use App\Controllers\AnswerController;
use App\Controllers\AuthController;
use App\Controllers\BranchController;
use App\Controllers\CandidateController;
use App\Controllers\CompanyController;
use App\Controllers\DashboardController;
use App\Controllers\DepartmentController;
use App\Controllers\EmployeeController;
use App\Controllers\IndicatorController;
use App\Controllers\JobController;
use App\Controllers\LaboratoryController;
use App\Controllers\OIEController;
use App\Controllers\PositionController;
use App\Controllers\QuestionController;
use App\Controllers\SurveyController;
use App\Controllers\SurveyQuestionController;
use App\Controllers\TeamController;
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

    // FILIAIS
    case '/branches':
        echo (new BranchController())->index();
        break;

    case '/branches/create':
        echo (new BranchController())->create();
        break;

    case '/branches/store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new BranchController())->store();
        }
        break;

    case '/branches/edit':
        echo (new BranchController())->edit();
        break;

    case '/branches/update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new BranchController())->update();
        }
        break;

    case '/branches/delete':
        (new BranchController())->delete();
        break;

    // SETORES
    case '/departments':
        echo (new DepartmentController())->index();
        break;

    case '/departments/create':
        echo (new DepartmentController())->create();
        break;

    case '/departments/store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new DepartmentController())->store();
        }
        break;

    case '/departments/edit':
        echo (new DepartmentController())->edit();
        break;

    case '/departments/update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new DepartmentController())->update();
        }
        break;

    case '/departments/delete':
        (new DepartmentController())->delete();
        break;

    // EQUIPES
    case '/teams':
        echo (new TeamController())->index();
        break;

    case '/teams/create':
        echo (new TeamController())->create();
        break;

    case '/teams/store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new TeamController())->store();
        }
        break;

    case '/teams/edit':
        echo (new TeamController())->edit();
        break;

    case '/teams/update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new TeamController())->update();
        }
        break;

    case '/teams/delete':
        (new TeamController())->delete();
        break;

    // CARGOS
    case '/positions':
        echo (new PositionController())->index();
        break;

    case '/positions/create':
        echo (new PositionController())->create();
        break;

    case '/positions/store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new PositionController())->store();
        }
        break;

    case '/positions/edit':
        echo (new PositionController())->edit();
        break;

    case '/positions/update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new PositionController())->update();
        }
        break;

    case '/positions/delete':
        (new PositionController())->delete();
        break;

    // COLABORADORES
    case '/employees':
        echo (new EmployeeController())->index();
        break;

    case '/employees/create':
        echo (new EmployeeController())->create();
        break;

    case '/employees/store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new EmployeeController())->store();
        }
        break;

    case '/employees/edit':
        echo (new EmployeeController())->edit();
        break;

    case '/employees/update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new EmployeeController())->update();
        }
        break;

    case '/employees/delete':
        (new EmployeeController())->delete();
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

    // PESQUISAS
    case '/surveys':
        echo (new SurveyController())->index();
        break;

    case '/surveys/create':
        echo (new SurveyController())->create();
        break;

    case '/surveys/store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new SurveyController())->store();
        }
        break;

    case '/surveys/edit':
        echo (new SurveyController())->edit();
        break;

    case '/surveys/update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new SurveyController())->update();
        }
        break;

    case '/surveys/delete':
        (new SurveyController())->delete();
        break;

    // PERGUNTAS
    case '/questions':
        echo (new QuestionController())->index();
        break;

    case '/questions/create':
        echo (new QuestionController())->create();
        break;

    case '/questions/store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new QuestionController())->store();
        }
        break;

    case '/questions/edit':
        echo (new QuestionController())->edit();
        break;

    case '/questions/update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new QuestionController())->update();
        }
        break;

    case '/questions/delete':
        (new QuestionController())->delete();
        break;

    // MONTAGEM DE PESQUISAS
    case '/survey-questions':
        echo (new SurveyQuestionController())->index();
        break;

    case '/survey-questions/manage':
        echo (new SurveyQuestionController())->manage();
        break;

    case '/survey-questions/add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new SurveyQuestionController())->add();
        }
        break;

    case '/survey-questions/remove':
        (new SurveyQuestionController())->remove();
        break;

    case '/survey-questions/toggle-required':
        (new SurveyQuestionController())->toggleRequired();
        break;

    case '/survey-questions/move-up':
        (new SurveyQuestionController())->moveUp();
        break;

    case '/survey-questions/move-down':
        (new SurveyQuestionController())->moveDown();
        break;

    // RESPOSTAS
    case '/answers':
        echo (new AnswerController())->index();
        break;

    case '/answers/respondents':
        echo (new AnswerController())->respondents();
        break;

    case '/answers/view':
        echo (new AnswerController())->view();
        break;

    case '/answers/start':
        echo (new AnswerController())->start();
        break;

    case '/answers/begin':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new AnswerController())->begin();
        }
        break;

    case '/answers/apply':
        echo (new AnswerController())->apply();
        break;

    case '/answers/submit':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new AnswerController())->submit();
        }
        break;

    case '/answers/complete':
        echo (new AnswerController())->complete();
        break;

    // INDICADORES ORGANIZACIONAIS
    case '/indicators':
        echo (new IndicatorController())->index();
        break;

    case '/indicators/show':
        echo (new IndicatorController())->show();
        break;

    // LABORATÓRIO ORGANIZACIONAL
    case '/laboratory':
        echo (new LaboratoryController())->index();
        break;

    case '/laboratory/generate':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo (new LaboratoryController())->generate();
        }
        break;

    case '/laboratory/clear':
        (new LaboratoryController())->clear();
        break;

    // ORGANIZATIONAL INTELLIGENCE ENGINE (OIE)
    case '/oie':
        echo (new OIEController())->index();
        break;

    case '/oie/show':
        echo (new OIEController())->show();
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