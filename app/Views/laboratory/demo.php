<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gerar Empresa de Demonstração | <?= APP_NAME ?></title>

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
            ['label' => 'Gerar Demonstração'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <div class="page-header">

            <h1>Gerar Empresa de Demonstração</h1>

        </div>

        <p>
            Escolha um cenário e clique no botão abaixo. O Laboratório vai criar
            uma empresa completa — filial, setores, equipes, cargos, colaboradores,
            pesquisa e respostas — rodar o Motor de Indicadores e te levar direto
            para o Dashboard Executivo com os resultados prontos.
        </p>

        <?php if ($noActiveQuestions): ?>

            <div class="card">
                <h2>Biblioteca Psicométrica vazia</h2>
                <p>
                    Não há nenhuma pergunta ativa cadastrada, então a demonstração
                    não teria respostas para gerar. Cadastre perguntas em
                    <a href="<?= BASE_URL ?>/questions">Administração → Perguntas</a>
                    antes de gerar uma empresa de demonstração.
                </p>
            </div>

        <?php endif; ?>

        <?php if ($currentDemo): ?>

            <div class="card">
                <p>
                    Demonstração atual: <strong><?= htmlspecialchars($currentDemo['trade_name']) ?></strong>
                    — gerar novamente vai substituí-la automaticamente.
                </p>
            </div>

        <?php endif; ?>

        <div class="card">

            <h2>Cenário</h2>

            <form method="POST" action="<?= BASE_URL ?>/laboratory/demo/generate">

                <label>Cenário</label>
                <select name="scenario" required <?= $noActiveQuestions ? 'disabled' : '' ?>>
                    <?php foreach ($scenarios as $key => $label): ?>
                        <option value="<?= $key ?>" <?= $key === 'saudavel' ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" <?= $noActiveQuestions ? 'disabled' : '' ?>>
                    🚀 Gerar Empresa de Demonstração
                </button>

            </form>

        </div>

        <p>
            Precisa de mais controle (volume de colaboradores, número de pesquisas)?
            Use a <a href="<?= BASE_URL ?>/laboratory">ferramenta avançada do Laboratório</a>.
        </p>

    </main>

</div>

</body>

</html>
