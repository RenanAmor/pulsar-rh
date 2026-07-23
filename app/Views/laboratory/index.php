<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laboratório Organizacional | <?= APP_NAME ?></title>

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
            ['label' => 'Laboratório Organizacional'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <div class="page-header">

            <h1>Laboratório Organizacional</h1>

        </div>

        <p>
            Gere ambientes organizacionais sintéticos completos (empresa,
            filial, setores, equipes, cargos, colaboradores, pesquisas e
            respostas) para testes, demonstrações e validação do Motor de
            Indicadores. Nenhum dado real é utilizado.
        </p>

        <div class="card">

            <h2>Geração Rápida de Demonstração</h2>

            <p>
                Prefere um clique só? Escolha um cenário e vá direto para o
                Dashboard Executivo com os resultados prontos.
            </p>

            <a class="btn-primary" href="<?= BASE_URL ?>/laboratory/demo">
                🚀 Gerar Empresa de Demonstração
            </a>

        </div>

        <div class="card">

            <h2>Gerar Ambiente (avançado)</h2>

            <form method="POST" action="<?= BASE_URL ?>/laboratory/generate">

                <label>Cenário</label>
                <select name="scenario" required>
                    <?php foreach ($scenarios as $key => $label): ?>
                        <option value="<?= $key ?>"><?= htmlspecialchars($label) ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Quantidade de Colaboradores</label>
                <input type="number" name="employees_count" value="20" min="1" max="500" required>

                <label>Quantidade de Pesquisas</label>
                <input type="number" name="surveys_count" value="1" min="1" max="20" required>

                <label>Quantidade de Respondentes por Pesquisa</label>
                <input type="number" name="respondents_count" value="20" min="1" max="500" required>

                <button type="submit">
                    Gerar Ambiente
                </button>

            </form>

        </div>

        <div class="card">

            <h2>Ambientes Gerados</h2>

            <?php if (empty($generatedCompanies)): ?>

                <p>Nenhum ambiente sintético foi gerado ainda.</p>

            <?php else: ?>

                <table class="table">

                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>Cidade/UF</th>
                            <th width="160">Ações</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($generatedCompanies as $company): ?>

                        <tr>

                            <td><?= htmlspecialchars($company['trade_name']) ?></td>
                            <td><?= htmlspecialchars($company['city'] ?? '') ?> / <?= htmlspecialchars($company['state'] ?? '') ?></td>

                            <td>

                                <a class="btn-action delete"
                                   href="<?= BASE_URL ?>/laboratory/clear?company_id=<?= $company['id'] ?>"
                                   onclick="return confirm('Isso vai apagar permanentemente esta empresa sintética e todos os dados vinculados (filiais, setores, colaboradores, pesquisas e respostas). Continuar?')">
                                    🗑️ Limpar Ambiente
                                </a>

                            </td>

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
