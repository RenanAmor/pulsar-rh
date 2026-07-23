<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Respondentes - <?= APP_NAME ?></title>

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

        <div class="page-header">

            <h1>Respondentes: <?= htmlspecialchars($survey['title']) ?></h1>

            <a class="btn-primary" href="<?= BASE_URL ?>/answers">
                ← Voltar
            </a>

        </div>

        <p>
            Total de perguntas da pesquisa: <strong><?= $totalQuestions ?></strong>
        </p>

        <?php if (empty($respondents)): ?>

            <p>Nenhum colaborador respondeu esta pesquisa ainda.</p>

        <?php else: ?>

            <table class="table">

                <thead>

                    <tr>
                        <th>Colaborador</th>
                        <th>Perguntas Respondidas</th>
                        <th>Última Resposta</th>
                        <th width="120">Ações</th>
                    </tr>

                </thead>

                <tbody>

                <?php foreach ($respondents as $respondent): ?>

                    <tr>

                        <td><?= htmlspecialchars($respondent['employee_name']) ?></td>
                        <td><?= (int) $respondent['answered_count'] ?> / <?= $totalQuestions ?></td>
                        <td><?= htmlspecialchars($respondent['last_answered_at'] ?? '') ?></td>

                        <td>

                            <a class="btn-action edit"
                               href="<?= BASE_URL ?>/answers/view?survey_id=<?= $survey['id'] ?>&employee_id=<?= $respondent['employee_id'] ?>">
                                👁️ Ver
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        <?php endif; ?>

    </main>

</div>

</body>

</html>
