<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/autoload.php';

use App\Models\User;

echo "<h1>Teste do Pulsar RH</h1>";

try {

    $userModel = new User();

    $user = $userModel->findByEmail('admin@pulsarrh.com');

    if ($user) {

        echo "<p style='color:green;font-weight:bold;'>✅ Usuário encontrado!</p>";

        echo "<pre>";
        print_r($user);
        echo "</pre>";

    } else {

        echo "<p style='color:red;font-weight:bold;'>❌ Usuário não encontrado.</p>";

    }

} catch (Throwable $e) {

    echo "<h3 style='color:red;'>Erro</h3>";

    echo "<pre>";
    echo $e->getMessage();
    echo "</pre>";

}