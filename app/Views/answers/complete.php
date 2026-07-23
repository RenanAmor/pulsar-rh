<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pesquisa Concluída - <?= APP_NAME ?></title>

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
            <a href="<?= BASE_URL ?>/users">Usuários</a>
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
            <a class="active" href="<?= BASE_URL ?>/answers">Respostas</a>
            <a href="<?= BASE_URL ?>/indicators">Indicadores</a>
            <a href="<?= BASE_URL ?>/laboratory">Laboratório Organizacional</a>
            <a href="<?= BASE_URL ?>/oie">OIE</a>
            <a href="<?= BASE_URL ?>/ai">Inteligência Artificial</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <h1>Pesquisa Concluída</h1>

        <div class="card">

            <p>
                Obrigado, <strong><?= htmlspecialchars($employee['name']) ?></strong>!
            </p>

            <p>
                Suas respostas para a pesquisa
                <strong><?= htmlspecialchars($survey['title']) ?></strong>
                foram registradas.
            </p>

            <p>
                Perguntas respondidas: <strong><?= $answeredQuestions ?> / <?= $totalQuestions ?></strong>
            </p>

            <a class="btn-primary" href="<?= BASE_URL ?>/answers">
                Voltar às Pesquisas
            </a>

        </div>

    </main>

</div>

</body>

</html>
