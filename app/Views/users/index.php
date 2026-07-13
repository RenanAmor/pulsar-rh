<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Usuários - <?= APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

</head>

<body>

<div class="dashboard">

    <aside class="sidebar">

        <div class="logo">
            <span>P</span>
        </div>

        <h2>Pulsar RH</h2>

        <nav>
            <a href="<?= BASE_URL ?>/dashboard">Dashboard</a>
            <a class="active" href="<?= BASE_URL ?>/users">Usuários</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>
        </nav>

    </aside>

    <main class="content">

        <div class="page-header">

            <h1>Usuários</h1>

            <a class="btn-primary" href="<?= BASE_URL ?>/users/create">
                + Novo Usuário
            </a>

        </div>

        <table class="table">

            <thead>

                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Perfil</th>
                    <th>Status</th>
                    <th width="180">Ações</th>
                </tr>

            </thead>

            <tbody>

            <?php foreach ($users as $user): ?>

                <tr>

                    <td><?= htmlspecialchars($user['name']) ?></td>

                    <td><?= htmlspecialchars($user['email']) ?></td>

                    <td><?= htmlspecialchars($user['role']) ?></td>

                    <td>
                        <?= $user['active'] ? 'Ativo' : 'Inativo' ?>
                    </td>

                    <td>

                        <a class="btn-action edit"
                           href="<?= BASE_URL ?>/users/edit?id=<?= $user['id'] ?>">
                            ✏️ Editar
                        </a>

                        <a class="btn-action delete"
                           href="<?= BASE_URL ?>/users/delete?id=<?= $user['id'] ?>"
                           onclick="return confirm('Deseja realmente excluir este usuário?')">
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