<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Colaborador - <?= APP_NAME ?></title>

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

        <h1>Editar Colaborador</h1>

        <div class="card">

            <form method="POST" action="<?= BASE_URL ?>/employees/update">

                <input
                    type="hidden"
                    name="id"
                    value="<?= $employee['id'] ?>"
                >

                <label>Empresa</label>
                <select name="company_id" id="company_id" required>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?= $company['id'] ?>" <?= (int) $employee['company_id'] === (int) $company['id'] ? 'selected' : '' ?>>
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
                            <?= (int) $employee['branch_id'] === (int) $branch['id'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($branch['company_name']) ?> - <?= htmlspecialchars($branch['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Setor</label>
                <select name="department_id" id="department_id" required>
                    <?php foreach ($departments as $department): ?>
                        <option
                            value="<?= $department['id'] ?>"
                            data-company-id="<?= $department['company_id'] ?>"
                            data-branch-id="<?= $department['branch_id'] ?>"
                            <?= (int) $employee['department_id'] === (int) $department['id'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($department['company_name']) ?> / <?= htmlspecialchars($department['branch_name']) ?> / <?= htmlspecialchars($department['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Cargo</label>
                <select name="position_id" id="position_id" required>
                    <?php foreach ($positions as $position): ?>
                        <option
                            value="<?= $position['id'] ?>"
                            data-company-id="<?= $position['company_id'] ?>"
                            data-branch-id="<?= $position['branch_id'] ?>"
                            data-department-id="<?= $position['department_id'] ?>"
                            <?= (int) $employee['position_id'] === (int) $position['id'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($position['company_name']) ?> / <?= htmlspecialchars($position['branch_name']) ?> / <?= htmlspecialchars($position['department_name']) ?> / <?= htmlspecialchars($position['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Gestor</label>
                <select name="manager_id" id="manager_id">
                    <option value="">Sem gestor</option>
                    <?php foreach ($managers as $manager): ?>
                        <?php if ((int) $manager['id'] === (int) $employee['id']) continue; ?>
                        <option
                            value="<?= $manager['id'] ?>"
                            data-company-id="<?= $manager['company_id'] ?>"
                            data-branch-id="<?= $manager['branch_id'] ?>"
                            data-department-id="<?= $manager['department_id'] ?>"
                            <?= (int) ($employee['manager_id'] ?? 0) === (int) $manager['id'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($manager['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Nome do Colaborador</label>
                <input type="text" name="name" value="<?= htmlspecialchars($employee['name']) ?>" required>

                <label>Matrícula</label>
                <input type="text" name="registration" value="<?= htmlspecialchars($employee['registration'] ?? '') ?>">

                <label>CPF</label>
                <input type="text" name="cpf" value="<?= htmlspecialchars($employee['cpf'] ?? '') ?>">

                <label>Data de Nascimento</label>
                <input type="date" name="birth_date" value="<?= htmlspecialchars($employee['birth_date'] ?? '') ?>">

                <label>Gênero</label>
                <select name="gender">
                    <option value="" <?= empty($employee['gender']) ? 'selected' : '' ?>>Não informado</option>
                    <option value="Masculino" <?= ($employee['gender'] ?? '') === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                    <option value="Feminino" <?= ($employee['gender'] ?? '') === 'Feminino' ? 'selected' : '' ?>>Feminino</option>
                    <option value="Outro" <?= ($employee['gender'] ?? '') === 'Outro' ? 'selected' : '' ?>>Outro</option>
                    <option value="Prefiro não informar" <?= ($employee['gender'] ?? '') === 'Prefiro não informar' ? 'selected' : '' ?>>Prefiro não informar</option>
                </select>

                <label>E-mail</label>
                <input type="email" name="email" value="<?= htmlspecialchars($employee['email'] ?? '') ?>">

                <label>Telefone</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($employee['phone'] ?? '') ?>">

                <label>Data de Admissão</label>
                <input type="date" name="admission_date" value="<?= htmlspecialchars($employee['admission_date'] ?? '') ?>">

                <label>Data de Desligamento</label>
                <input type="date" name="termination_date" value="<?= htmlspecialchars($employee['termination_date'] ?? '') ?>">

                <label>Tipo de Contrato</label>
                <select name="employment_type">
                    <option value="CLT" <?= ($employee['employment_type'] ?? '') === 'CLT' ? 'selected' : '' ?>>CLT</option>
                    <option value="PJ" <?= ($employee['employment_type'] ?? '') === 'PJ' ? 'selected' : '' ?>>PJ</option>
                    <option value="Estágio" <?= ($employee['employment_type'] ?? '') === 'Estágio' ? 'selected' : '' ?>>Estágio</option>
                    <option value="Temporário" <?= ($employee['employment_type'] ?? '') === 'Temporário' ? 'selected' : '' ?>>Temporário</option>
                    <option value="Terceirizado" <?= ($employee['employment_type'] ?? '') === 'Terceirizado' ? 'selected' : '' ?>>Terceirizado</option>
                </select>

                <label>Status</label>
                <select name="status">
                    <option value="Ativo" <?= ($employee['status'] ?? '') === 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                    <option value="Férias" <?= ($employee['status'] ?? '') === 'Férias' ? 'selected' : '' ?>>Férias</option>
                    <option value="Afastado" <?= ($employee['status'] ?? '') === 'Afastado' ? 'selected' : '' ?>>Afastado</option>
                    <option value="Desligado" <?= ($employee['status'] ?? '') === 'Desligado' ? 'selected' : '' ?>>Desligado</option>
                </select>

                <label>ID do Time (opcional)</label>
                <input type="number" name="team_id" min="1" value="<?= htmlspecialchars((string) ($employee['team_id'] ?? '')) ?>">

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
    const positionSelect = document.getElementById('position_id');
    const managerSelect = document.getElementById('manager_id');

    const currentBranchId = '<?= (int) $employee['branch_id'] ?>';
    const currentDepartmentId = '<?= (int) $employee['department_id'] ?>';
    const currentPositionId = '<?= (int) $employee['position_id'] ?>';
    const currentManagerId = '<?= (int) ($employee['manager_id'] ?? 0) ?>';

    const allBranchOptions = Array.from(branchSelect.querySelectorAll('option[data-company-id]'));
    const allDepartmentOptions = Array.from(departmentSelect.querySelectorAll('option[data-company-id]'));
    const allPositionOptions = Array.from(positionSelect.querySelectorAll('option[data-company-id]'));
    const allManagerOptions = Array.from(managerSelect.querySelectorAll('option[data-company-id]'));

    function filterBranchesByCompany() {
        const selectedCompanyId = companySelect.value;

        branchSelect.innerHTML = '';
        departmentSelect.innerHTML = '';
        positionSelect.innerHTML = '';
        managerSelect.innerHTML = '<option value="">Sem gestor</option>';

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

        filterDepartmentsByBranch();
    }

    function filterDepartmentsByBranch() {
        const selectedCompanyId = companySelect.value;
        const selectedBranchId = branchSelect.value;

        departmentSelect.innerHTML = '';
        positionSelect.innerHTML = '';
        managerSelect.innerHTML = '<option value="">Sem gestor</option>';

        allDepartmentOptions.forEach((option) => {
            const matchCompany = !selectedCompanyId || option.dataset.companyId === selectedCompanyId;
            const matchBranch = !selectedBranchId || option.dataset.branchId === selectedBranchId;

            if (matchCompany && matchBranch) {
                departmentSelect.appendChild(option);
            }
        });

        const selectedDepartment = departmentSelect.querySelector('option[value="' + currentDepartmentId + '"]');
        if (selectedDepartment) {
            selectedDepartment.selected = true;
        } else if (departmentSelect.options.length > 0) {
            departmentSelect.options[0].selected = true;
        }

        filterPositionsByDepartment();
    }

    function filterPositionsByDepartment() {
        const selectedCompanyId = companySelect.value;
        const selectedBranchId = branchSelect.value;
        const selectedDepartmentId = departmentSelect.value;

        positionSelect.innerHTML = '';
        managerSelect.innerHTML = '<option value="">Sem gestor</option>';

        allPositionOptions.forEach((option) => {
            const matchCompany = !selectedCompanyId || option.dataset.companyId === selectedCompanyId;
            const matchBranch = !selectedBranchId || option.dataset.branchId === selectedBranchId;
            const matchDepartment = !selectedDepartmentId || option.dataset.departmentId === selectedDepartmentId;

            if (matchCompany && matchBranch && matchDepartment) {
                positionSelect.appendChild(option);
            }
        });

        const selectedPosition = positionSelect.querySelector('option[value="' + currentPositionId + '"]');
        if (selectedPosition) {
            selectedPosition.selected = true;
        } else if (positionSelect.options.length > 0) {
            positionSelect.options[0].selected = true;
        }

        filterManagersByHierarchy();
    }

    function filterManagersByHierarchy() {
        const selectedCompanyId = companySelect.value;
        const selectedBranchId = branchSelect.value;
        const selectedDepartmentId = departmentSelect.value;

        managerSelect.innerHTML = '<option value="">Sem gestor</option>';

        allManagerOptions.forEach((option) => {
            const matchCompany = !selectedCompanyId || option.dataset.companyId === selectedCompanyId;
            const matchBranch = !selectedBranchId || option.dataset.branchId === selectedBranchId;
            const matchDepartment = !selectedDepartmentId || option.dataset.departmentId === selectedDepartmentId;

            if (matchCompany && matchBranch && matchDepartment) {
                managerSelect.appendChild(option);
            }
        });

        const selectedManager = managerSelect.querySelector('option[value="' + currentManagerId + '"]');
        if (selectedManager) {
            selectedManager.selected = true;
        }
    }

    companySelect.addEventListener('change', filterBranchesByCompany);
    branchSelect.addEventListener('change', filterDepartmentsByBranch);
    departmentSelect.addEventListener('change', filterPositionsByDepartment);

    filterBranchesByCompany();
</script>

</body>

</html>
