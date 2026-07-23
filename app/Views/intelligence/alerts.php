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

    <title>Alertas | <?= APP_NAME ?></title>

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
            ['label' => 'Alertas'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <?php if (isset($center['error'])): ?>

            <div class="card">
                <h2>Ainda não há dados suficientes</h2>
                <p><?= htmlspecialchars($center['error']) ?></p>
            </div>

        <?php else: ?>

            <?php require __DIR__ . '/../partials/intelligence-header.php'; ?>

            <?php require __DIR__ . '/../partials/intelligence-subnav.php'; ?>

            <?php if ($center['alerts']['empty']): ?>

                <div class="card">
                    <h2>Nenhum alerta ativo</h2>
                    <p>Os indicadores organizacionais estão dentro do esperado. Continue monitorando.</p>
                </div>

            <?php else: ?>

                <div class="alert-cards">

                    <?php foreach ($center['alerts']['items'] as $alert): ?>

                        <div class="card alert-card <?= $severityClass[$alert['severity']] ?? '' ?>">

                            <div class="alert-card-head">
                                <h2><?= htmlspecialchars($alert['headline']) ?></h2>
                                <span class="badge-severity <?= $severityClass[$alert['severity']] ?? '' ?>"><?= htmlspecialchars($alert['severity']) ?></span>
                            </div>

                            <dl class="alert-card-meta">
                                <div>
                                    <dt>Setor Afetado</dt>
                                    <dd><?= htmlspecialchars($alert['sector']) ?></dd>
                                </div>
                                <div>
                                    <dt>Tendência</dt>
                                    <dd><?= htmlspecialchars($alert['trend']) ?></dd>
                                </div>
                            </dl>

                            <p class="alert-card-impact"><strong>Impacto:</strong> <?= htmlspecialchars($alert['impact']) ?></p>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        <?php endif; ?>

    </main>

</div>

</body>

</html>
