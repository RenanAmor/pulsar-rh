<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Respostas do Colaborador - <?= APP_NAME ?></title>

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
            <a href="<?= BASE_URL ?>/questions">Perguntas</a>
            <a href="<?= BASE_URL ?>/survey-questions">Montagem de Pesquisas</a>
            <a class="active" href="<?= BASE_URL ?>/answers">Respostas</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <div class="page-header">

            <h1><?= htmlspecialchars($employee['name']) ?> — <?= htmlspecialchars($survey['title']) ?></h1>

            <a class="btn-primary" href="<?= BASE_URL ?>/answers/respondents?survey_id=<?= $survey['id'] ?>">
                ← Voltar
            </a>

        </div>

        <?php if (empty($answers)): ?>

            <p>Este colaborador ainda não respondeu nenhuma pergunta.</p>

        <?php else: ?>

            <table class="table">

                <thead>

                    <tr>
                        <th>Categoria</th>
                        <th>Dimensão</th>
                        <th>Pergunta</th>
                        <th>Resposta</th>
                        <th>Data/Hora</th>
                    </tr>

                </thead>

                <tbody>

                <?php foreach ($answers as $answer): ?>

                    <tr>

                        <td><?= htmlspecialchars($answer['category']) ?></td>
                        <td><?= htmlspecialchars($answer['dimension']) ?></td>
                        <td><?= htmlspecialchars($answer['question']) ?></td>
                        <td>
                            <?= htmlspecialchars(
                                $answer['score'] !== null
                                    ? (string) $answer['score']
                                    : (string) ($answer['answer_text'] ?? '')
                            ) ?>
                        </td>
                        <td><?= htmlspecialchars($answer['answered_at']) ?></td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        <?php endif; ?>

    </main>

</div>

</body>

</html>
