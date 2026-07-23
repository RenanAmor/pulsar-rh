<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inteligência Organizacional | <?= APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

</head>

<body>

<div class="dashboard">

    <aside class="sidebar">

        <div class="logo">
            <span>P</span>
        </div>

        <h2><?= APP_NAME ?></h2>

<?php $activeNav = 'oie'; require __DIR__ . '/../partials/nav.php'; ?>

    </aside>

    <main class="content">

        <div class="page-header">

            <h1>Inteligência Organizacional</h1>

        </div>

        <p>
            Selecione uma pesquisa para que o OIE interprete os indicadores
            calculados pelo Motor de Indicadores, identifique padrões,
            riscos e produza recomendações baseadas em regras de negócio
            (sem uso de Inteligência Artificial nesta etapa).
        </p>

        <table class="table">

            <thead>

                <tr>
                    <th>Empresa</th>
                    <th>Pesquisa</th>
                    <th>Status</th>
                    <th width="160">Ações</th>
                </tr>

            </thead>

            <tbody>

            <?php foreach ($surveys as $survey): ?>

                <tr>

                    <td><?= htmlspecialchars($survey['company_name']) ?></td>
                    <td><?= htmlspecialchars($survey['title']) ?></td>
                    <td><?= htmlspecialchars($survey['status']) ?></td>

                    <td>

                        <a class="btn-action edit"
                           href="<?= BASE_URL ?>/oie/show?survey_id=<?= $survey['id'] ?>">
                            🧠 Analisar
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
