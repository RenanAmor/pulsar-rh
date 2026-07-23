<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Equipes | <?= APP_NAME ?></title>

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
            ['label' => 'Organização'],
            ['label' => 'Equipes'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <div class="page-header">

            <h1>Equipes</h1>

            <a class="btn-primary" href="<?= BASE_URL ?>/teams/create">
                + Nova Equipe
            </a>

        </div>

        <table class="table">

            <thead>

                <tr>
                    <th>Empresa</th>
                    <th>Filial</th>
                    <th>Setor</th>
                    <th>Equipe</th>
                    <th>Código</th>
                    <th>Gestor</th>
                    <th>Status</th>
                    <th width="180">Ações</th>
                </tr>

            </thead>

            <tbody>

            <?php foreach ($teams as $team): ?>

                <tr>

                    <td><?= htmlspecialchars($team['company_name']) ?></td>
                    <td><?= htmlspecialchars($team['branch_name']) ?></td>
                    <td><?= htmlspecialchars($team['department_name']) ?></td>
                    <td><?= htmlspecialchars($team['name']) ?></td>
                    <td><?= htmlspecialchars($team['code'] ?? '') ?></td>
                    <td><?= htmlspecialchars($team['leader_name'] ?? '') ?></td>
                    <td><?= $team['active'] ? 'Ativa' : 'Inativa' ?></td>

                    <td>

                        <a class="btn-action edit"
                           href="<?= BASE_URL ?>/teams/edit?id=<?= $team['id'] ?>">
                            ✏️ Editar
                        </a>

                        <a class="btn-action delete"
                           href="<?= BASE_URL ?>/teams/delete?id=<?= $team['id'] ?>"
                           onclick="return confirm('Deseja realmente excluir esta equipe?')">
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
