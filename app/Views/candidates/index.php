<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Candidatos | <?= APP_NAME ?></title>

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

        <div class="page-header">

            <h1>Candidatos</h1>

            <a class="btn-primary" href="<?= BASE_URL ?>/candidates/create">
                + Novo Candidato
            </a>

        </div>

        <table class="table">

            <thead>

            <tr>

                <th>Nome</th>
                <th>Empresa</th>
                <th>Vaga</th>
                <th>Status</th>

            </tr>

            </thead>

            <tbody>

            <?php foreach ($candidates as $candidate): ?>

                <tr>

                    <td><?= htmlspecialchars($candidate['name']) ?></td>

                    <td><?= htmlspecialchars($candidate['company']) ?></td>

                    <td><?= htmlspecialchars($candidate['job']) ?></td>

                    <td><?= htmlspecialchars($candidate['status']) ?></td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </main>

</div>

</body>

</html>