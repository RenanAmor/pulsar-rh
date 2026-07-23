<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Novo Setor | <?= APP_NAME ?></title>

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
            ['label' => 'Setores', 'href' => BASE_URL . '/departments'],
            ['label' => 'Novo Setor'],
        ]; require __DIR__ . '/../partials/breadcrumb.php'; ?>

        <h1>Novo Setor</h1>

        <div class="card">

            <form method="POST" action="<?= BASE_URL ?>/departments/store">

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

                <label>Nome do Setor</label>
                <input type="text" name="name" required>

                <label>Código</label>
                <input type="text" name="code">

                <label>Gestor (ID do usuário)</label>
                <input type="number" name="manager_id" min="1">

                <label>E-mail</label>
                <input type="email" name="email">

                <label>Telefone</label>
                <input type="text" name="phone">

                <label>Descrição</label>
                <textarea name="description" rows="4"></textarea>

                <label>Status</label>
                <select name="active">
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>

                <button type="submit">
                    Salvar Setor
                </button>

            </form>

        </div>

    </main>

</div>

<script>
    const companySelect = document.getElementById('company_id');
    const branchSelect = document.getElementById('branch_id');
    const allBranchOptions = Array.from(branchSelect.querySelectorAll('option[data-company-id]'));

    function filterBranchesByCompany() {
        const selectedCompanyId = companySelect.value;

        branchSelect.innerHTML = '<option value="">Selecione uma filial</option>';

        allBranchOptions.forEach((option) => {
            if (!selectedCompanyId || option.dataset.companyId === selectedCompanyId) {
                branchSelect.appendChild(option);
            }
        });
    }

    companySelect.addEventListener('change', filterBranchesByCompany);
</script>

</body>

</html>
