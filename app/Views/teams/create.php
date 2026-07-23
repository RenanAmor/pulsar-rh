<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nova Equipe | <?= APP_NAME ?></title>

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
            ['label' => 'Organização'],
            ['label' => 'Equipes', 'href' => BASE_URL . '/teams'],
            ['label' => 'Nova Equipe'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <h1>Nova Equipe</h1>

        <div class="card">

            <form method="POST" action="<?= BASE_URL ?>/teams/store">

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
                    <?php foreach ($departments as $department): ?>
                        <option
                            value="<?= $department['id'] ?>"
                            data-company-id="<?= $department['company_id'] ?>"
                            data-branch-id="<?= $department['branch_id'] ?>"
                        >
                            <?= htmlspecialchars($department['company_name']) ?> / <?= htmlspecialchars($department['branch_name']) ?> / <?= htmlspecialchars($department['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Gestor da Equipe</label>
                <select name="leader_id" id="leader_id">
                    <option value="">Sem gestor</option>
                    <?php foreach ($leaders as $leader): ?>
                        <option
                            value="<?= $leader['id'] ?>"
                            data-company-id="<?= $leader['company_id'] ?>"
                            data-branch-id="<?= $leader['branch_id'] ?>"
                            data-department-id="<?= $leader['department_id'] ?>"
                        >
                            <?= htmlspecialchars($leader['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Nome da Equipe</label>
                <input type="text" name="name" required>

                <label>Código</label>
                <input type="text" name="code">

                <label>Descrição</label>
                <textarea name="description" rows="4"></textarea>

                <label>Status</label>
                <select name="active">
                    <option value="1">Ativa</option>
                    <option value="0">Inativa</option>
                </select>

                <button type="submit">
                    Salvar Equipe
                </button>

            </form>

        </div>

    </main>

</div>

<script>
    const companySelect = document.getElementById('company_id');
    const branchSelect = document.getElementById('branch_id');
    const departmentSelect = document.getElementById('department_id');
    const leaderSelect = document.getElementById('leader_id');

    const allBranchOptions = Array.from(branchSelect.querySelectorAll('option[data-company-id]'));
    const allDepartmentOptions = Array.from(departmentSelect.querySelectorAll('option[data-company-id]'));
    const allLeaderOptions = Array.from(leaderSelect.querySelectorAll('option[data-company-id]'));

    function filterBranchesByCompany() {
        const selectedCompanyId = companySelect.value;

        branchSelect.innerHTML = '<option value="">Selecione uma filial</option>';
        departmentSelect.innerHTML = '<option value="">Selecione um setor</option>';
        leaderSelect.innerHTML = '<option value="">Sem gestor</option>';

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
        leaderSelect.innerHTML = '<option value="">Sem gestor</option>';

        allDepartmentOptions.forEach((option) => {
            const matchCompany = !selectedCompanyId || option.dataset.companyId === selectedCompanyId;
            const matchBranch = !selectedBranchId || option.dataset.branchId === selectedBranchId;

            if (matchCompany && matchBranch) {
                departmentSelect.appendChild(option);
            }
        });
    }

    function filterLeadersByDepartment() {
        const selectedCompanyId = companySelect.value;
        const selectedBranchId = branchSelect.value;
        const selectedDepartmentId = departmentSelect.value;

        leaderSelect.innerHTML = '<option value="">Sem gestor</option>';

        allLeaderOptions.forEach((option) => {
            const matchCompany = !selectedCompanyId || option.dataset.companyId === selectedCompanyId;
            const matchBranch = !selectedBranchId || option.dataset.branchId === selectedBranchId;
            const matchDepartment = !selectedDepartmentId || option.dataset.departmentId === selectedDepartmentId;

            if (matchCompany && matchBranch && matchDepartment) {
                leaderSelect.appendChild(option);
            }
        });
    }

    companySelect.addEventListener('change', filterBranchesByCompany);
    branchSelect.addEventListener('change', filterDepartmentsByBranch);
    departmentSelect.addEventListener('change', filterLeadersByDepartment);

    filterBranchesByCompany();
</script>

</body>

</html>
