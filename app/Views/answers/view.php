<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Respostas do Colaborador | <?= APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

</head>

<body>

<div class="dashboard">

    <aside class="sidebar">

        <div class="logo">
            <span>P</span>
        </div>

        <h2><?= APP_NAME ?></h2>

<?php $activeNav = 'administration'; require __DIR__ . '/../partials/nav.php'; ?>

    </aside>

    <main class="content">

        <?php $breadcrumb = [
            ['label' => 'Administração', 'href' => BASE_URL . '/administration'],
            ['label' => 'Pesquisas'],
            ['label' => 'Respostas', 'href' => BASE_URL . '/answers'],
            ['label' => 'Respondentes', 'href' => BASE_URL . '/answers/respondents?survey_id=' . $survey["id"]],
            ['label' => 'Respostas do Colaborador'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <div class="page-header">

            <h1><?= htmlspecialchars($employee['name']) ?> — <?= htmlspecialchars($survey['title']) ?></h1>

            <a class="btn-primary" href="<?= BASE_URL ?>/answers/respondents?survey_id=<?= $survey['id'] ?>">
                ← Voltar
            </a>

        </div>

        <?php if (empty($answers)): ?>

            <p>Este colaborador ainda não respondeu nenhuma pergunta.</p>

        <?php else: ?>

            <table class="table">

                <thead>

                    <tr>
                        <th>Categoria</th>
                        <th>Dimensão</th>
                        <th>Pergunta</th>
                        <th>Resposta</th>
                        <th>Data/Hora</th>
                    </tr>

                </thead>

                <tbody>

                <?php foreach ($answers as $answer): ?>

                    <tr>

                        <td><?= htmlspecialchars($answer['category']) ?></td>
                        <td><?= htmlspecialchars($answer['dimension']) ?></td>
                        <td><?= htmlspecialchars($answer['question']) ?></td>
                        <td>
                            <?= htmlspecialchars(
                                $answer['score'] !== null
                                    ? (string) $answer['score']
                                    : (string) ($answer['answer_text'] ?? '')
                            ) ?>
                        </td>
                        <td><?= htmlspecialchars($answer['answered_at']) ?></td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        <?php endif; ?>

    </main>

</div>

</body>

</html>
