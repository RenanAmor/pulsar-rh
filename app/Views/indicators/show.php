<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Indicadores - <?= APP_NAME ?></title>

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
            <a class="active" href="<?= BASE_URL ?>/indicators">Indicadores</a>
            <a href="<?= BASE_URL ?>/laboratory">Laboratório Organizacional</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <div class="page-header">

            <h1>Indicadores: <?= htmlspecialchars($survey['title']) ?></h1>

            <a class="btn-primary" href="<?= BASE_URL ?>/indicators">
                ← Voltar
            </a>

        </div>

        <?php if (isset($result['error'])): ?>

            <p><?= htmlspecialchars($result['error']) ?></p>

        <?php else: ?>

            <section class="cards">

                <div class="card">
                    <h3>Índice Geral</h3>
                    <h2><?= $result['final_score'] !== null ? number_format($result['final_score'], 2) : '—' ?></h2>
                </div>

                <div class="card">
                    <h3>Classificação</h3>
                    <h2><?= htmlspecialchars($result['classification']) ?></h2>
                </div>

                <div class="card">
                    <h3>Participação</h3>
                    <h2><?= number_format($result['participation']['percentage'], 2) ?>%</h2>
                </div>

                <div class="card">
                    <h3>Risco de Burnout</h3>
                    <h2><?= number_format($result['burnout_risk'], 2) ?></h2>
                </div>

            </section>

            <p>
                Respondentes: <strong><?= $result['participation']['responded_employees'] ?></strong>
                de <strong><?= $result['participation']['eligible_employees'] ?></strong>
                colaboradores ativos elegíveis
                &nbsp;|&nbsp;
                Respostas computadas: <strong><?= $result['responses_count'] ?></strong>
                <?php if ($result['persisted']): ?>
                    &nbsp;|&nbsp; ✅ Snapshot salvo no Índice de Saúde Organizacional
                <?php endif; ?>
            </p>

            <div class="card">

                <h2>Indicadores por Categoria</h2>

                <table class="table">

                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th>Média (0-100)</th>
                            <th>Respostas</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($result['categories'] as $category): ?>

                        <tr>
                            <td><?= htmlspecialchars($category['category']) ?></td>
                            <td><?= $category['average'] !== null ? number_format($category['average'], 2) : '—' ?></td>
                            <td><?= $category['responses_count'] ?></td>
                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

            <div class="card">

                <h2>Indicadores por Dimensão (Biblioteca Psicométrica)</h2>

                <table class="table">

                    <thead>
                        <tr>
                            <th>Dimensão</th>
                            <th>Média (0-100)</th>
                            <th>Respostas</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($result['dimensions'] as $dimension): ?>

                        <tr>
                            <td><?= htmlspecialchars($dimension['dimension']) ?></td>
                            <td><?= $dimension['average'] !== null ? number_format($dimension['average'], 2) : '—' ?></td>
                            <td><?= $dimension['responses_count'] ?></td>
                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

            <div class="card">

                <h2>Evolução Temporal (Comparação entre Pesquisas)</h2>

                <?php if (empty($history)): ?>

                    <p>Ainda não há histórico suficiente para comparação temporal.</p>

                <?php else: ?>

                    <table class="table">

                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Índice Geral</th>
                                <th>Classificação</th>
                                <th>Liderança</th>
                                <th>Comunicação</th>
                                <th>Engajamento</th>
                                <th>Bem-estar</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($history as $snapshot): ?>

                            <tr>
                                <td><?= htmlspecialchars($snapshot['created_at']) ?></td>
                                <td><?= number_format((float) $snapshot['final_score'], 2) ?></td>
                                <td><?= htmlspecialchars($snapshot['classification']) ?></td>
                                <td><?= number_format((float) $snapshot['leadership'], 2) ?></td>
                                <td><?= number_format((float) $snapshot['communication'], 2) ?></td>
                                <td><?= number_format((float) $snapshot['engagement'], 2) ?></td>
                                <td><?= number_format((float) $snapshot['wellbeing'], 2) ?></td>
                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                <?php endif; ?>

            </div>

        <?php endif; ?>

    </main>

</div>

</body>

</html>
