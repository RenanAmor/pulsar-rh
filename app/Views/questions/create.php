<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nova Pergunta - <?= APP_NAME ?></title>

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
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <h1>Nova Pergunta</h1>

        <div class="card">

            <form method="POST" action="<?= BASE_URL ?>/questions/store">

                <label>Empresa</label>
                <select name="company_id">
                    <option value="">Genérica (todas as empresas)</option>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?= $company['id'] ?>">
                            <?= htmlspecialchars($company['trade_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Categoria</label>
                <select name="category" id="category" required>
                    <option value="Liderança">Liderança</option>
                    <option value="Comunicação">Comunicação</option>
                    <option value="Engajamento">Engajamento</option>
                    <option value="Cultura">Cultura</option>
                    <option value="Bem-estar">Bem-estar</option>
                    <option value="Desenvolvimento">Desenvolvimento</option>
                    <option value="Colaboração">Colaboração</option>
                    <option value="Reconhecimento">Reconhecimento</option>
                </select>

                <label>Dimensão Psicológica</label>
                <select name="dimension" id="dimension" required></select>

                <label>Pergunta</label>
                <textarea name="question" rows="3" required></textarea>

                <label>Tipo de Resposta</label>
                <select name="answer_type" id="answer_type">
                    <option value="Escala">Escala</option>
                    <option value="SimNão">Sim/Não</option>
                    <option value="Texto">Texto</option>
                    <option value="Múltipla Escolha">Múltipla Escolha</option>
                </select>

                <div id="scale_fields">

                    <label>Escala Mínima</label>
                    <input type="number" name="scale_min" value="1">

                    <label>Escala Máxima</label>
                    <input type="number" name="scale_max" value="5">

                </div>

                <label>Peso</label>
                <input type="number" name="weight" step="0.01" value="1.00">

                <label>Status</label>
                <select name="active">
                    <option value="1">Ativa</option>
                    <option value="0">Inativa</option>
                </select>

                <button type="submit">
                    Salvar Pergunta
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

    const categorySelect = document.getElementById('category');
    const dimensionSelect = document.getElementById('dimension');
    const answerTypeSelect = document.getElementById('answer_type');
    const scaleFields = document.getElementById('scale_fields');

    function fillDimensions() {
        const dimensions = dimensionsByCategory[categorySelect.value] || [];

        dimensionSelect.innerHTML = '';

        dimensions.forEach((dimension) => {
            const option = document.createElement('option');
            option.value = dimension;
            option.textContent = dimension;
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
