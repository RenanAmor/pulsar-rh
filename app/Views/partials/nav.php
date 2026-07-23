<?php
/**
 * Navegação principal do produto, compartilhada por todas as telas.
 * Espera uma variável $activeNav definida pela view que a inclui, com um
 * dos valores: dashboard, indicators, oie, history, reports, administration.
 */
$activeNav = $activeNav ?? '';
?>
<nav>

    <a href="<?= BASE_URL ?>/dashboard" class="<?= $activeNav === 'dashboard' ? 'active' : '' ?>">Dashboard</a>

    <a href="<?= BASE_URL ?>/indicators" class="<?= $activeNav === 'indicators' ? 'active' : '' ?>">Indicadores</a>

    <a href="<?= BASE_URL ?>/oie" class="<?= $activeNav === 'oie' ? 'active' : '' ?>">Inteligência Organizacional</a>

    <a href="<?= BASE_URL ?>/history" class="<?= $activeNav === 'history' ? 'active' : '' ?>">Histórico</a>

    <a href="<?= BASE_URL ?>/reports" class="<?= $activeNav === 'reports' ? 'active' : '' ?>">Relatórios</a>

    <a href="<?= BASE_URL ?>/administration" class="<?= $activeNav === 'administration' ? 'active' : '' ?>">Administração</a>

    <a href="<?= BASE_URL ?>/logout">Sair</a>

</nav>
