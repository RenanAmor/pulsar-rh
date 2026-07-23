<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Novo Colaborador - <?= APP_NAME ?></title>

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
            <a class="active" href="<?= BASE_URL ?>/employees">Colaboradores</a>
            <a href="<?= BASE_URL ?>/jobs">Vagas</a>
            <a href="<?= BASE_URL ?>/candidates">Candidatos</a>
            <a href="<?= BASE_URL ?>/surveys">Pesquisas</a>
            <a href="<?= BASE_URL ?>/questions">Perguntas</a>
            <a href="<?= BASE_URL ?>/survey-questions">Montagem de Pesquisas</a>
            <a href="<?= BASE_URL ?>/answers">Respostas</a>
            <a href="<?= BASE_URL ?>/indicators">Indicadores</a>
            <a href="<?= BASE_URL ?>/laboratory">Laboratório Organizacional</a>
            <a href="<?= BASE_URL ?>/oie">OIE</a>
            <a href="<?= BASE_URL ?>/ai">Inteligência Artificial</a>
            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <h1>Novo Colaborador</h1>

        <div class="card">

            <form method="POST" action="<?= BASE_URL ?>/employees/store">

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

                <label>Cargo</label>
                <select name="position_id" id="position_id" required>
                    <option value="">Selecione um cargo</option>
                    <?php foreach ($positions as $position): ?>
                        <option
                            value="<?= $position['id'] ?>"
                            data-company-id="<?= $position['company_id'] ?>"
                            data-branch-id="<?= $position['branch_id'] ?>"
                            data-department-id="<?= $position['department_id'] ?>"
                        >
                            <?= htmlspecialchars($position['company_name']) ?> / <?= htmlspecialchars($position['branch_name']) ?> / <?= htmlspecialchars($position['department_name']) ?> / <?= htmlspecialchars($position['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Gestor</label>
                <select name="manager_id" id="manager_id">
                    <option value="">Sem gestor</option>
                    <?php foreach ($managers as $manager): ?>
                        <option
                            value="<?= $manager['id'] ?>"
                            data-company-id="<?= $manager['company_id'] ?>"
                            data-branch-id="<?= $manager['branch_id'] ?>"
                            data-department-id="<?= $manager['department_id'] ?>"
                        >
                            <?= htmlspecialchars($manager['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Nome do Colaborador</label>
                <input type="text" name="name" required>

                <label>Matrícula</label>
                <input type="text" name="registration">

                <label>CPF</label>
                <input type="text" name="cpf">

                <label>Data de Nascimento</label>
                <input type="date" name="birth_date">

                <label>Gênero</label>
                <select name="gender">
                    <option value="">Não informado</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Feminino">Feminino</option>
                    <option value="Outro">Outro</option>
                    <option value="Prefiro não informar">Prefiro não informar</option>
                </select>

                <label>E-mail</label>
                <input type="email" name="email">

                <label>Telefone</label>
                <input type="text" name="phone">

                <label>Data de Admissão</label>
                <input type="date" name="admission_date">

                <label>Data de Desligamento</label>
                <input type="date" name="termination_date">

                <label>Tipo de Contrato</label>
                <select name="employment_type">
                    <option value="CLT">CLT</option>
                    <option value="PJ">PJ</option>
                    <option value="Estágio">Estágio</option>
                    <option value="Temporário">Temporário</option>
                    <option value="Terceirizado">Terceirizado</option>
                </select>

                <label>Status</label>
                <select name="status">
                    <option value="Ativo">Ativo</option>
                    <option value="Férias">Férias</option>
                    <option value="Afastado">Afastado</option>
                    <option value="Desligado">Desligado</option>
                </select>

                <label>ID do Time (opcional)</label>
                <input type="number" name="team_id" min="1">

                <button type="submit">
                    Salvar Colaborador
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

    const allBranchOptions = Array.from(branchSelect.querySelectorAll('option[data-company-id]'));
    const allDepartmentOptions = Array.from(departmentSelect.querySelectorAll('option[data-company-id]'));
    const allPositionOptions = Array.from(positionSelect.querySelectorAll('option[data-company-id]'));
    const allManagerOptions = Array.from(managerSelect.querySelectorAll('option[data-company-id]'));

    function filterBranchesByCompany() {
        const selectedCompanyId = companySelect.value;

        branchSelect.innerHTML = '<option value="">Selecione uma filial</option>';
        departmentSelect.innerHTML = '<option value="">Selecione um setor</option>';
        positionSelect.innerHTML = '<option value="">Selecione um cargo</option>';
        managerSelect.innerHTML = '<option value="">Sem gestor</option>';

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
        positionSelect.innerHTML = '<option value="">Selecione um cargo</option>';
        managerSelect.innerHTML = '<option value="">Sem gestor</option>';

        allDepartmentOptions.forEach((option) => {
            const matchCompany = !selectedCompanyId || option.dataset.companyId === selectedCompanyId;
            const matchBranch = !selectedBranchId || option.dataset.branchId === selectedBranchId;

            if (matchCompany && matchBranch) {
                departmentSelect.appendChild(option);
            }
        });
    }

    function filterPositionsByDepartment() {
        const selectedCompanyId = companySelect.value;
        const selectedBranchId = branchSelect.value;
        const selectedDepartmentId = departmentSelect.value;

        positionSelect.innerHTML = '<option value="">Selecione um cargo</option>';
        managerSelect.innerHTML = '<option value="">Sem gestor</option>';

        allPositionOptions.forEach((option) => {
            const matchCompany = !selectedCompanyId || option.dataset.companyId === selectedCompanyId;
            const matchBranch = !selectedBranchId || option.dataset.branchId === selectedBranchId;
            const matchDepartment = !selectedDepartmentId || option.dataset.departmentId === selectedDepartmentId;

            if (matchCompany && matchBranch && matchDepartment) {
                positionSelect.appendChild(option);
            }
        });

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
    }

    companySelect.addEventListener('change', filterBranchesByCompany);
    branchSelect.addEventListener('change', filterDepartmentsByBranch);
    departmentSelect.addEventListener('change', filterPositionsByDepartment);

    filterBranchesByCompany();
</script>

</body>

</html>
