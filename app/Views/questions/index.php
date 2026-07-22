<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Perguntas - <?= APP_NAME ?></title>

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
            <a href="<?= BASE_URL ?>/surveys">Pesquisas</a>
            <a class="active" href="<?= BASE_URL ?>/questions">Perguntas</a>
            <a href="<?= BASE_URL ?>/survey-questions">Montagem de Pesquisas</a>
            <a href="<?= BASE_URL ?>/answers">Respostas</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <div class="page-header">

            <h1>Biblioteca Psicométrica</h1>

            <a class="btn-primary" href="<?= BASE_URL ?>/questions/create">
                + Nova Pergunta
            </a>

        </div>

        <table class="table">

            <thead>

                <tr>
                    <th>Categoria</th>
                    <th>Dimensão</th>
                    <th>Pergunta</th>
                    <th>Tipo de Resposta</th>
                    <th>Peso</th>
                    <th>Empresa</th>
                    <th>Status</th>
                    <th width="180">Ações</th>
                </tr>

            </thead>

            <tbody>

            <?php foreach ($questions as $question): ?>

                <tr>

                    <td><?= htmlspecialchars($question['category']) ?></td>
                    <td><?= htmlspecialchars($question['dimension']) ?></td>
                    <td><?= htmlspecialchars($question['question']) ?></td>
                    <td><?= htmlspecialchars($question['answer_type']) ?></td>
                    <td><?= htmlspecialchars((string) $question['weight']) ?></td>
                    <td><?= htmlspecialchars($question['company_name'] ?? 'Genérica (todas as empresas)') ?></td>
                    <td><?= $question['active'] ? 'Ativa' : 'Inativa' ?></td>

                    <td>

                        <a class="btn-action edit"
                           href="<?= BASE_URL ?>/questions/edit?id=<?= $question['id'] ?>">
                            ✏️ Editar
                        </a>

                        <a class="btn-action delete"
                           href="<?= BASE_URL ?>/questions/delete?id=<?= $question['id'] ?>"
                           onclick="return confirm('Deseja realmente excluir esta pergunta?')">
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
