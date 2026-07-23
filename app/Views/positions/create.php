<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Novo Cargo - <?= APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

</head>

<body>

<div class="dashboard">

    <aside class="sidebar">

        <div class="logo">
            <span>P</span>
        </div>

        <h2>Pulsar RH</h2>

<?php $activeNav = 'administration'; require __DIR__ . '/../partials/nav.php'; ?>

    </aside>

    <main class="content">

        <h1>Novo Cargo</h1>

        <div class="card">

            <form method="POST" action="<?= BASE_URL ?>/positions/store">

                <label>Empresa</label>
                <select name="company_id" id="company_id" required>
                    <option value="">Selecione uma empresa</option>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?= $company['id'] ?>">
                            <?= htmlspecialchars($company['trade_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Filial</label>
                <select name="branch_id" id="branch_id" required>
                    <option value="">Selecione uma filial</option>
                    <?php foreach ($branches as $branch): ?>
                        <option
                            value="<?= $branch['id'] ?>"
                            data-company-id="<?= $branch['company_id'] ?>"
                        >
                            <?= htmlspecialchars($branch['company_name']) ?> - <?= htmlspecialchars($branch['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Setor</label>
                <select name="department_id" id="department_id" required>
                    <option value="">Selecione um setor</option>
                    <?php foreach ($departments as $dept): ?>
                        <option
                            value="<?= $dept['id'] ?>"
                            data-company-id="<?= $dept['company_id'] ?>"
                            data-branch-id="<?= $dept['branch_id'] ?>"
                        >
                            <?= htmlspecialchars($dept['company_name']) ?> / <?= htmlspecialchars($dept['branch_name']) ?> / <?= htmlspecialchars($dept['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Nome do Cargo</label>
                <input type="text" name="name" required>

                <label>Código</label>
                <input type="text" name="code">

                <label>CBO</label>
                <input type="text" name="cbo" placeholder="Ex: 2521-05">

                <label>Salário Mínimo</label>
                <input type="number" name="salary_min" step="0.01" min="0">

                <label>Salário Máximo</label>
                <input type="number" name="salary_max" step="0.01" min="0">

                <label>Descrição</label>
                <textarea name="description" rows="4"></textarea>

                <label>Status</label>
                <select name="active">
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>

                <button type="submit">
                    Salvar Cargo
                </button>

            </form>

        </div>

    </main>

</div>

<script>
    const companySelect = document.getElementById('company_id');
    const branchSelect = document.getElementById('branch_id');
    const departmentSelect = document.getElementById('department_id');

    const allBranchOptions = Array.from(branchSelect.querySelectorAll('option[data-company-id]'));
    const allDeptOptions = Array.from(departmentSelect.querySelectorAll('option[data-company-id]'));

    function filterBranchesByCompany() {
        const selectedCompanyId = companySelect.value;

        branchSelect.innerHTML = '<option value="">Selecione uma filial</option>';
        departmentSelect.innerHTML = '<option value="">Selecione um setor</option>';

        allBranchOptions.forEach((option) => {
            if (!selectedCompanyId || option.dataset.companyId === selectedCompanyId) {
                branchSelect.appendChild(option);
            }
        });
    }

    function filterDepartmentsByBranch() {
        const selectedCompanyId = companySelect.value;
        const selectedBranchId = branchSelect.value;

        departmentSelect.innerHTML = '<option value="">Selecione um setor</option>';

        allDeptOptions.forEach((option) => {
            const matchCompany = !selectedCompanyId || option.dataset.companyId === selectedCompanyId;
            const matchBranch = !selectedBranchId || option.dataset.branchId === selectedBranchId;

            if (matchCompany && matchBranch) {
                departmentSelect.appendChild(option);
            }
        });
    }

    companySelect.addEventListener('change', filterBranchesByCompany);
    branchSelect.addEventListener('change', filterDepartmentsByBranch);
</script>

</body>

</html>
