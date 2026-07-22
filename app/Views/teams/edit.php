<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Equipe - <?= APP_NAME ?></title>

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
            <a class="active" href="<?= BASE_URL ?>/teams">Equipes</a>
            <a href="<?= BASE_URL ?>/positions">Cargos</a>
            <a href="<?= BASE_URL ?>/employees">Colaboradores</a>
            <a href="<?= BASE_URL ?>/jobs">Vagas</a>
            <a href="<?= BASE_URL ?>/candidates">Candidatos</a>
            <a href="<?= BASE_URL ?>/surveys">Pesquisas</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <h1>Editar Equipe</h1>

        <div class="card">

            <form method="POST" action="<?= BASE_URL ?>/teams/update">

                <input
                    type="hidden"
                    name="id"
                    value="<?= $team['id'] ?>"
                >

                <label>Empresa</label>
                <select name="company_id" id="company_id" required>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?= $company['id'] ?>" <?= (int) $team['company_id'] === (int) $company['id'] ? 'selected' : '' ?>>
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
                            <?= (int) $team['branch_id'] === (int) $branch['id'] ? 'selected' : '' ?>
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
                            <?= (int) $team['department_id'] === (int) $department['id'] ? 'selected' : '' ?>
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
                            <?= (int) ($team['leader_id'] ?? 0) === (int) $leader['id'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($leader['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Nome da Equipe</label>
                <input type="text" name="name" value="<?= htmlspecialchars($team['name']) ?>" required>

                <label>Código</label>
                <input type="text" name="code" value="<?= htmlspecialchars($team['code'] ?? '') ?>">

                <label>Descrição</label>
                <textarea name="description" rows="4"><?= htmlspecialchars($team['description'] ?? '') ?></textarea>

                <label>Status</label>
                <select name="active">
                    <option value="1" <?= $team['active'] ? 'selected' : '' ?>>Ativa</option>
                    <option value="0" <?= !$team['active'] ? 'selected' : '' ?>>Inativa</option>
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
    const leaderSelect = document.getElementById('leader_id');

    const currentBranchId = '<?= (int) $team['branch_id'] ?>';
    const currentDepartmentId = '<?= (int) $team['department_id'] ?>';
    const currentLeaderId = '<?= (int) ($team['leader_id'] ?? 0) ?>';

    const allBranchOptions = Array.from(branchSelect.querySelectorAll('option[data-company-id]'));
    const allDepartmentOptions = Array.from(departmentSelect.querySelectorAll('option[data-company-id]'));
    const allLeaderOptions = Array.from(leaderSelect.querySelectorAll('option[data-company-id]'));

    function filterBranchesByCompany() {
        const selectedCompanyId = companySelect.value;

        branchSelect.innerHTML = '';
        departmentSelect.innerHTML = '';
        leaderSelect.innerHTML = '<option value="">Sem gestor</option>';

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
        leaderSelect.innerHTML = '<option value="">Sem gestor</option>';

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

        filterLeadersByDepartment();
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

        const selectedLeader = leaderSelect.querySelector('option[value="' + currentLeaderId + '"]');
        if (selectedLeader) {
            selectedLeader.selected = true;
        }
    }

    companySelect.addEventListener('change', filterBranchesByCompany);
    branchSelect.addEventListener('change', filterDepartmentsByBranch);
    departmentSelect.addEventListener('change', filterLeadersByDepartment);

    filterBranchesByCompany();
</script>

</body>

</html>
