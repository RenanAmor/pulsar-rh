<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ambiente Gerado - <?= APP_NAME ?></title>

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
            <a href="<?= BASE_URL ?>/answers">Respostas</a>
            <a href="<?= BASE_URL ?>/indicators">Indicadores</a>
            <a class="active" href="<?= BASE_URL ?>/laboratory">Laboratório Organizacional</a>
            <a href="<?= BASE_URL ?>/oie">OIE</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <div class="page-header">

            <h1>Ambiente Gerado: <?= htmlspecialchars($result['label']) ?></h1>

            <a class="btn-primary" href="<?= BASE_URL ?>/laboratory">
                ← Voltar ao Laboratório
            </a>

        </div>

        <p>
            Empresa: <strong><?= htmlspecialchars($result['company_name']) ?></strong>
            &nbsp;|&nbsp;
            Colaboradores gerados: <strong><?= $result['employees_count'] ?></strong>
            &nbsp;|&nbsp;
            <a href="<?= BASE_URL ?>/employees">Ver colaboradores</a>
        </p>

        <?php foreach ($result['surveys'] as $surveyResult): ?>

            <?php $indicators = $surveyResult['indicators']; ?>

            <div class="card">

                <h2>
                    Pesquisa #<?= $surveyResult['survey_id'] ?>
                    — <?= $surveyResult['respondents'] ?> respondente(s)
                </h2>

                <a href="<?= BASE_URL ?>/indicators/show?survey_id=<?= $surveyResult['survey_id'] ?>">
                    Ver indicadores completos desta pesquisa →
                </a>

                <?php if (isset($indicators['error'])): ?>

                    <p><?= htmlspecialchars($indicators['error']) ?></p>

                <?php else: ?>

                    <section class="cards">

                        <div class="card">
                            <h3>Índice Geral</h3>
                            <h2><?= $indicators['final_score'] !== null ? number_format($indicators['final_score'], 2) : '—' ?></h2>
                        </div>

                        <div class="card">
                            <h3>Classificação</h3>
                            <h2><?= htmlspecialchars($indicators['classification']) ?></h2>
                        </div>

                        <div class="card">
                            <h3>Participação</h3>
                            <h2><?= number_format($indicators['participation']['percentage'], 2) ?>%</h2>
                        </div>

                        <div class="card">
                            <h3>Risco de Burnout</h3>
                            <h2><?= number_format($indicators['burnout_risk'], 2) ?></h2>
                        </div>

                    </section>

                    <table class="table">

                        <thead>
                            <tr>
                                <th>Categoria</th>
                                <th>Média (0-100)</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($indicators['categories'] as $category): ?>

                            <tr>
                                <td><?= htmlspecialchars($category['category']) ?></td>
                                <td><?= $category['average'] !== null ? number_format($category['average'], 2) : '—' ?></td>
                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                <?php endif; ?>

            </div>

        <?php endforeach; ?>

    </main>

</div>

</body>

</html>
