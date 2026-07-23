<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Perguntas | <?= APP_NAME ?></title>

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
            ['label' => 'Biblioteca Psicométrica'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <div class="page-header">

            <h1>Biblioteca Psicométrica</h1>

            <a class="btn-primary" href="<?= BASE_URL ?>/questions/create">
                + Nova Pergunta
            </a>

        </div>

        <table class="table">

            <thead>

                <tr>
                    <th>Categoria</th>
                    <th>Dimensão</th>
                    <th>Pergunta</th>
                    <th>Tipo de Resposta</th>
                    <th>Peso</th>
                    <th>Empresa</th>
                    <th>Status</th>
                    <th width="180">Ações</th>
                </tr>

            </thead>

            <tbody>

            <?php foreach ($questions as $question): ?>

                <tr>

                    <td><?= htmlspecialchars($question['category']) ?></td>
                    <td><?= htmlspecialchars($question['dimension']) ?></td>
                    <td><?= htmlspecialchars($question['question']) ?></td>
                    <td><?= htmlspecialchars($question['answer_type']) ?></td>
                    <td><?= htmlspecialchars((string) $question['weight']) ?></td>
                    <td><?= htmlspecialchars($question['company_name'] ?? 'Genérica (todas as empresas)') ?></td>
                    <td><?= $question['active'] ? 'Ativa' : 'Inativa' ?></td>

                    <td>

                        <a class="btn-action edit"
                           href="<?= BASE_URL ?>/questions/edit?id=<?= $question['id'] ?>">
                            ✏️ Editar
                        </a>

                        <a class="btn-action delete"
                           href="<?= BASE_URL ?>/questions/delete?id=<?= $question['id'] ?>"
                           onclick="return confirm('Deseja realmente excluir esta pergunta?')">
                            🗑️ Excluir
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </main>

</div>

</body>

</html>
