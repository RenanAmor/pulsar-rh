<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>OIE - <?= APP_NAME ?></title>

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
            <a href="<?= BASE_URL ?>/laboratory">Laboratório Organizacional</a>
            <a class="active" href="<?= BASE_URL ?>/oie">OIE</a>
            <a href="<?= BASE_URL ?>/ai">Inteligência Artificial</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <div class="page-header">

            <h1>OIE: <?= htmlspecialchars($survey['title']) ?></h1>

            <a class="btn-primary" href="<?= BASE_URL ?>/oie">
                ← Voltar
            </a>

        </div>

        <?php if (isset($context['error'])): ?>

            <p><?= htmlspecialchars($context['error']) ?></p>

        <?php else: ?>

            <p>
                Empresa: <strong><?= htmlspecialchars($context['organization']['company_name'] ?? '') ?></strong>
                &nbsp;|&nbsp;
                Contexto gerado em: <?= htmlspecialchars($context['generated_at']) ?>
            </p>

            <section class="cards">

                <div class="card">
                    <h3>Índice Geral</h3>
                    <h2><?= $context['indicators']['final_score'] !== null ? number_format($context['indicators']['final_score'], 2) : '—' ?></h2>
                </div>

                <div class="card">
                    <h3>Classificação</h3>
                    <h2><?= htmlspecialchars($context['indicators']['classification']) ?></h2>
                </div>

                <div class="card">
                    <h3>Participação</h3>
                    <h2><?= number_format($context['indicators']['participation']['percentage'], 2) ?>%</h2>
                </div>

                <div class="card">
                    <h3>Tendência Geral</h3>
                    <h2><?= htmlspecialchars($context['patterns']['overall']) ?></h2>
                </div>

            </section>

            <div class="card">

                <h2>Riscos Detectados</h2>

                <?php if (empty($context['risks'])): ?>

                    <p>Nenhum risco organizacional foi detectado para esta pesquisa.</p>

                <?php else: ?>

                    <table class="table">

                        <thead>
                            <tr>
                                <th>Risco</th>
                                <th>Severidade</th>
                                <th>Valor de Referência</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($context['risks'] as $risk): ?>

                            <tr>
                                <td><?= htmlspecialchars($risk['label']) ?></td>
                                <td><?= htmlspecialchars($risk['severity']) ?></td>
                                <td><?= number_format((float) $risk['value'], 2) ?></td>
                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                <?php endif; ?>

            </div>

            <div class="card">

                <h2>Recomendações</h2>

                <?php if (empty($context['recommendations'])): ?>

                    <p>Nenhuma recomendação gerada — os indicadores estão dentro do esperado.</p>

                <?php else: ?>

                    <table class="table">

                        <thead>
                            <tr>
                                <th>Recomendação</th>
                                <th>Motivo</th>
                                <th>Prioridade</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($context['recommendations'] as $recommendation): ?>

                            <tr>
                                <td><?= htmlspecialchars($recommendation['title']) ?></td>
                                <td><?= htmlspecialchars($recommendation['reason']) ?></td>
                                <td><?= htmlspecialchars($recommendation['priority']) ?></td>
                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                <?php endif; ?>

            </div>

            <div class="card">

                <h2>Padrões (Comparação com a Pesquisa Anterior)</h2>

                <?php if (!$context['patterns']['has_previous']): ?>

                    <p><?= htmlspecialchars($context['patterns']['overall']) ?></p>

                <?php else: ?>

                    <table class="table">

                        <thead>
                            <tr>
                                <th>Indicador</th>
                                <th>Atual</th>
                                <th>Anterior</th>
                                <th>Variação</th>
                                <th>Padrão</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($context['patterns']['dimensions'] as $key => $dimension): ?>

                            <tr>
                                <td><?= htmlspecialchars($key) ?></td>
                                <td><?= number_format($dimension['current'], 2) ?></td>
                                <td><?= number_format($dimension['previous'], 2) ?></td>
                                <td><?= number_format($dimension['delta'], 2) ?></td>
                                <td><?= htmlspecialchars($dimension['classification']) ?></td>
                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                <?php endif; ?>

            </div>

            <div class="card">

                <h2>Evolução Temporal</h2>

                <?php if (empty($context['history'])): ?>

                    <p>Ainda não há histórico suficiente para comparação temporal.</p>

                <?php else: ?>

                    <table class="table">

                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Índice Geral</th>
                                <th>Classificação</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($context['history'] as $snapshot): ?>

                            <tr>
                                <td><?= htmlspecialchars($snapshot['created_at']) ?></td>
                                <td><?= number_format((float) $snapshot['final_score'], 2) ?></td>
                                <td><?= htmlspecialchars($snapshot['classification']) ?></td>
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
