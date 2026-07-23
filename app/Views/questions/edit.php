<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Pergunta | <?= APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

</head>

<body>

<div class="dashboard">

    <aside class="sidebar">

        <div class="logo">
            <span>P</span>
        </div>

        <h2><?= APP_NAME ?></h2>

<?php $activeNav = 'administration'; require __DIR__ . '/../partials/nav.php'; ?>

    </aside>

    <main class="content">

        <?php $breadcrumb = [
            ['label' => 'Administração', 'href' => BASE_URL . '/administration'],
            ['label' => 'Pesquisas'],
            ['label' => 'Biblioteca Psicométrica', 'href' => BASE_URL . '/questions'],
            ['label' => 'Editar Pergunta'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <h1>Editar Pergunta</h1>

        <?php if ($surveysUsingQuestion > 0): ?>
            <p>
                ⚠️ Esta pergunta está vinculada a
                <strong><?= $surveysUsingQuestion ?></strong>
                pesquisa(s). Alterações de categoria, dimensão ou tipo de
                resposta podem impactar pesquisas já configuradas.
            </p>
        <?php endif; ?>

        <div class="card">

            <form method="POST" action="<?= BASE_URL ?>/questions/update">

                <input
                    type="hidden"
                    name="id"
                    value="<?= $question['id'] ?>"
                >

                <label>Empresa</label>
                <select name="company_id">
                    <option value="">Genérica (todas as empresas)</option>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?= $company['id'] ?>" <?= (int) ($question['company_id'] ?? 0) === (int) $company['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($company['trade_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Categoria</label>
                <select name="category" id="category" required>
                    <?php foreach (['Liderança', 'Comunicação', 'Engajamento', 'Cultura', 'Bem-estar', 'Desenvolvimento', 'Colaboração', 'Reconhecimento'] as $categoryOption): ?>
                        <option value="<?= $categoryOption ?>" <?= $question['category'] === $categoryOption ? 'selected' : '' ?>>
                            <?= $categoryOption ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Dimensão Psicológica</label>
                <select name="dimension" id="dimension" required></select>

                <label>Pergunta</label>
                <textarea name="question" rows="3" required><?= htmlspecialchars($question['question']) ?></textarea>

                <label>Tipo de Resposta</label>
                <select name="answer_type" id="answer_type">
                    <?php foreach (['Escala' => 'Escala', 'SimNão' => 'Sim/Não', 'Texto' => 'Texto', 'Múltipla Escolha' => 'Múltipla Escolha'] as $value => $label): ?>
                        <option value="<?= $value ?>" <?= $question['answer_type'] === $value ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <div id="scale_fields">

                    <label>Escala Mínima</label>
                    <input type="number" name="scale_min" value="<?= (int) $question['scale_min'] ?>">

                    <label>Escala Máxima</label>
                    <input type="number" name="scale_max" value="<?= (int) $question['scale_max'] ?>">

                </div>

                <label>Peso</label>
                <input type="number" name="weight" step="0.01" value="<?= htmlspecialchars((string) $question['weight']) ?>">

                <label>Status</label>
                <select name="active">
                    <option value="1" <?= $question['active'] ? 'selected' : '' ?>>Ativa</option>
                    <option value="0" <?= !$question['active'] ? 'selected' : '' ?>>Inativa</option>
                </select>

                <button type="submit">
                    Salvar Alterações
                </button>

            </form>

        </div>

    </main>

</div>

<script>
    const dimensionsByCategory = {
        'Liderança': ['Liderança'],
        'Comunicação': ['Comunicação'],
        'Engajamento': ['Engajamento', 'Motivação', 'Satisfação'],
        'Cultura': ['Cultura', 'Clima Organizacional', 'Pertencimento', 'Justiça Organizacional', 'Segurança Psicológica'],
        'Bem-estar': ['Bem-estar', 'Burnout'],
        'Desenvolvimento': ['Desenvolvimento'],
        'Colaboração': ['Trabalho em Equipe'],
        'Reconhecimento': ['Reconhecimento']
    };

    const currentDimension = <?= json_encode($question['dimension']) ?>;

    const categorySelect = document.getElementById('category');
    const dimensionSelect = document.getElementById('dimension');
    const answerTypeSelect = document.getElementById('answer_type');
    const scaleFields = document.getElementById('scale_fields');

    function fillDimensions() {
        const dimensions = dimensionsByCategory[categorySelect.value] || [];

        dimensionSelect.innerHTML = '';

        if (!dimensions.includes(currentDimension)) {
            dimensions.push(currentDimension);
        }

        dimensions.forEach((dimension) => {
            const option = document.createElement('option');
            option.value = dimension;
            option.textContent = dimension;
            option.selected = dimension === currentDimension;
            dimensionSelect.appendChild(option);
        });
    }

    function toggleScaleFields() {
        scaleFields.style.display = answerTypeSelect.value === 'Escala' ? 'block' : 'none';
    }

    categorySelect.addEventListener('change', fillDimensions);
    answerTypeSelect.addEventListener('change', toggleScaleFields);

    fillDimensions();
    toggleScaleFields();
</script>

</body>

</html>
