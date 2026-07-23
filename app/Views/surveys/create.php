<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nova Pesquisa - <?= APP_NAME ?></title>

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
            <a href="<?= BASE_URL ?>/branches">Filiais</a>
            <a href="<?= BASE_URL ?>/departments">Setores</a>
            <a href="<?= BASE_URL ?>/teams">Equipes</a>
            <a href="<?= BASE_URL ?>/positions">Cargos</a>
            <a href="<?= BASE_URL ?>/employees">Colaboradores</a>
            <a href="<?= BASE_URL ?>/jobs">Vagas</a>
            <a href="<?= BASE_URL ?>/candidates">Candidatos</a>
            <a class="active" href="<?= BASE_URL ?>/surveys">Pesquisas</a>
            <a href="<?= BASE_URL ?>/questions">Perguntas</a>
            <a href="<?= BASE_URL ?>/survey-questions">Montagem de Pesquisas</a>
            <a href="<?= BASE_URL ?>/answers">Respostas</a>
            <a href="<?= BASE_URL ?>/indicators">Indicadores</a>
            <a href="<?= BASE_URL ?>/laboratory">Laboratório Organizacional</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <h1>Nova Pesquisa</h1>

        <div class="card">

            <form method="POST" action="<?= BASE_URL ?>/surveys/store">

                <label>Empresa</label>
                <select name="company_id" required>
                    <option value="">Selecione uma empresa</option>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?= $company['id'] ?>">
                            <?= htmlspecialchars($company['trade_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Título</label>
                <input type="text" name="title" required>

                <label>Descrição</label>
                <textarea name="description" rows="4"></textarea>

                <label>Início do Período</label>
                <input type="date" name="start_date">

                <label>Fim do Período</label>
                <input type="date" name="end_date">

                <label>Pesquisa Anônima</label>
                <select name="anonymous">
                    <option value="1">Sim</option>
                    <option value="0">Não</option>
                </select>

                <label>Status</label>
                <select name="status">
                    <option value="Rascunho">Rascunho</option>
                    <option value="Agendada">Agendada</option>
                    <option value="Em andamento">Em andamento</option>
                    <option value="Encerrada">Encerrada</option>
                    <option value="Cancelada">Cancelada</option>
                </select>

                <button type="submit">
                    Salvar Pesquisa
                </button>

            </form>

        </div>

    </main>

</div>

</body>

</html>
