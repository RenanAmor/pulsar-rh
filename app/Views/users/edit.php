<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Usuário - <?= APP_NAME ?></title>

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
            <a href="<?= BASE_URL ?>/companies">Empresas</a>
            <a href="<?= BASE_URL ?>/branches">Filiais</a>
            <a href="<?= BASE_URL ?>/departments">Setores</a>
            <a href="<?= BASE_URL ?>/teams">Equipes</a>
            <a href="<?= BASE_URL ?>/positions">Cargos</a>
            <a href="<?= BASE_URL ?>/employees">Colaboradores</a>
            <a href="<?= BASE_URL ?>/jobs">Vagas</a>
            <a href="<?= BASE_URL ?>/candidates">Candidatos</a>
            <a href="<?= BASE_URL ?>/surveys">Pesquisas</a>
            <a href="<?= BASE_URL ?>/questions">Perguntas</a>
            <a href="<?= BASE_URL ?>/survey-questions">Montagem de Pesquisas</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <h1>Editar Usuário</h1>

        <div class="card">

            <form method="POST" action="<?= BASE_URL ?>/users/update">

                <input
                    type="hidden"
                    name="id"
                    value="<?= $user['id'] ?>"
                >

                <label>Nome</label>

                <input
                    type="text"
                    name="name"
                    value="<?= htmlspecialchars($user['name']) ?>"
                    required
                >

                <label>E-mail</label>

                <input
                    type="email"
                    name="email"
                    value="<?= htmlspecialchars($user['email']) ?>"
                    required
                >

                <label>Perfil</label>

                <select name="role">

                    <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Administrador</option>

                    <option value="rh" <?= $user['role']=='rh'?'selected':'' ?>>RH</option>

                    <option value="gestor" <?= $user['role']=='gestor'?'selected':'' ?>>Gestor</option>

                    <option value="avaliador" <?= $user['role']=='avaliador'?'selected':'' ?>>Avaliador</option>

                </select>

                <label>Status</label>

                <select name="active">

                    <option value="1" <?= $user['active']?'selected':'' ?>>Ativo</option>

                    <option value="0" <?= !$user['active']?'selected':'' ?>>Inativo</option>

                </select>

                <button type="submit">

                    Salvar Alterações

                </button>

            </form>

        </div>

    </main>

</div>

</body>

</html>