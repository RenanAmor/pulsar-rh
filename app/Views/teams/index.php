<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Equipes - <?= APP_NAME ?></title>

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
            <a class="active" href="<?= BASE_URL ?>/teams">Equipes</a>
            <a href="<?= BASE_URL ?>/positions">Cargos</a>
            <a href="<?= BASE_URL ?>/employees">Colaboradores</a>
            <a href="<?= BASE_URL ?>/jobs">Vagas</a>
            <a href="<?= BASE_URL ?>/candidates">Candidatos</a>
            <a href="<?= BASE_URL ?>/surveys">Pesquisas</a>
            <a href="<?= BASE_URL ?>/questions">Perguntas</a>
            <a href="<?= BASE_URL ?>/survey-questions">Montagem de Pesquisas</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <div class="page-header">

            <h1>Equipes</h1>

            <a class="btn-primary" href="<?= BASE_URL ?>/teams/create">
                + Nova Equipe
            </a>

        </div>

        <table class="table">

            <thead>

                <tr>
                    <th>Empresa</th>
                    <th>Filial</th>
                    <th>Setor</th>
                    <th>Equipe</th>
                    <th>Código</th>
                    <th>Gestor</th>
                    <th>Status</th>
                    <th width="180">Ações</th>
                </tr>

            </thead>

            <tbody>

            <?php foreach ($teams as $team): ?>

                <tr>

                    <td><?= htmlspecialchars($team['company_name']) ?></td>
                    <td><?= htmlspecialchars($team['branch_name']) ?></td>
                    <td><?= htmlspecialchars($team['department_name']) ?></td>
                    <td><?= htmlspecialchars($team['name']) ?></td>
                    <td><?= htmlspecialchars($team['code'] ?? '') ?></td>
                    <td><?= htmlspecialchars($team['leader_name'] ?? '') ?></td>
                    <td><?= $team['active'] ? 'Ativa' : 'Inativa' ?></td>

                    <td>

                        <a class="btn-action edit"
                           href="<?= BASE_URL ?>/teams/edit?id=<?= $team['id'] ?>">
                            ✏️ Editar
                        </a>

                        <a class="btn-action delete"
                           href="<?= BASE_URL ?>/teams/delete?id=<?= $team['id'] ?>"
                           onclick="return confirm('Deseja realmente excluir esta equipe?')">
                            🗑️ Excluir
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </main>

</div>

</body>

</html>
