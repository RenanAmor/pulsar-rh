<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Novo Usuário | <?= APP_NAME ?></title>

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

            <a href="#">Avaliações</a>

            <a href="#">Relatórios</a>

            <a href="#">Configurações</a>

            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <header>

            <h1>Novo Usuário</h1>

            <p>Cadastro de usuários do sistema.</p>

        </header>

        <div class="card">

            <form method="POST" action="<?= BASE_URL ?>/users/store">

                <div class="form-group">

                    <label>Nome</label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Nome completo"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>E-mail</label>

                    <input
                        type="email"
                        name="email"
                        placeholder="usuario@empresa.com"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Senha</label>

                    <input
                        type="password"
                        name="password"
                        placeholder="********"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Perfil</label>

                    <select name="role">

                        <option value="admin">Administrador</option>

                        <option value="rh">RH</option>

                        <option value="gestor">Gestor</option>

                        <option value="avaliador">Avaliador</option>

                    </select>

                </div>

                <div class="form-group">

                    <label>Status</label>

                    <select name="active">

                        <option value="1">Ativo</option>

                        <option value="0">Inativo</option>

                    </select>

                </div>

                <div style="display:flex;gap:15px;margin-top:30px;">

                    <button type="submit">

                        Salvar Usuário

                    </button>

                    <a
                        href="<?= BASE_URL ?>/users"
                        class="btn-secondary"
                    >

                        Cancelar

                    </a>

                </div>

            </form>

        </div>

    </main>

</div>

</body>

</html>