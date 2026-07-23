<?php

$scopeLabels = [
    'branch'     => 'Filial',
    'department' => 'Setor',
    'team'       => 'Equipe',
];

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Evolução | <?= APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

</head>

<body>

<div class="dashboard">

    <aside class="sidebar">

        <div class="logo">
            <span>P</span>
        </div>

        <h2><?= APP_NAME ?></h2>

<?php $activeNav = 'intelligence'; require __DIR__ . '/../partials/nav.php'; ?>

    </aside>

    <main class="content">

        <?php $breadcrumb = [
            ['label' => 'Centro de Inteligência Organizacional', 'href' => BASE_URL . '/intelligence'],
            ['label' => 'Evolução'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <?php if (isset($center['error'])): ?>

            <div class="card">
                <h2>Ainda não há dados suficientes</h2>
                <p><?= htmlspecialchars($center['error']) ?></p>
            </div>

        <?php else: ?>

            <?php require __DIR__ . '/../partials/intelligence-header.php'; ?>

            <?php require __DIR__ . '/../partials/intelligence-subnav.php'; ?>

            <div class="card">

                <h2>Evolução do Índice Geral — Empresa</h2>

                <?php if (!$center['evolution']['hasHistory']): ?>

                    <p>Ainda não há histórico suficiente para exibir a evolução organizacional.</p>

                <?php else: ?>

                    <p class="intel-meta">Tendência: <strong><?= htmlspecialchars($center['evolution']['trend']) ?></strong></p>

                    <table class="table">

                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Índice Geral</th>
                                <th>Classificação</th>
                                <th>Variação</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($center['evolution']['series'] as $snapshot): ?>

                                <tr>
                                    <td><?= htmlspecialchars($snapshot['date']) ?></td>
                                    <td><?= number_format($snapshot['score'], 2) ?></td>
                                    <td><?= htmlspecialchars($snapshot['classification'] ?? '—') ?></td>
                                    <td><?= $snapshot['arrow'] ?? '—' ?></td>
                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                <?php endif; ?>

            </div>

            <div class="card">

                <h2>Comparar por Filial, Setor ou Equipe</h2>

                <form method="get" class="scope-filter">

                    <select name="entity">

                        <option value="">Selecione uma filial, setor ou equipe</option>

                        <?php if (!empty($scopeOptions['branch'])): ?>
                            <optgroup label="Filiais">
                                <?php foreach ($scopeOptions['branch'] as $branch): ?>
                                    <option value="branch:<?= $branch['id'] ?>" <?= ($scopeType === 'branch' && $entityId === (int) $branch['id']) ? 'selected' : '' ?>><?= htmlspecialchars($branch['name']) ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endif; ?>

                        <?php if (!empty($scopeOptions['department'])): ?>
                            <optgroup label="Setores">
                                <?php foreach ($scopeOptions['department'] as $department): ?>
                                    <option value="department:<?= $department['id'] ?>" <?= ($scopeType === 'department' && $entityId === (int) $department['id']) ? 'selected' : '' ?>><?= htmlspecialchars($department['name']) ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endif; ?>

                        <?php if (!empty($scopeOptions['team'])): ?>
                            <optgroup label="Equipes">
                                <?php foreach ($scopeOptions['team'] as $team): ?>
                                    <option value="team:<?= $team['id'] ?>" <?= ($scopeType === 'team' && $entityId === (int) $team['id']) ? 'selected' : '' ?>><?= htmlspecialchars($team['name']) ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endif; ?>

                    </select>

                    <button type="submit" class="btn-primary">Visualizar</button>

                </form>

                <?php if ($scopeType !== null && $entityId !== null): ?>

                    <?php if ($comparison === null): ?>

                        <p>Não há dados suficientes para este recorte.</p>

                    <?php else: ?>

                        <section class="cards">

                            <div class="card">
                                <h3><?= htmlspecialchars($scopeLabels[$scopeType] ?? 'Recorte') ?></h3>
                                <h2><?= $comparison['result']['final_score'] !== null ? number_format((float) $comparison['result']['final_score'], 2) : '—' ?></h2>
                            </div>

                            <div class="card">
                                <h3>Classificação</h3>
                                <h2><?= htmlspecialchars($comparison['result']['classification'] ?? '—') ?></h2>
                            </div>

                            <div class="card">
                                <h3>Participação</h3>
                                <h2><?= isset($comparison['result']['participation']['percentage']) ? number_format((float) $comparison['result']['participation']['percentage'], 0) . '%' : '—' ?></h2>
                            </div>

                        </section>

                    <?php endif; ?>

                <?php endif; ?>

            </div>

        <?php endif; ?>

    </main>

</div>

</body>

</html>
