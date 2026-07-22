<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Montar Pesquisa - <?= APP_NAME ?></title>

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
            <a class="active" href="<?= BASE_URL ?>/survey-questions">Montagem de Pesquisas</a>
            <a href="<?= BASE_URL ?>/answers">Respostas</a>
            <a href="<?= BASE_URL ?>/indicators">Indicadores</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <div class="page-header">

            <h1>Montar Pesquisa: <?= htmlspecialchars($survey['title']) ?></h1>

            <a class="btn-primary" href="<?= BASE_URL ?>/survey-questions">
                ← Voltar
            </a>

        </div>

        <p>
            Empresa: <strong><?= htmlspecialchars($survey['company_name'] ?? '') ?></strong>
            &nbsp;|&nbsp;
            Status: <strong><?= htmlspecialchars($survey['status']) ?></strong>
        </p>

        <div class="card">

            <h2>Perguntas da Pesquisa</h2>

            <?php if (empty($surveyQuestions)): ?>

                <p>Nenhuma pergunta adicionada ainda.</p>

            <?php else: ?>

                <table class="table">

                    <thead>

                        <tr>
                            <th width="60">Ordem</th>
                            <th>Categoria</th>
                            <th>Dimensão</th>
                            <th>Pergunta</th>
                            <th>Tipo de Resposta</th>
                            <th>Obrigatória</th>
                            <th width="220">Ações</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php foreach ($surveyQuestions as $surveyQuestion): ?>

                        <tr>

                            <td><?= (int) $surveyQuestion['display_order'] ?></td>
                            <td><?= htmlspecialchars($surveyQuestion['category']) ?></td>
                            <td><?= htmlspecialchars($surveyQuestion['dimension']) ?></td>
                            <td><?= htmlspecialchars($surveyQuestion['question']) ?></td>
                            <td><?= htmlspecialchars($surveyQuestion['answer_type']) ?></td>
                            <td><?= $surveyQuestion['required'] ? 'Sim' : 'Não' ?></td>

                            <td>

                                <a class="btn-action edit"
                                   href="<?= BASE_URL ?>/survey-questions/move-up?id=<?= $surveyQuestion['id'] ?>&survey_id=<?= $survey['id'] ?>">
                                    ⬆️
                                </a>

                                <a class="btn-action edit"
                                   href="<?= BASE_URL ?>/survey-questions/move-down?id=<?= $surveyQuestion['id'] ?>&survey_id=<?= $survey['id'] ?>">
                                    ⬇️
                                </a>

                                <a class="btn-action edit"
                                   href="<?= BASE_URL ?>/survey-questions/toggle-required?id=<?= $surveyQuestion['id'] ?>&survey_id=<?= $survey['id'] ?>">
                                    🔁 <?= $surveyQuestion['required'] ? 'Tornar Opcional' : 'Tornar Obrigatória' ?>
                                </a>

                                <a class="btn-action delete"
                                   href="<?= BASE_URL ?>/survey-questions/remove?id=<?= $surveyQuestion['id'] ?>&survey_id=<?= $survey['id'] ?>"
                                   onclick="return confirm('Remover esta pergunta da pesquisa?')">
                                    🗑️ Remover
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            <?php endif; ?>

        </div>

        <div class="card">

            <h2>Perguntas Disponíveis na Biblioteca</h2>

            <?php if (empty($availableQuestions)): ?>

                <p>Não há perguntas disponíveis para adicionar.</p>

            <?php else: ?>

                <table class="table">

                    <thead>

                        <tr>
                            <th>Categoria</th>
                            <th>Dimensão</th>
                            <th>Pergunta</th>
                            <th>Tipo de Resposta</th>
                            <th width="120">Ações</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php foreach ($availableQuestions as $question): ?>

                        <tr>

                            <td><?= htmlspecialchars($question['category']) ?></td>
                            <td><?= htmlspecialchars($question['dimension']) ?></td>
                            <td><?= htmlspecialchars($question['question']) ?></td>
                            <td><?= htmlspecialchars($question['answer_type']) ?></td>

                            <td>

                                <form method="POST" action="<?= BASE_URL ?>/survey-questions/add">

                                    <input type="hidden" name="survey_id" value="<?= $survey['id'] ?>">
                                    <input type="hidden" name="question_id" value="<?= $question['id'] ?>">

                                    <button type="submit" class="btn-primary">
                                        + Adicionar
                                    </button>

                                </form>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            <?php endif; ?>

        </div>

    </main>

</div>

</body>

</html>
