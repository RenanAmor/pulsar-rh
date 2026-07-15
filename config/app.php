<?php

declare(strict_types=1);

define('APP_NAME', 'Pulsar RH');

$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

if (
    $host === 'pulsarrh.local' ||
    $host === 'localhost'
) {

    define('APP_ENV', 'local');
    define('BASE_URL', '');

} else {

    define('APP_ENV', 'production');
    define('BASE_URL', '');

}

define('APP_DEBUG', APP_ENV === 'local');

date_default_timezone_set('America/Sao_Paulo');