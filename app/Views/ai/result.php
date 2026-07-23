<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Análise Gerada - <?= APP_NAME ?></title>

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
            <a href="<?= BASE_URL ?>/oie">OIE</a>
            <a class="active" href="<?= BASE_URL ?>/ai">Inteligência Artificial</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <div class="page-header">

            <h1>Análise: <?= htmlspecialchars($survey['title'] ?? '') ?></h1>

            <a class="btn-primary" href="<?= BASE_URL ?>/ai">
                ← Voltar
            </a>

        </div>

        <?php if (isset($result['error'])): ?>

            <p><?= htmlspecialchars($result['error']) ?></p>

        <?php else: ?>

            <p>
                Provedor: <strong><?= htmlspecialchars($result['provider']) ?></strong>
                &nbsp;|&nbsp;
                Modelo: <strong><?= htmlspecialchars($result['model']) ?></strong>
                &nbsp;|&nbsp;
                Tempo de resposta: <strong><?= $result['duration_ms'] ?> ms</strong>
                &nbsp;|&nbsp;
                Custo estimado: <strong>$<?= number_format($result['estimated_cost'], 6) ?></strong>
            </p>

            <?php if (!$result['validation']['valid']): ?>
                <p>
                    ⚠️ A resposta original não passou na validação
                    (<?= htmlspecialchars(implode('; ', $result['validation']['errors'])) ?>)
                    e o sistema utilizou o fallback baseado em regras do OIE.
                </p>
            <?php endif; ?>

            <div class="card">

                <h2>Resumo Executivo</h2>

                <p><?= nl2br(htmlspecialchars($result['parsed']['executive_summary'])) ?></p>

            </div>

            <?php if (!empty($result['parsed']['detailed_analysis'])): ?>

                <div class="card">

                    <h2>Análise Detalhada</h2>

                    <p><?= nl2br(htmlspecialchars($result['parsed']['detailed_analysis'])) ?></p>

                </div>

            <?php endif; ?>

            <div class="card">

                <h2>Oportunidades</h2>

                <?php if (empty($result['parsed']['opportunities'])): ?>
                    <p>Nenhuma oportunidade identificada.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($result['parsed']['opportunities'] as $item): ?>
                            <li><?= htmlspecialchars($item) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

            </div>

            <div class="card">

                <h2>Riscos</h2>

                <?php if (empty($result['parsed']['risks'])): ?>
                    <p>Nenhum risco identificado.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($result['parsed']['risks'] as $item): ?>
                            <li><?= htmlspecialchars($item) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

            </div>

            <div class="card">

                <h2>Recomendações</h2>

                <?php if (empty($result['parsed']['recommendations'])): ?>
                    <p>Nenhuma recomendação gerada.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($result['parsed']['recommendations'] as $item): ?>
                            <li><?= htmlspecialchars($item) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

            </div>

            <?php if ($result['ai_report_id']): ?>
                <p>✅ Relatório salvo (ai_reports #<?= $result['ai_report_id'] ?>).</p>
            <?php endif; ?>

        <?php endif; ?>

    </main>

</div>

</body>

</html>
