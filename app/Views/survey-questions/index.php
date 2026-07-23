<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Montagem de Pesquisas | <?= APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

</head>

<body>

<div class="dashboard">

    <aside class="sidebar">

        <div class="logo">
            <span>P</span>
        </div>

        <h2><?= APP_NAME ?></h2>

<?php $activeNav = 'administration'; require __DIR__ . '/../partials/nav.php'; ?>

    </aside>

    <main class="content">

        <?php $breadcrumb = [
            ['label' => 'Administração', 'href' => BASE_URL . '/administration'],
            ['label' => 'Pesquisas'],
            ['label' => 'Montagem de Pesquisas'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <div class="page-header">

            <h1>Montagem de Pesquisas</h1>

        </div>

        <p>
            Selecione uma pesquisa para adicionar, remover ou reordenar as
            perguntas da Biblioteca Psicométrica utilizadas nela.
        </p>

        <table class="table">

            <thead>

                <tr>
                    <th>Empresa</th>
                    <th>Pesquisa</th>
                    <th>Status</th>
                    <th>Perguntas Vinculadas</th>
                    <th width="180">Ações</th>
                </tr>

            </thead>

            <tbody>

            <?php foreach ($surveys as $survey): ?>

                <tr>

                    <td><?= htmlspecialchars($survey['company_name']) ?></td>
                    <td><?= htmlspecialchars($survey['title']) ?></td>
                    <td><?= htmlspecialchars($survey['status']) ?></td>
                    <td><?= (int) $survey['questions_count'] ?></td>

                    <td>

                        <a class="btn-action edit"
                           href="<?= BASE_URL ?>/survey-questions/manage?survey_id=<?= $survey['id'] ?>">
                            🧩 Montar
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </main>

</div>

</body>

</html>
