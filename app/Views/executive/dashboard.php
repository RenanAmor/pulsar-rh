<?php

$severityClass = [
    'Crítica' => 'sev-critica',
    'Alta'    => 'sev-alta',
    'Média'   => 'sev-media',
];

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | <?= APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

</head>

<body>

<div class="dashboard">

    <aside class="sidebar">

        <div class="logo">
            <span>P</span>
        </div>

        <h2><?= APP_NAME ?></h2>

<?php $activeNav = 'dashboard'; require __DIR__ . '/../partials/nav.php'; ?>

    </aside>

    <main class="content">

        <?php if (isset($dashboard['error'])): ?>

            <div class="card">
                <h2>Ainda não há dados suficientes</h2>
                <p><?= htmlspecialchars($dashboard['error']) ?></p>
            </div>

        <?php else: ?>

            <header class="exec-greeting">

                <h1><?= htmlspecialchars($dashboard['greeting']['salutation']) ?>, <?= htmlspecialchars($user['name']) ?>.</h1>

                <p>
                    <?php if ($dashboard['greeting']['companyName']): ?>
                        Organização analisada: <strong><?= htmlspecialchars($dashboard['greeting']['companyName']) ?></strong>
                        <?php if ($dashboard['greeting']['analyzedAt']): ?>&nbsp;&middot;&nbsp;<?php endif; ?>
                    <?php endif; ?>
                    <?php if ($dashboard['greeting']['analyzedAt']): ?>
                        Análise realizada em <?= htmlspecialchars($dashboard['greeting']['analyzedAt']) ?>
                    <?php endif; ?>
                </p>

            </header>

            <section class="exec-hero <?= $dashboard['health']['tierClass'] ?>">

                <div class="exec-hero-status">
                    <span class="exec-hero-label">Sua organização encontra-se</span>
                    <h2 class="exec-hero-tier"><?= htmlspecialchars($dashboard['health']['classification'] ?? '—') ?></h2>
                </div>

                <div class="exec-hero-score">
                    <strong><?= $dashboard['health']['score'] !== null ? number_format($dashboard['health']['score'], 0) : '—' ?></strong>
                    <small>Índice Geral</small>
                </div>

            </section>

            <?php if (!empty($dashboard['topAlerts'])): ?>

                <section class="card exec-alerts">

                    <h2>Principais Alertas</h2>

                    <ul class="alert-list">

                        <?php foreach ($dashboard['topAlerts'] as $alert): ?>

                            <li class="<?= $severityClass[$alert['severity']] ?? '' ?>">
                                <?= htmlspecialchars($alert['headline']) ?>
                            </li>

                        <?php endforeach; ?>

                    </ul>

                </section>

            <?php endif; ?>

            <section class="card exec-changes">

                <h2>O que Mudou</h2>

                <?php if ($dashboard['changes']['message'] !== null): ?>

                    <p><?= htmlspecialchars($dashboard['changes']['message']) ?></p>

                <?php else: ?>

                    <div class="change-chips">

                        <?php foreach ($dashboard['changes']['items'] as $item): ?>

                            <span class="chip"><?= $item['arrow'] ?> <?= htmlspecialchars($item['label']) ?></span>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

            </section>

            <section class="card exec-priority <?= $severityClass[$dashboard['priorityOfDay']['severity']] ?? '' ?>">

                <h2>Prioridade do Dia</h2>

                <p><?= htmlspecialchars($dashboard['priorityOfDay']['title']) ?></p>

            </section>

            <section class="card exec-recommendation">

                <h2>Recomendação Principal</h2>

                <?php if ($dashboard['mainRecommendation']['message'] !== null): ?>

                    <p><?= htmlspecialchars($dashboard['mainRecommendation']['message']) ?></p>

                <?php else: ?>

                    <h3><?= htmlspecialchars($dashboard['mainRecommendation']['title']) ?></h3>
                    <p><?= htmlspecialchars($dashboard['mainRecommendation']['reason']) ?></p>

                <?php endif; ?>

            </section>

        <?php endif; ?>

    </main>

</div>

</body>

</html>
