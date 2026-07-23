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

<?php $activeNav = 'administration'; require __DIR__ . '/../partials/nav.php'; ?>

    </aside>

    <main class="content">

        <?php $breadcrumb = [
            ['label' => 'Administração', 'href' => BASE_URL . '/administration'],
            ['label' => 'Organização'],
            ['label' => 'Usuários', 'href' => BASE_URL . '/users'],
            ['label' => 'Novo Usuário'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

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