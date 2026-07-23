<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mudanças Recentes | <?= APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

</head>

<body>

<div class="dashboard">

    <aside class="sidebar">

        <div class="logo">
            <span>P</span>
        </div>

        <h2><?= APP_NAME ?></h2>

<?php $activeNav = 'intelligence'; require __DIR__ . '/../partials/nav.php'; ?>

    </aside>

    <main class="content">

        <?php $breadcrumb = [
            ['label' => 'Centro de Inteligência Organizacional', 'href' => BASE_URL . '/intelligence'],
            ['label' => 'Mudanças Recentes'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <?php if (isset($center['error'])): ?>

            <div class="card">
                <h2>Ainda não há dados suficientes</h2>
                <p><?= htmlspecialchars($center['error']) ?></p>
            </div>

        <?php else: ?>

            <?php require __DIR__ . '/../partials/intelligence-header.php'; ?>

            <?php require __DIR__ . '/../partials/intelligence-subnav.php'; ?>

            <div class="card">

                <h2>O que Mudou desde a Última Pesquisa</h2>

                <?php if (!$center['changes']['hasPrevious'] || $center['changes']['message'] !== null): ?>

                    <p><?= htmlspecialchars($center['changes']['message']) ?></p>

                <?php else: ?>

                    <div class="change-list">

                        <?php foreach ($center['changes']['items'] as $item): ?>

                            <div class="change-row">
                                <span class="change-arrow <?= $item['arrow'] === '↑' ? 'up' : 'down' ?>"><?= $item['arrow'] ?></span>
                                <span class="change-label"><?= htmlspecialchars($item['label']) ?></span>
                                <span class="change-intensity"><?= htmlspecialchars($item['intensity']) ?></span>
                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

            </div>

        <?php endif; ?>

    </main>

</div>

</body>

</html>
