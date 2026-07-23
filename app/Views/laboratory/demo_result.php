<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="3;url=<?= $dashboardUrl ?>">

    <title>Demonstração Gerada | <?= APP_NAME ?></title>

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
            ['label' => 'Laboratório Organizacional', 'href' => BASE_URL . '/laboratory'],
            ['label' => 'Demonstração Gerada'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <div class="page-header">

            <h1>✅ Empresa de demonstração pronta</h1>

        </div>

        <div class="card">

            <h2><?= htmlspecialchars($result['company_name']) ?></h2>

            <p>Cenário: <strong><?= htmlspecialchars($result['label']) ?></strong></p>

            <section class="cards">

                <div class="card">
                    <h3>Colaboradores</h3>
                    <h2><?= $result['employees_count'] ?></h2>
                </div>

                <div class="card">
                    <h3>Respostas geradas</h3>
                    <h2><?= $result['answers_count'] ?></h2>
                </div>

                <div class="card">
                    <h3>Tempo de geração</h3>
                    <h2><?= number_format($result['duration_seconds'], 1) ?>s</h2>
                </div>

            </section>

            <p id="redirect-message">
                Redirecionando para o Dashboard Executivo em <span id="countdown">3</span>s…
                <a href="<?= $dashboardUrl ?>">Ir agora →</a>
            </p>

        </div>

    </main>

</div>

<script>
    var seconds = 3;
    var countdown = document.getElementById('countdown');

    var timer = setInterval(function () {
        seconds -= 1;

        if (countdown) {
            countdown.textContent = Math.max(seconds, 0);
        }

        if (seconds <= 0) {
            clearInterval(timer);
        }
    }, 1000);
</script>

</body>

</html>
