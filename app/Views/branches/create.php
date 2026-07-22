<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nova Filial - <?= APP_NAME ?></title>

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
            <a href="<?= BASE_URL ?>/users">Usuários</a>
            <a href="<?= BASE_URL ?>/companies">Empresas</a>
            <a class="active" href="<?= BASE_URL ?>/branches">Filiais</a>
            <a href="<?= BASE_URL ?>/departments">Setores</a>
            <a href="<?= BASE_URL ?>/teams">Equipes</a>
            <a href="<?= BASE_URL ?>/positions">Cargos</a>
            <a href="<?= BASE_URL ?>/employees">Colaboradores</a>
            <a href="<?= BASE_URL ?>/jobs">Vagas</a>
            <a href="<?= BASE_URL ?>/candidates">Candidatos</a>
            <a href="<?= BASE_URL ?>/surveys">Pesquisas</a>
            <a href="<?= BASE_URL ?>/questions">Perguntas</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <h1>Nova Filial</h1>

        <div class="card">

            <form method="POST" action="<?= BASE_URL ?>/branches/store">

                <label>Empresa</label>
                <select name="company_id" required>
                    <option value="">Selecione uma empresa</option>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?= $company['id'] ?>">
                            <?= htmlspecialchars($company['trade_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Nome da Filial</label>
                <input type="text" name="name" required>

                <label>CNPJ</label>
                <input type="text" name="document" required>

                <label>E-mail</label>
                <input type="email" name="email">

                <label>Telefone</label>
                <input type="text" name="phone">

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
                    Salvar Filial
                </button>

            </form>

        </div>

    </main>

</div>

</body>

</html>
