<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Administração | <?= APP_NAME ?></title>

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

        <header>
            <h1>Administração</h1>
            <p>Cadastros e configurações do sistema, <strong><?= htmlspecialchars($user['name']) ?></strong>.</p>
        </header>

        <div class="card">

            <h2>Estrutura Organizacional</h2>

            <ul>
                <li><a class="btn-primary" href="<?= BASE_URL ?>/companies">Empresas</a></li>
                <li><a class="btn-primary" href="<?= BASE_URL ?>/branches">Filiais</a></li>
                <li><a class="btn-primary" href="<?= BASE_URL ?>/departments">Setores</a></li>
                <li><a class="btn-primary" href="<?= BASE_URL ?>/teams">Equipes</a></li>
                <li><a class="btn-primary" href="<?= BASE_URL ?>/positions">Cargos</a></li>
                <li><a class="btn-primary" href="<?= BASE_URL ?>/employees">Colaboradores</a></li>
                <li><a class="btn-primary" href="<?= BASE_URL ?>/users">Usuários</a></li>
            </ul>

        </div>

        <div class="card">

            <h2>Recrutamento &amp; Seleção</h2>

            <ul>
                <li><a class="btn-primary" href="<?= BASE_URL ?>/jobs">Vagas</a></li>
                <li><a class="btn-primary" href="<?= BASE_URL ?>/candidates">Candidatos</a></li>
            </ul>

        </div>

        <div class="card">

            <h2>Pesquisas &amp; Respostas</h2>

            <ul>
                <li><a class="btn-primary" href="<?= BASE_URL ?>/surveys">Pesquisas</a></li>
                <li><a class="btn-primary" href="<?= BASE_URL ?>/questions">Perguntas</a></li>
                <li><a class="btn-primary" href="<?= BASE_URL ?>/survey-questions">Montagem de Pesquisas</a></li>
                <li><a class="btn-primary" href="<?= BASE_URL ?>/answers">Respostas</a></li>
            </ul>

        </div>

        <div class="card">

            <h2>Motor de Inteligência (acesso técnico)</h2>

            <p>Telas detalhadas do Motor de Indicadores, do OIE e da configuração de provedores de IA, para uso técnico e de validação.</p>

            <ul>
                <li><a class="btn-primary" href="<?= BASE_URL ?>/indicators">Indicadores (detalhado)</a></li>
                <li><a class="btn-primary" href="<?= BASE_URL ?>/oie">OIE (detalhado)</a></li>
                <li><a class="btn-primary" href="<?= BASE_URL ?>/ai">Inteligência Artificial (provedores)</a></li>
                <li><a class="btn-primary" href="<?= BASE_URL ?>/laboratory">Laboratório Organizacional</a></li>
            </ul>

        </div>

    </main>

</div>

</body>

</html>
