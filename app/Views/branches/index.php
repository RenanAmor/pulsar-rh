<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Filiais | <?= APP_NAME ?></title>

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
            ['label' => 'Filiais'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <div class="page-header">

            <h1>Filiais</h1>

            <a class="btn-primary" href="<?= BASE_URL ?>/branches/create">
                + Nova Filial
            </a>

        </div>

        <table class="table">

            <thead>

                <tr>
                    <th>Empresa</th>
                    <th>Filial</th>
                    <th>CNPJ</th>
                    <th>Cidade</th>
                    <th>Status</th>
                    <th width="180">Ações</th>
                </tr>

            </thead>

            <tbody>

            <?php foreach ($branches as $branch): ?>

                <tr>

                    <td><?= htmlspecialchars($branch['company_name']) ?></td>
                    <td><?= htmlspecialchars($branch['name']) ?></td>
                    <td><?= htmlspecialchars($branch['document']) ?></td>
                    <td><?= htmlspecialchars($branch['city'] ?? '') ?></td>
                    <td><?= $branch['active'] ? 'Ativa' : 'Inativa' ?></td>

                    <td>

                        <a class="btn-action edit"
                           href="<?= BASE_URL ?>/branches/edit?id=<?= $branch['id'] ?>">
                            ✏️ Editar
                        </a>

                        <a class="btn-action delete"
                           href="<?= BASE_URL ?>/branches/delete?id=<?= $branch['id'] ?>"
                           onclick="return confirm('Deseja realmente excluir esta filial?')">
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
