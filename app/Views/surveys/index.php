<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pesquisas - <?= APP_NAME ?></title>

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
            <a class="active" href="<?= BASE_URL ?>/surveys">Pesquisas</a>
            <a href="<?= BASE_URL ?>/questions">Perguntas</a>
            <a href="<?= BASE_URL ?>/survey-questions">Montagem de Pesquisas</a>
            <a href="<?= BASE_URL ?>/answers">Respostas</a>
            <a href="<?= BASE_URL ?>/indicators">Indicadores</a>
            <a href="<?= BASE_URL ?>/laboratory">Laboratório Organizacional</a>
            <a href="<?= BASE_URL ?>/oie">OIE</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <div class="page-header">

            <h1>Pesquisas</h1>

            <a class="btn-primary" href="<?= BASE_URL ?>/surveys/create">
                + Nova Pesquisa
            </a>

        </div>

        <table class="table">

            <thead>

                <tr>
                    <th>Empresa</th>
                    <th>Título</th>
                    <th>Período</th>
                    <th>Anônima</th>
                    <th>Status</th>
                    <th width="180">Ações</th>
                </tr>

            </thead>

            <tbody>

            <?php foreach ($surveys as $survey): ?>

                <tr>

                    <td><?= htmlspecialchars($survey['company_name']) ?></td>
                    <td><?= htmlspecialchars($survey['title']) ?></td>
                    <td>
                        <?= htmlspecialchars($survey['start_date'] ?? '') ?>
                        —
                        <?= htmlspecialchars($survey['end_date'] ?? '') ?>
                    </td>
                    <td><?= $survey['anonymous'] ? 'Sim' : 'Não' ?></td>
                    <td><?= htmlspecialchars($survey['status']) ?></td>

                    <td>

                        <a class="btn-action edit"
                           href="<?= BASE_URL ?>/surveys/edit?id=<?= $survey['id'] ?>">
                            ✏️ Editar
                        </a>

                        <a class="btn-action edit"
                           href="<?= BASE_URL ?>/survey-questions/manage?survey_id=<?= $survey['id'] ?>">
                            🧩 Montar
                        </a>

                        <a class="btn-action delete"
                           href="<?= BASE_URL ?>/surveys/delete?id=<?= $survey['id'] ?>"
                           onclick="return confirm('Deseja realmente excluir esta pesquisa?')">
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
