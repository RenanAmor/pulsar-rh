<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Respondentes - <?= APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

</head>

<body>

<div class="dashboard">

    <aside class="sidebar">

        <div class="logo">
            <span>P</span>
        </div>

        <h2>Pulsar RH</h2>

<?php $activeNav = 'administration'; require __DIR__ . '/../partials/nav.php'; ?>

    </aside>

    <main class="content">

        <div class="page-header">

            <h1>Respondentes: <?= htmlspecialchars($survey['title']) ?></h1>

            <a class="btn-primary" href="<?= BASE_URL ?>/answers">
                ← Voltar
            </a>

        </div>

        <p>
            Total de perguntas da pesquisa: <strong><?= $totalQuestions ?></strong>
        </p>

        <?php if (empty($respondents)): ?>

            <p>Nenhum colaborador respondeu esta pesquisa ainda.</p>

        <?php else: ?>

            <table class="table">

                <thead>

                    <tr>
                        <th>Colaborador</th>
                        <th>Perguntas Respondidas</th>
                        <th>Última Resposta</th>
                        <th width="120">Ações</th>
                    </tr>

                </thead>

                <tbody>

                <?php foreach ($respondents as $respondent): ?>

                    <tr>

                        <td><?= htmlspecialchars($respondent['employee_name']) ?></td>
                        <td><?= (int) $respondent['answered_count'] ?> / <?= $totalQuestions ?></td>
                        <td><?= htmlspecialchars($respondent['last_answered_at'] ?? '') ?></td>

                        <td>

                            <a class="btn-action edit"
                               href="<?= BASE_URL ?>/answers/view?survey_id=<?= $survey['id'] ?>&employee_id=<?= $respondent['employee_id'] ?>">
                                👁️ Ver
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        <?php endif; ?>

    </main>

</div>

</body>

</html>
