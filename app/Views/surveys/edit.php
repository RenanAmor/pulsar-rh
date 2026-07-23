<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Pesquisa | <?= APP_NAME ?></title>

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
            ['label' => 'Pesquisas'],
            ['label' => 'Pesquisas', 'href' => BASE_URL . '/surveys'],
            ['label' => 'Editar Pesquisa'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <h1>Editar Pesquisa</h1>

        <div class="card">

            <form method="POST" action="<?= BASE_URL ?>/surveys/update">

                <input
                    type="hidden"
                    name="id"
                    value="<?= $survey['id'] ?>"
                >

                <label>Empresa</label>
                <select name="company_id" required>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?= $company['id'] ?>" <?= (int) $survey['company_id'] === (int) $company['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($company['trade_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Título</label>
                <input type="text" name="title" value="<?= htmlspecialchars($survey['title']) ?>" required>

                <label>Descrição</label>
                <textarea name="description" rows="4"><?= htmlspecialchars($survey['description'] ?? '') ?></textarea>

                <label>Início do Período</label>
                <input type="date" name="start_date" value="<?= htmlspecialchars($survey['start_date'] ?? '') ?>">

                <label>Fim do Período</label>
                <input type="date" name="end_date" value="<?= htmlspecialchars($survey['end_date'] ?? '') ?>">

                <label>Pesquisa Anônima</label>
                <select name="anonymous">
                    <option value="1" <?= $survey['anonymous'] ? 'selected' : '' ?>>Sim</option>
                    <option value="0" <?= !$survey['anonymous'] ? 'selected' : '' ?>>Não</option>
                </select>

                <label>Status</label>
                <select name="status">
                    <?php foreach (['Rascunho', 'Agendada', 'Em andamento', 'Encerrada', 'Cancelada'] as $statusOption): ?>
                        <option value="<?= $statusOption ?>" <?= $survey['status'] === $statusOption ? 'selected' : '' ?>>
                            <?= $statusOption ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">
                    Salvar Alterações
                </button>

            </form>

        </div>

    </main>

</div>

</body>

</html>
