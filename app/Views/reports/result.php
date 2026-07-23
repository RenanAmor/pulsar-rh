<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Relatório - <?= APP_NAME ?></title>

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

            <h1>Relatório: <?= htmlspecialchars($survey['title'] ?? '') ?></h1>

            <a class="btn-primary" href="<?= BASE_URL ?>/reports">
                ← Voltar
            </a>

        </div>

        <?php if (isset($result['error'])): ?>

            <p><?= htmlspecialchars($result['error']) ?></p>

        <?php else: ?>

            <div class="card">

                <h2>Resumo Executivo</h2>

                <p><?= nl2br(htmlspecialchars($result['parsed']['executive_summary'])) ?></p>

            </div>

            <?php if (!empty($result['parsed']['detailed_analysis'])): ?>

                <div class="card">

                    <h2>Análise Detalhada</h2>

                    <p><?= nl2br(htmlspecialchars($result['parsed']['detailed_analysis'])) ?></p>

                </div>

            <?php endif; ?>

            <div class="card">

                <h2>Oportunidades</h2>

                <?php if (empty($result['parsed']['opportunities'])): ?>
                    <p>Nenhuma oportunidade identificada.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($result['parsed']['opportunities'] as $item): ?>
                            <li><?= htmlspecialchars($item) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

            </div>

            <div class="card">

                <h2>Riscos</h2>

                <?php if (empty($result['parsed']['risks'])): ?>
                    <p>Nenhum risco identificado.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($result['parsed']['risks'] as $item): ?>
                            <li><?= htmlspecialchars($item) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

            </div>

            <div class="card">

                <h2>Recomendações</h2>

                <?php if (empty($result['parsed']['recommendations'])): ?>
                    <p>Nenhuma recomendação gerada.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($result['parsed']['recommendations'] as $item): ?>
                            <li><?= htmlspecialchars($item) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

            </div>

        <?php endif; ?>

    </main>

</div>

</body>

</html>
