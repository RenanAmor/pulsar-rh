<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cargos - <?= APP_NAME ?></title>

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
            <a class="active" href="<?= BASE_URL ?>/positions">Cargos</a>
            <a href="<?= BASE_URL ?>/employees">Colaboradores</a>
            <a href="<?= BASE_URL ?>/jobs">Vagas</a>
            <a href="<?= BASE_URL ?>/candidates">Candidatos</a>
            <a href="<?= BASE_URL ?>/surveys">Pesquisas</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <div class="page-header">

            <h1>Cargos</h1>

            <a class="btn-primary" href="<?= BASE_URL ?>/positions/create">
                + Novo Cargo
            </a>

        </div>

        <table class="table">

            <thead>

                <tr>
                    <th>Empresa</th>
                    <th>Filial</th>
                    <th>Setor</th>
                    <th>Cargo</th>
                    <th>Código</th>
                    <th>CBO</th>
                    <th>Status</th>
                    <th width="180">Ações</th>
                </tr>

            </thead>

            <tbody>

            <?php foreach ($positions as $position): ?>

                <tr>

                    <td><?= htmlspecialchars($position['company_name']) ?></td>
                    <td><?= htmlspecialchars($position['branch_name']) ?></td>
                    <td><?= htmlspecialchars($position['department_name']) ?></td>
                    <td><?= htmlspecialchars($position['name']) ?></td>
                    <td><?= htmlspecialchars($position['code'] ?? '') ?></td>
                    <td><?= htmlspecialchars($position['cbo'] ?? '') ?></td>
                    <td><?= $position['active'] ? 'Ativo' : 'Inativo' ?></td>

                    <td>

                        <a class="btn-action edit"
                           href="<?= BASE_URL ?>/positions/edit?id=<?= $position['id'] ?>">
                            ✏️ Editar
                        </a>

                        <a class="btn-action delete"
                           href="<?= BASE_URL ?>/positions/delete?id=<?= $position['id'] ?>"
                           onclick="return confirm('Deseja realmente excluir este cargo?')">
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
