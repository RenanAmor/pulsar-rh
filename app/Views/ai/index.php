<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inteligência Artificial | <?= APP_NAME ?></title>

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

        <?php $breadcrumb = [
            ['label' => 'Administração', 'href' => BASE_URL . '/administration'],
            ['label' => 'Sistema'],
            ['label' => 'Inteligência Artificial'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <div class="page-header">

            <h1>Inteligência Artificial</h1>

        </div>

        <p>
            A IA interpreta o contexto já construído pelo OIE (indicadores,
            padrões, riscos e recomendações) — ela não recalcula nada.
            Se nenhum provedor de IA estiver configurado, o sistema usa
            automaticamente as regras de negócio do OIE, sem interromper
            o funcionamento.
        </p>

        <div class="card">

            <h2>Provedores</h2>

            <table class="table">

                <thead>
                    <tr>
                        <th>Provedor</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($providers as $provider): ?>

                    <tr>
                        <td><?= htmlspecialchars($provider->name()) ?></td>
                        <td><?= $provider->isAvailable() ? '✅ Disponível' : '⚪ Não configurado' ?></td>
                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

        <div class="card">

            <h2>Gerar Análise</h2>

            <table class="table">

                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>Pesquisa</th>
                        <th width="160">Ações</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($surveys as $survey): ?>

                    <tr>

                        <td><?= htmlspecialchars($survey['company_name']) ?></td>
                        <td><?= htmlspecialchars($survey['title']) ?></td>

                        <td>

                            <form method="POST" action="<?= BASE_URL ?>/ai/generate">
                                <input type="hidden" name="survey_id" value="<?= $survey['id'] ?>">
                                <button type="submit" class="btn-primary">🤖 Analisar</button>
                            </form>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

        <div class="card">

            <h2>Análises Recentes (Histórico de Auditoria)</h2>

            <?php if (empty($executions)): ?>

                <p>Nenhuma análise foi gerada ainda.</p>

            <?php else: ?>

                <table class="table">

                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Provedor</th>
                            <th>Modelo</th>
                            <th>Tempo de Resposta</th>
                            <th>Custo Estimado</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($executions as $execution): ?>

                        <tr>
                            <td><?= htmlspecialchars($execution['timestamp'] ?? '') ?></td>
                            <td><?= htmlspecialchars($execution['provider'] ?? '') ?></td>
                            <td><?= htmlspecialchars($execution['model'] ?? '') ?></td>
                            <td><?= (int) ($execution['duration_ms'] ?? 0) ?> ms</td>
                            <td>$<?= number_format((float) ($execution['estimated_cost'] ?? 0), 6) ?></td>
                            <td><?= htmlspecialchars($execution['status'] ?? '') ?></td>
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
