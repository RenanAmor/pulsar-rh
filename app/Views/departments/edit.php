<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Setor - <?= APP_NAME ?></title>

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
            <a class="active" href="<?= BASE_URL ?>/departments">Setores</a>
            <a href="<?= BASE_URL ?>/positions">Cargos</a>
            <a href="<?= BASE_URL ?>/employees">Colaboradores</a>
            <a href="<?= BASE_URL ?>/jobs">Vagas</a>
            <a href="<?= BASE_URL ?>/candidates">Candidatos</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <h1>Editar Setor</h1>

        <div class="card">

            <form method="POST" action="<?= BASE_URL ?>/departments/update">

                <input
                    type="hidden"
                    name="id"
                    value="<?= $department['id'] ?>"
                >

                <label>Empresa</label>
                <select name="company_id" id="company_id" required>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?= $company['id'] ?>" <?= (int) $department['company_id'] === (int) $company['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($company['trade_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Filial</label>
                <select name="branch_id" id="branch_id" required>
                    <?php foreach ($branches as $branch): ?>
                        <option
                            value="<?= $branch['id'] ?>"
                            data-company-id="<?= $branch['company_id'] ?>"
                            <?= (int) $department['branch_id'] === (int) $branch['id'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($branch['company_name']) ?> - <?= htmlspecialchars($branch['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Nome do Setor</label>
                <input type="text" name="name" value="<?= htmlspecialchars($department['name']) ?>" required>

                <label>Código</label>
                <input type="text" name="code" value="<?= htmlspecialchars($department['code'] ?? '') ?>">

                <label>Gestor (ID do usuário)</label>
                <input type="number" name="manager_id" min="1" value="<?= htmlspecialchars((string) ($department['manager_id'] ?? '')) ?>">

                <label>E-mail</label>
                <input type="email" name="email" value="<?= htmlspecialchars($department['email'] ?? '') ?>">

                <label>Telefone</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($department['phone'] ?? '') ?>">

                <label>Descrição</label>
                <textarea name="description" rows="4"><?= htmlspecialchars($department['description'] ?? '') ?></textarea>

                <label>Status</label>
                <select name="active">
                    <option value="1" <?= $department['active'] ? 'selected' : '' ?>>Ativo</option>
                    <option value="0" <?= !$department['active'] ? 'selected' : '' ?>>Inativo</option>
                </select>

                <button type="submit">
                    Salvar Alterações
                </button>

            </form>

        </div>

    </main>

</div>

<script>
    const companySelect = document.getElementById('company_id');
    const branchSelect = document.getElementById('branch_id');
    const currentBranchId = '<?= (int) $department['branch_id'] ?>';
    const allBranchOptions = Array.from(branchSelect.querySelectorAll('option[data-company-id]'));

    function filterBranchesByCompany() {
        const selectedCompanyId = companySelect.value;

        branchSelect.innerHTML = '';

        allBranchOptions.forEach((option) => {
            if (!selectedCompanyId || option.dataset.companyId === selectedCompanyId) {
                branchSelect.appendChild(option);
            }
        });

        const selectedBranch = branchSelect.querySelector('option[value="' + currentBranchId + '"]');

        if (selectedBranch) {
            selectedBranch.selected = true;
        } else if (branchSelect.options.length > 0) {
            branchSelect.options[0].selected = true;
        }
    }

    companySelect.addEventListener('change', filterBranchesByCompany);
    filterBranchesByCompany();
</script>

</body>

</html>
