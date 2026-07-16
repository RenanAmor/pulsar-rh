<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Cargo - <?= APP_NAME ?></title>

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
            <a class="active" href="<?= BASE_URL ?>/positions">Cargos</a>
            <a href="<?= BASE_URL ?>/jobs">Vagas</a>
            <a href="<?= BASE_URL ?>/candidates">Candidatos</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <h1>Editar Cargo</h1>

        <div class="card">

            <form method="POST" action="<?= BASE_URL ?>/positions/update">

                <input
                    type="hidden"
                    name="id"
                    value="<?= $position['id'] ?>"
                >

                <label>Empresa</label>
                <select name="company_id" id="company_id" required>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?= $company['id'] ?>" <?= (int) $position['company_id'] === (int) $company['id'] ? 'selected' : '' ?>>
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
                            <?= (int) $position['branch_id'] === (int) $branch['id'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($branch['company_name']) ?> - <?= htmlspecialchars($branch['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Setor</label>
                <select name="department_id" id="department_id" required>
                    <?php foreach ($departments as $dept): ?>
                        <option
                            value="<?= $dept['id'] ?>"
                            data-company-id="<?= $dept['company_id'] ?>"
                            data-branch-id="<?= $dept['branch_id'] ?>"
                            <?= (int) $position['department_id'] === (int) $dept['id'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($dept['company_name']) ?> / <?= htmlspecialchars($dept['branch_name']) ?> / <?= htmlspecialchars($dept['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Nome do Cargo</label>
                <input type="text" name="name" value="<?= htmlspecialchars($position['name']) ?>" required>

                <label>Código</label>
                <input type="text" name="code" value="<?= htmlspecialchars($position['code'] ?? '') ?>">

                <label>CBO</label>
                <input type="text" name="cbo" value="<?= htmlspecialchars($position['cbo'] ?? '') ?>" placeholder="Ex: 2521-05">

                <label>Salário Mínimo</label>
                <input type="number" name="salary_min" step="0.01" min="0" value="<?= htmlspecialchars((string) ($position['salary_min'] ?? '')) ?>">

                <label>Salário Máximo</label>
                <input type="number" name="salary_max" step="0.01" min="0" value="<?= htmlspecialchars((string) ($position['salary_max'] ?? '')) ?>">

                <label>Descrição</label>
                <textarea name="description" rows="4"><?= htmlspecialchars($position['description'] ?? '') ?></textarea>

                <label>Status</label>
                <select name="active">
                    <option value="1" <?= $position['active'] ? 'selected' : '' ?>>Ativo</option>
                    <option value="0" <?= !$position['active'] ? 'selected' : '' ?>>Inativo</option>
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
    const departmentSelect = document.getElementById('department_id');

    const currentBranchId = '<?= (int) $position['branch_id'] ?>';
    const currentDeptId = '<?= (int) $position['department_id'] ?>';

    const allBranchOptions = Array.from(branchSelect.querySelectorAll('option[data-company-id]'));
    const allDeptOptions = Array.from(departmentSelect.querySelectorAll('option[data-company-id]'));

    function filterBranchesByCompany() {
        const selectedCompanyId = companySelect.value;

        branchSelect.innerHTML = '';
        departmentSelect.innerHTML = '';

        allBranchOptions.forEach((option) => {
            if (!selectedCompanyId || option.dataset.companyId === selectedCompanyId) {
                branchSelect.appendChild(option);
            }
        });

        const selBranch = branchSelect.querySelector('option[value="' + currentBranchId + '"]');
        if (selBranch) {
            selBranch.selected = true;
        } else if (branchSelect.options.length > 0) {
            branchSelect.options[0].selected = true;
        }

        filterDepartmentsByBranch();
    }

    function filterDepartmentsByBranch() {
        const selectedCompanyId = companySelect.value;
        const selectedBranchId = branchSelect.value;

        departmentSelect.innerHTML = '';

        allDeptOptions.forEach((option) => {
            const matchCompany = !selectedCompanyId || option.dataset.companyId === selectedCompanyId;
            const matchBranch = !selectedBranchId || option.dataset.branchId === selectedBranchId;

            if (matchCompany && matchBranch) {
                departmentSelect.appendChild(option);
            }
        });

        const selDept = departmentSelect.querySelector('option[value="' + currentDeptId + '"]');
        if (selDept) {
            selDept.selected = true;
        } else if (departmentSelect.options.length > 0) {
            departmentSelect.options[0].selected = true;
        }
    }

    companySelect.addEventListener('change', filterBranchesByCompany);
    branchSelect.addEventListener('change', filterDepartmentsByBranch);

    filterBranchesByCompany();
</script>

</body>

</html>
