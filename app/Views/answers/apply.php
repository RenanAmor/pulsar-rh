<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Responder Pesquisa - <?= APP_NAME ?></title>

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
            <a href="<?= BASE_URL ?>/indicators">Indicadores</a>
            <a href="<?= BASE_URL ?>/laboratory">Laboratório Organizacional</a>
            <a href="<?= BASE_URL ?>/oie">OIE</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <h1><?= htmlspecialchars($survey['title']) ?></h1>

        <p>
            Respondendo como: <strong><?= htmlspecialchars($employee['name']) ?></strong>
        </p>

        <div class="card">

            <?php if (empty($questions)): ?>

                <p>Esta pesquisa ainda não possui perguntas vinculadas.</p>

            <?php else: ?>

                <form method="POST" action="<?= BASE_URL ?>/answers/submit">

                    <input type="hidden" name="survey_id" value="<?= $survey['id'] ?>">
                    <input type="hidden" name="employee_id" value="<?= $employee['id'] ?>">

                    <?php foreach ($questions as $question): ?>

                        <?php
                            $questionId = (int) $question['question_id'];
                            $existing = $existingAnswers[$questionId] ?? null;
                            $fieldName = 'answers[' . $questionId . ']';
                        ?>

                        <div class="card">

                            <label>
                                <?= htmlspecialchars($question['category']) ?> —
                                <?= htmlspecialchars($question['dimension']) ?>
                                <?= $question['required'] ? ' *' : '' ?>
                            </label>

                            <p><?= htmlspecialchars($question['question']) ?></p>

                            <?php if ($question['answer_type'] === 'Escala'): ?>

                                <?php for ($value = (int) $question['scale_min']; $value <= (int) $question['scale_max']; $value++): ?>
                                    <label>
                                        <input
                                            type="radio"
                                            name="<?= $fieldName ?>"
                                            value="<?= $value ?>"
                                            <?= $existing && (float) $existing['score'] === (float) $value ? 'checked' : '' ?>
                                            <?= $question['required'] ? 'required' : '' ?>
                                        >
                                        <?= $value ?>
                                    </label>
                                <?php endfor; ?>

                            <?php elseif ($question['answer_type'] === 'SimNão'): ?>

                                <label>
                                    <input
                                        type="radio"
                                        name="<?= $fieldName ?>"
                                        value="Sim"
                                        <?= $existing && $existing['answer_text'] === 'Sim' ? 'checked' : '' ?>
                                        <?= $question['required'] ? 'required' : '' ?>
                                    >
                                    Sim
                                </label>

                                <label>
                                    <input
                                        type="radio"
                                        name="<?= $fieldName ?>"
                                        value="Não"
                                        <?= $existing && $existing['answer_text'] === 'Não' ? 'checked' : '' ?>
                                        <?= $question['required'] ? 'required' : '' ?>
                                    >
                                    Não
                                </label>

                            <?php elseif ($question['answer_type'] === 'Múltipla Escolha'): ?>

                                <input
                                    type="text"
                                    name="<?= $fieldName ?>"
                                    value="<?= htmlspecialchars($existing['answer_text'] ?? '') ?>"
                                    placeholder="Digite a opção escolhida"
                                    <?= $question['required'] ? 'required' : '' ?>
                                >

                            <?php else: ?>

                                <textarea
                                    name="<?= $fieldName ?>"
                                    rows="3"
                                    <?= $question['required'] ? 'required' : '' ?>
                                ><?= htmlspecialchars($existing['answer_text'] ?? '') ?></textarea>

                            <?php endif; ?>

                        </div>

                    <?php endforeach; ?>

                    <button type="submit">
                        Salvar e Concluir
                    </button>

                </form>

            <?php endif; ?>

        </div>

    </main>

</div>

</body>

</html>
