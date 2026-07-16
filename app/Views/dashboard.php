<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | <?= APP_NAME ?></title>

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

            <a href="<?= BASE_URL ?>/companies">Empresas</a>

            <a href="<?= BASE_URL ?>/branches">Filiais</a>

            <a href="<?= BASE_URL ?>/departments">Setores</a>

            <a href="<?= BASE_URL ?>/positions">Cargos</a>

            <a href="<?= BASE_URL ?>/jobs">Vagas</a>

            <a href="<?= BASE_URL ?>/candidates">Candidatos</a>

            <a href="#">Avaliações</a>

            <a href="#">Relatórios</a>

            <a href="#">Configurações</a>

            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <header>

            <h1>Dashboard</h1>

            <p>

                Bem-vindo,
                <strong><?= htmlspecialchars($user['name']) ?></strong>

            </p>

        </header>

        <section class="cards">

            <div class="card">

                <h3>Usuários</h3>

                <h2>1</h2>

            </div>

            <div class="card">

                <h3>Empresas</h3>

                <h2>0</h2>

            </div>

            <div class="card">

                <h3>Vagas</h3>

                <h2>0</h2>

            </div>

            <div class="card">

                <h3>Filiais</h3>

                <h2>0</h2>

            </div>

            <div class="card">

                <h3>Setores</h3>

                <h2>0</h2>

            </div>

            <div class="card">

                <h3>Cargos</h3>

                <h2>0</h2>

            </div>

            <div class="card">

                <h3>Candidatos</h3>

                <h2>0</h2>

            </div>

        </section>

    </main>

</div>

</body>

</html>