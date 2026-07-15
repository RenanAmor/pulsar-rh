<?php

declare(strict_types=1);

require_once __DIR__ . '/app/autoload.php';

use App\Database\Migration;
use Database\Database;

try {

    $migration = new Migration(Database::connect());

    $migration->run(__DIR__ . '/app/Database/migrations');

} catch (Throwable $e) {

    echo PHP_EOL;
    echo "==========================================" . PHP_EOL;
    echo "ERRO: " . $e->getMessage() . PHP_EOL;
    echo "==========================================" . PHP_EOL;
}