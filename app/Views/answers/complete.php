<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pesquisa Concluída - <?= APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

</head>

<body>

<div class="dashboard">

    <aside class="sidebar">

        <div class="logo">
            <span>P</span>
        </div>

        <h2>Pulsar RH</h2>

<?php $activeNav = 'administration'; require __DIR__ . '/../partials/nav.php'; ?>

    </aside>

    <main class="content">

        <h1>Pesquisa Concluída</h1>

        <div class="card">

            <p>
                Obrigado, <strong><?= htmlspecialchars($employee['name']) ?></strong>!
            </p>

            <p>
                Suas respostas para a pesquisa
                <strong><?= htmlspecialchars($survey['title']) ?></strong>
                foram registradas.
            </p>

            <p>
                Perguntas respondidas: <strong><?= $answeredQuestions ?> / <?= $totalQuestions ?></strong>
            </p>

            <a class="btn-primary" href="<?= BASE_URL ?>/answers">
                Voltar às Pesquisas
            </a>

        </div>

    </main>

</div>

</body>

</html>
