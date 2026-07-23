<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Relatórios | <?= APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

</head>

<body>

<div class="dashboard">

    <aside class="sidebar">

        <div class="logo">
            <span>P</span>
        </div>

        <h2><?= APP_NAME ?></h2>

<?php $activeNav = 'reports'; require __DIR__ . '/../partials/nav.php'; ?>

    </aside>

    <main class="content">

        <div class="page-header">
            <h1>Centro de Relatórios</h1>
        </div>

        <div class="card">

            <h2>Relatório Executivo</h2>

            <p>Resumo executivo, riscos, oportunidades e recomendações gerados a partir da análise organizacional de cada pesquisa.</p>

            <table class="table">

                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>Pesquisa</th>
                        <th width="160">Ações</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($surveys as $survey): ?>

                    <tr>

                        <td><?= htmlspecialchars($survey['company_name']) ?></td>
                        <td><?= htmlspecialchars($survey['title']) ?></td>

                        <td>
                            <form method="POST" action="<?= BASE_URL ?>/reports/generate">
                                <input type="hidden" name="survey_id" value="<?= $survey['id'] ?>">
                                <button type="submit" class="btn-primary">Gerar Relatório</button>
                            </form>
                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

        <div class="card">

            <h2>Em breve</h2>

            <ul>
                <li>Relatório Comparativo entre pesquisas</li>
                <li>Relatório Histórico consolidado</li>
                <li>Exportação em PDF</li>
            </ul>

        </div>

    </main>

</div>

</body>

</html>
