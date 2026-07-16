<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Vagas - <?= APP_NAME ?></title>

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
            <a href="<?= BASE_URL ?>/branches">Filiais</a>
            <a href="<?= BASE_URL ?>/departments">Setores</a>
            <a href="<?= BASE_URL ?>/teams">Equipes</a>
            <a href="<?= BASE_URL ?>/users">Usuários</a>
            <a href="<?= BASE_URL ?>/positions">Cargos</a>
            <a href="<?= BASE_URL ?>/employees">Colaboradores</a>
            <a class="active" href="<?= BASE_URL ?>/jobs">Vagas</a>
            <a href="<?= BASE_URL ?>/candidates">Candidatos</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <div class="page-header">

            <h1>Vagas</h1>

            <a class="btn-primary" href="<?= BASE_URL ?>/jobs/create">
                + Nova Vaga
            </a>

        </div>

        <table class="table">

            <thead>

                <tr>
                    <th>Empresa</th>
                    <th>Vaga</th>
                    <th>Modalidade</th>
                    <th>Vagas</th>
                    <th>Status</th>
                </tr>

            </thead>

            <tbody>

            <?php foreach ($jobs as $job): ?>

                <tr>

                    <td><?= htmlspecialchars($job['company']) ?></td>

                    <td><?= htmlspecialchars($job['title']) ?></td>

                    <td><?= htmlspecialchars($job['workplace']) ?></td>

                    <td><?= $job['vacancies'] ?></td>

                    <td><?= $job['active'] ? 'Ativa' : 'Encerrada' ?></td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </main>

</div>

</body>

</html>