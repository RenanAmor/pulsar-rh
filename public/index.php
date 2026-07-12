<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/autoload.php';

use App\Controllers\AuthController;

$controller = new AuthController();
echo $controller->login();