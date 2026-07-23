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

            <?php if (!isset($assistant['error'])): ?>

                <section class="card assistant-message">
                    <p><?= htmlspecialchars($assistant['executiveMessage']) ?></p>
                </section>

                <section class="card assistant-summary">

                    <h2>Resumo Inteligente</h2>

                    <p><strong>Situação atual.</strong> <?= htmlspecialchars($assistant['summary']['currentSituation']) ?></p>
                    <p><strong>Evolução.</strong> <?= htmlspecialchars($assistant['summary']['evolution']) ?></p>
                    <p><strong>Tendência.</strong> <?= htmlspecialchars($assistant['summary']['trend']) ?></p>

                </section>

                <?php if (!empty($assistant['priorityTopics'])): ?>

                    <section class="card assistant-topics">

                        <h2>Assuntos Prioritários</h2>

                        <ul class="alert-list">

                            <?php foreach ($assistant['priorityTopics'] as $topic): ?>

                                <li class="<?= $severityClass[$topic['severity']] ?? '' ?>">
                                    <strong><?= htmlspecialchars($topic['theme']) ?>.</strong>
                                    <?= htmlspecialchars($topic['headline']) ?>
                                </li>

                            <?php endforeach; ?>

                        </ul>

                    </section>

                <?php endif; ?>

                <section class="card assistant-actions">

                    <h2>Ações Recomendadas</h2>

                    <?php if (empty($assistant['recommendedActions'])): ?>

                        <p>Nenhuma ação crítica no momento — mantenha as boas práticas atuais.</p>

                    <?php else: ?>

                        <ol class="action-list">

                            <?php foreach ($assistant['recommendedActions'] as $action): ?>

                                <li class="<?= $severityClass[$action['priority']] ?? '' ?>">
                                    <strong><?= htmlspecialchars($action['title']) ?></strong>
                                    <p><?= htmlspecialchars($action['reason']) ?></p>
                                </li>

                            <?php endforeach; ?>

                        </ol>

                    <?php endif; ?>

                </section>

                <section class="card assistant-questions">

                    <h2>Perguntas Estratégicas</h2>

                    <ul class="question-list">

                        <?php foreach ($assistant['strategicQuestions'] as $question): ?>

                            <li><?= htmlspecialchars($question) ?></li>

                        <?php endforeach; ?>

                    </ul>

                </section>

            <?php endif; ?>

        <?php endif; ?>

    </main>

</div>

</body>

</html>
