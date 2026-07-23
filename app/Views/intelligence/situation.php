<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Situação Atual | <?= APP_NAME ?></title>

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
            ['label' => 'Situação Atual'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <?php if (isset($center['error'])): ?>

            <div class="card">
                <h2>Ainda não há dados suficientes</h2>
                <p><?= htmlspecialchars($center['error']) ?></p>
            </div>

        <?php else: ?>

            <?php require __DIR__ . '/../partials/intelligence-header.php'; ?>

            <?php require __DIR__ . '/../partials/intelligence-subnav.php'; ?>

            <div class="card intel-summary">
                <h2>Resumo Executivo</h2>
                <p><?= htmlspecialchars($center['situation']['summary']) ?></p>
            </div>

            <section class="cards">

                <div class="card">
                    <h3>Índice Geral</h3>
                    <h2><?= $center['situation']['score'] !== null ? number_format($center['situation']['score'], 0) : '—' ?></h2>
                </div>

                <div class="card">
                    <h3>Classificação Organizacional</h3>
                    <h2><?= htmlspecialchars($center['situation']['classification'] ?? '—') ?></h2>
                </div>

                <div class="card">
                    <h3>Participação</h3>
                    <h2><?= $center['situation']['participation'] !== null ? number_format($center['situation']['participation'], 0) . '%' : '—' ?></h2>
                </div>

                <div class="card intel-company-card">
                    <h3>Empresa Analisada</h3>
                    <h2><?= htmlspecialchars($center['meta']['companyName'] ?? '—') ?></h2>
                </div>

            </section>

            <section class="cards">

                <a class="card intel-link-card" href="<?= BASE_URL ?>/intelligence/alerts">
                    <h3>Alertas Ativos</h3>
                    <h2><?= count($center['alerts']['items']) ?></h2>
                </a>

                <a class="card intel-link-card" href="<?= BASE_URL ?>/intelligence/recommendations">
                    <h3>Recomendações Priorizadas</h3>
                    <h2><?= count($center['recommendations']['items']) ?></h2>
                </a>

                <a class="card intel-link-card" href="<?= BASE_URL ?>/intelligence/changes">
                    <h3>Mudanças Relevantes</h3>
                    <h2><?= count($center['changes']['items']) ?></h2>
                </a>

            </section>

        <?php endif; ?>

    </main>

</div>

</body>

</html>
