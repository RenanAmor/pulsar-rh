<?php

require_once __DIR__ . '/../app/autoload.php';

use Database\Database;

try {

    $pdo = Database::connect();

    echo "<h1>✅ Banco conectado com sucesso!</h1>";

} catch (Exception $e) {

    echo "<h1>❌ Erro:</h1>";
    echo $e->getMessage();

}