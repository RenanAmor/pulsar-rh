<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Respostas - <?= APP_NAME ?></title>

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
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <div class="page-header">

            <h1>Respostas das Pesquisas</h1>

        </div>

        <p>
            Selecione uma pesquisa para aplicar (responder em nome de um
            colaborador) ou para visualizar as respostas já registradas.
        </p>

        <table class="table">

            <thead>

                <tr>
                    <th>Empresa</th>
                    <th>Pesquisa</th>
                    <th>Status</th>
                    <th>Respondentes</th>
                    <th width="220">Ações</th>
                </tr>

            </thead>

            <tbody>

            <?php foreach ($surveys as $survey): ?>

                <tr>

                    <td><?= htmlspecialchars($survey['company_name']) ?></td>
                    <td><?= htmlspecialchars($survey['title']) ?></td>
                    <td><?= htmlspecialchars($survey['status']) ?></td>
                    <td><?= (int) $survey['respondents_count'] ?></td>

                    <td>

                        <a class="btn-action edit"
                           href="<?= BASE_URL ?>/answers/start?survey_id=<?= $survey['id'] ?>">
                            📝 Responder
                        </a>

                        <a class="btn-action edit"
                           href="<?= BASE_URL ?>/answers/respondents?survey_id=<?= $survey['id'] ?>">
                            👥 Ver Respostas
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
