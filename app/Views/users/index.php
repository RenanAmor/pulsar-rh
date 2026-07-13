<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Usuários | <?= APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

</head>

<body>

<div class="dashboard">

    <aside class="sidebar">

        <div class="logo">
            <span>P</span>
        </div>

        <h2><?= APP_NAME ?></h2>

        <nav>

            <a href="<?= BASE_URL ?>/dashboard">Dashboard</a>

            <a href="<?= BASE_URL ?>/users">Usuários</a>

            <a href="#">Empresas</a>

            <a href="#">Vagas</a>

            <a href="#">Candidatos</a>

            <a href="#">Avaliações</a>

            <a href="#">Relatórios</a>

            <a href="#">Configurações</a>

            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <header style="display:flex;justify-content:space-between;align-items:center;">

            <div>

                <h1>Usuários</h1>

                <p>Gerenciamento de usuários do sistema.</p>

            </div>

            <button>

                + Novo Usuário

            </button>

        </header>

        <div class="card">

            <table width="100%" cellpadding="12">

                <thead>

                    <tr>

                        <th align="left">Nome</th>
                        <th align="left">E-mail</th>
                        <th align="left">Perfil</th>
                        <th align="center">Status</th>

                    </tr>

                </thead>

                <tbody>

                <?php foreach ($users as $user): ?>

                    <tr>

                        <td><?= htmlspecialchars($user['name']) ?></td>

                        <td><?= htmlspecialchars($user['email']) ?></td>

                        <td><?= ucfirst($user['role']) ?></td>

                        <td align="center">

                            <?= $user['active'] ? '🟢 Ativo' : '🔴 Inativo' ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </main>

</div>

</body>

</html>