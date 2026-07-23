<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Histórico | <?= APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

</head>

<body>

<div class="dashboard">

    <aside class="sidebar">

        <div class="logo">
            <span>P</span>
        </div>

        <h2><?= APP_NAME ?></h2>

<?php $activeNav = 'history'; require __DIR__ . '/../partials/nav.php'; ?>

    </aside>

    <main class="content">

        <div class="page-header">
            <h1>Histórico<?= $company ? ' — ' . htmlspecialchars($company['trade_name']) : '' ?></h1>
        </div>

        <?php if (empty($snapshots)): ?>

            <div class="card">
                <p>Ainda não há histórico suficiente para exibir a evolução organizacional.</p>
            </div>

        <?php else: ?>

            <div class="card">

                <h2>Evolução do Índice Geral</h2>

                <table class="table">

                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Índice Geral</th>
                            <th>Classificação</th>
                            <th>Tendência</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($snapshots as $i => $snapshot): ?>

                            <?php $previous = $snapshots[$i + 1] ?? null; ?>

                            <tr>
                                <td><?= htmlspecialchars($snapshot['created_at']) ?></td>
                                <td><?= number_format((float) $snapshot['final_score'], 2) ?></td>
                                <td><?= htmlspecialchars($snapshot['classification']) ?></td>
                                <td>
                                    <?php if ($previous === null): ?>
                                        —
                                    <?php else: ?>
                                        <?= ((float) $snapshot['final_score']) >= ((float) $previous['final_score']) ? '↑' : '↓' ?>
                                    <?php endif; ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </main>

</div>

</body>

</html>
