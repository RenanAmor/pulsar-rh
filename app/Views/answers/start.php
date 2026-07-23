<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar Pesquisa | <?= APP_NAME ?></title>

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
            ['label' => 'Respostas', 'href' => BASE_URL . '/answers'],
            ['label' => 'Iniciar Pesquisa'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <h1>Iniciar Pesquisa</h1>

        <div class="card">

            <p>
                <strong>Pesquisa:</strong> <?= htmlspecialchars($survey['title']) ?><br>
                <strong>Empresa:</strong> <?= htmlspecialchars($survey['company_name'] ?? '') ?><br>
                <strong>Anônima:</strong> <?= $survey['anonymous'] ? 'Sim' : 'Não' ?>
            </p>

            <?php if (!empty($survey['description'])): ?>
                <p><?= nl2br(htmlspecialchars($survey['description'])) ?></p>
            <?php endif; ?>

            <?php if (empty($companyEmployees)): ?>

                <p>
                    Não há colaboradores ativos cadastrados nesta empresa
                    para responder à pesquisa.
                </p>

            <?php else: ?>

                <form method="POST" action="<?= BASE_URL ?>/answers/begin">

                    <input type="hidden" name="survey_id" value="<?= $survey['id'] ?>">

                    <label>Colaborador Participante</label>
                    <select name="employee_id" required>
                        <option value="">Selecione o colaborador</option>
                        <?php foreach ($companyEmployees as $employee): ?>
                            <option value="<?= $employee['id'] ?>">
                                <?= htmlspecialchars($employee['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit">
                        Iniciar Pesquisa
                    </button>

                </form>

            <?php endif; ?>

        </div>

    </main>

</div>

</body>

</html>
