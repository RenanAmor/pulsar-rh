<?php

$priorityClass = [
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

    <title>Recomendações | <?= APP_NAME ?></title>

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
            ['label' => 'Recomendações'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <?php if (isset($center['error'])): ?>

            <div class="card">
                <h2>Ainda não há dados suficientes</h2>
                <p><?= htmlspecialchars($center['error']) ?></p>
            </div>

        <?php else: ?>

            <?php require __DIR__ . '/../partials/intelligence-header.php'; ?>

            <?php require __DIR__ . '/../partials/intelligence-subnav.php'; ?>

            <?php if ($center['recommendations']['empty']): ?>

                <div class="card">
                    <h2>Nenhuma recomendação no momento</h2>
                    <p>Os indicadores estão dentro do esperado — mantenha as boas práticas atuais.</p>
                </div>

            <?php else: ?>

                <div class="rec-cards">

                    <?php foreach ($center['recommendations']['items'] as $index => $recommendation): ?>

                        <div class="card rec-card <?= $priorityClass[$recommendation['priority']] ?? '' ?>">

                            <div class="rec-card-head">
                                <span class="rec-rank">#<?= $index + 1 ?></span>
                                <span class="badge-severity <?= $priorityClass[$recommendation['priority']] ?? '' ?>"><?= htmlspecialchars($recommendation['priority']) ?></span>
                            </div>

                            <h2><?= htmlspecialchars($recommendation['objective']) ?></h2>

                            <p class="rec-reason"><strong>Motivo:</strong> <?= htmlspecialchars($recommendation['reason']) ?></p>
                            <p class="rec-impact"><strong>Impacto esperado:</strong> <?= htmlspecialchars($recommendation['impact']) ?></p>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        <?php endif; ?>

    </main>

</div>

</body>

</html>
