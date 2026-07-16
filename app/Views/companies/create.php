<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nova Empresa - <?= APP_NAME ?></title>

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
            <a class="active" href="<?= BASE_URL ?>/companies">Empresas</a>
            <a href="<?= BASE_URL ?>/users">Usuários</a>
            <a href="<?= BASE_URL ?>/branches">Filiais</a>
            <a href="<?= BASE_URL ?>/departments">Setores</a>
            <a href="<?= BASE_URL ?>/positions">Cargos</a>
            <a href="<?= BASE_URL ?>/jobs">Vagas</a>
            <a href="<?= BASE_URL ?>/candidates">Candidatos</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <h1>Nova Empresa</h1>

        <div class="card">

            <form method="POST" action="<?= BASE_URL ?>/companies/store">

                <label>Razão Social</label>
                <input type="text" name="corporate_name" required>

                <label>Nome Fantasia</label>
                <input type="text" name="trade_name" required>

                <label>CNPJ</label>
                <input type="text" name="document" required>

                <label>E-mail</label>
                <input type="email" name="email">

                <label>Telefone</label>
                <input type="text" name="phone">

                <label>Website</label>
                <input type="text" name="website">

                <label>Ramo de Atividade</label>
                <input type="text" name="sector">

                <label>Porte</label>

                <select name="size">
                    <option value="Micro">Micro</option>
                    <option value="Pequena">Pequena</option>
                    <option value="Média">Média</option>
                    <option value="Grande">Grande</option>
                </select>

                <label>Número de Funcionários</label>
                <input type="number" name="employees" value="0">

                <label>Cidade</label>
                <input type="text" name="city">

                <label>UF</label>
                <input type="text" name="state" maxlength="2">

                <label>Status</label>

                <select name="active">
                    <option value="1">Ativa</option>
                    <option value="0">Inativa</option>
                </select>

                <button type="submit">

                    Salvar Empresa

                </button>

            </form>

        </div>

    </main>

</div>

</body>

</html>