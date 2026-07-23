<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Respostas | <?= APP_NAME ?></title>

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
            ['label' => 'Respostas'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <div class="page-header">

            <h1>Respostas das Pesquisas</h1>

        </div>

        <p>
            Selecione uma pesquisa para aplicar (responder em nome de um
            colaborador) ou para visualizar as respostas já registradas.
        </p>

        <table class="table">

            <thead>

                <tr>
                    <th>Empresa</th>
                    <th>Pesquisa</th>
                    <th>Status</th>
                    <th>Respondentes</th>
                    <th width="220">Ações</th>
                </tr>

            </thead>

            <tbody>

            <?php foreach ($surveys as $survey): ?>

                <tr>

                    <td><?= htmlspecialchars($survey['company_name']) ?></td>
                    <td><?= htmlspecialchars($survey['title']) ?></td>
                    <td><?= htmlspecialchars($survey['status']) ?></td>
                    <td><?= (int) $survey['respondents_count'] ?></td>

                    <td>

                        <a class="btn-action edit"
                           href="<?= BASE_URL ?>/answers/start?survey_id=<?= $survey['id'] ?>">
                            📝 Responder
                        </a>

                        <a class="btn-action edit"
                           href="<?= BASE_URL ?>/answers/respondents?survey_id=<?= $survey['id'] ?>">
                            👥 Ver Respostas
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
