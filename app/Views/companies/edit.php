<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Empresa - <?= APP_NAME ?></title>

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
            <a href="<?= BASE_URL ?>/teams">Equipes</a>
            <a href="<?= BASE_URL ?>/positions">Cargos</a>
            <a href="<?= BASE_URL ?>/employees">Colaboradores</a>
            <a href="<?= BASE_URL ?>/jobs">Vagas</a>
            <a href="<?= BASE_URL ?>/candidates">Candidatos</a>
            <a href="<?= BASE_URL ?>/surveys">Pesquisas</a>
            <a href="<?= BASE_URL ?>/questions">Perguntas</a>
            <a href="<?= BASE_URL ?>/survey-questions">Montagem de Pesquisas</a>
            <a href="<?= BASE_URL ?>/answers">Respostas</a>
            <a href="<?= BASE_URL ?>/indicators">Indicadores</a>
            <a href="<?= BASE_URL ?>/laboratory">Laboratório Organizacional</a>
            <a href="<?= BASE_URL ?>/oie">OIE</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <h1>Editar Empresa</h1>

        <div class="card">

            <form method="POST" action="<?= BASE_URL ?>/companies/update">

                <input
                    type="hidden"
                    name="id"
                    value="<?= $company['id'] ?>"
                >

                <label>Razão Social</label>
                <input type="text" name="corporate_name" value="<?= htmlspecialchars($company['corporate_name']) ?>" required>

                <label>Nome Fantasia</label>
                <input type="text" name="trade_name" value="<?= htmlspecialchars($company['trade_name']) ?>" required>

                <label>CNPJ</label>
                <input type="text" name="document" value="<?= htmlspecialchars($company['document']) ?>" required>

                <label>E-mail</label>
                <input type="email" name="email" value="<?= htmlspecialchars($company['email']) ?>">

                <label>Telefone</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($company['phone']) ?>">

                <label>Website</label>
                <input type="text" name="website" value="<?= htmlspecialchars($company['website']) ?>">

                <label>Ramo de Atividade</label>
                <input type="text" name="sector" value="<?= htmlspecialchars($company['sector']) ?>">

                <label>Porte</label>

                <select name="size">
                    <option value="Micro" <?= $company['size']=='Micro'?'selected':'' ?>>Micro</option>
                    <option value="Pequena" <?= $company['size']=='Pequena'?'selected':'' ?>>Pequena</option>
                    <option value="Média" <?= $company['size']=='Média'?'selected':'' ?>>Média</option>
                    <option value="Grande" <?= $company['size']=='Grande'?'selected':'' ?>>Grande</option>
                </select>

                <label>Número de Funcionários</label>
                <input type="number" name="employees" value="<?= $company['employees'] ?>">

                <label>Cidade</label>
                <input type="text" name="city" value="<?= htmlspecialchars($company['city']) ?>">

                <label>UF</label>
                <input type="text" name="state" maxlength="2" value="<?= htmlspecialchars($company['state']) ?>">

                <label>Status</label>

                <select name="active">
                    <option value="1" <?= $company['active'] ? 'selected' : '' ?>>Ativa</option>
                    <option value="0" <?= !$company['active'] ? 'selected' : '' ?>>Inativa</option>
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