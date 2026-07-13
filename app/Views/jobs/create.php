<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nova Vaga - <?= APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

</head>

<body>

<div class="dashboard">

    <aside class="sidebar">

        <div class="logo">
            <span>P</span>
        </div>

        <h2>Pulsar RH</h2>

        <nav>

            <a href="<?= BASE_URL ?>/dashboard">Dashboard</a>
            <a href="<?= BASE_URL ?>/companies">Empresas</a>
            <a href="<?= BASE_URL ?>/users">Usuários</a>
            <a class="active" href="<?= BASE_URL ?>/jobs">Vagas</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <h1>Nova Vaga</h1>

        <div class="card">

            <form method="POST" action="<?= BASE_URL ?>/jobs/store">

                <label>Empresa</label>

                <select name="company_id" required>

                    <option value="">Selecione uma empresa</option>

                    <?php foreach ($companies as $company): ?>

                        <option value="<?= $company['id'] ?>">
                            <?= htmlspecialchars($company['trade_name']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

                <label>Título da Vaga</label>
                <input type="text" name="title" required>

                <label>Departamento</label>
                <input type="text" name="department">

                <label>Modalidade</label>

                <select name="workplace">
                    <option value="Presencial">Presencial</option>
                    <option value="Híbrido">Híbrido</option>
                    <option value="Remoto">Remoto</option>
                </select>

                <label>Tipo de Contrato</label>

                <select name="contract_type">
                    <option value="CLT">CLT</option>
                    <option value="PJ">PJ</option>
                    <option value="Estágio">Estágio</option>
                    <option value="Temporário">Temporário</option>
                    <option value="Autônomo">Autônomo</option>
                </select>

                <label>Quantidade de Vagas</label>
                <input type="number" name="vacancies" value="1">

                <label>Salário</label>
                <input type="number" step="0.01" name="salary">

                <label>Cidade</label>
                <input type="text" name="city">

                <label>UF</label>
                <input type="text" name="state" maxlength="2">

                <label>Descrição</label>
                <textarea name="description" rows="5"></textarea>

                <label>Requisitos</label>
                <textarea name="requirements" rows="5"></textarea>

                <label>Benefícios</label>
                <textarea name="benefits" rows="5"></textarea>

                <label>Status</label>

                <select name="active">
                    <option value="1">Ativa</option>
                    <option value="0">Encerrada</option>
                </select>

                <button type="submit">
                    Salvar Vaga
                </button>

            </form>

        </div>

    </main>

</div>

</body>

</html>