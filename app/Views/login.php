<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

</head>

<body>

<div class="login-page">

    <div class="background-particles"></div>

    <section class="left-panel">

        <div class="brand">

            <div class="logo">
                <span>P</span>
            </div>

            <h1>Pulsar RH</h1>

            <h2>Inteligência para Gestão de Pessoas</h2>

            <div class="gold-line"></div>

            <p>
                Plataforma desenvolvida para recrutamento,
                avaliação comportamental,
                gestão de talentos e
                desenvolvimento humano.
            </p>

        </div>

    </section>

    <section class="right-panel">

        <div class="login-card">

            <small>ACESSO AO SISTEMA</small>

            <h3>Bem-vindo</h3>

            <p>Entre com seu e-mail e senha.</p>

            <?php if (isset($error)): ?>

                <div class="error-message">
                    <?= htmlspecialchars($error) ?>
                </div>

            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/">

                <label>E-mail</label>

                <input
                    type="email"
                    name="email"
                    placeholder="Digite seu e-mail"
                    required
                >

                <label>Senha</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Digite sua senha"
                    required
                >

                <button type="submit">
                    Entrar
                </button>

            </form>

        </div>

    </section>

</div>

</body>

</html>