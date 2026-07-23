<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Novo Candidato | <?= APP_NAME ?></title>

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
            ['label' => 'Recrutamento & Seleção'],
            ['label' => 'Candidatos', 'href' => BASE_URL . '/candidates'],
            ['label' => 'Novo Candidato'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <div class="page-header">

            <h1>Novo Candidato</h1>

        </div>

        <form method="POST" action="<?= BASE_URL ?>/candidates/store">

            <label>Empresa</label>

            <select name="company_id" required>

                <?php foreach ($companies as $company): ?>

                    <option value="<?= $company['id'] ?>">
                        <?= htmlspecialchars($company['name']) ?>
                    </option>

                <?php endforeach; ?>

            </select>

            <label>Vaga</label>

            <select name="job_id">

                <option value="">Selecione</option>

                <?php foreach ($jobs as $job): ?>

                    <option value="<?= $job['id'] ?>">
                        <?= htmlspecialchars($job['title']) ?>
                    </option>

                <?php endforeach; ?>

            </select>

            <label>Nome</label>
            <input type="text" name="name" required>

            <label>E-mail</label>
            <input type="email" name="email">

            <label>Telefone</label>
            <input type="text" name="phone">

            <button type="submit">
                Salvar Candidato
            </button>

        </form>

    </main>

</div>

</body>

</html>