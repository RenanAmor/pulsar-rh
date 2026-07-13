<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Configurações da aplicação
|--------------------------------------------------------------------------
*/

define('APP_NAME', 'Pulsar RH');

/*
|--------------------------------------------------------------------------
| Detecta automaticamente o ambiente
|--------------------------------------------------------------------------
*/

$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

if ($host === 'pulsarrh.local') {

    define('BASE_URL', '');

} else {

    define('BASE_URL', '/humania-rh/public');

}

define('APP_ENV', 'local');

define('APP_DEBUG', true);

date_default_timezone_set('America/Sao_Paulo');