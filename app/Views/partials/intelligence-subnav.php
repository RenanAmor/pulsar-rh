<?php
/**
 * Navegação das cinco áreas do Centro de Inteligência Organizacional.
 * Espera $activeTab com um dos valores: situation, changes, alerts,
 * recommendations, evolution.
 */
$intelTabs = [
    'situation'       => ['label' => 'Situação Atual', 'href' => BASE_URL . '/intelligence'],
    'changes'         => ['label' => 'Mudanças Recentes', 'href' => BASE_URL . '/intelligence/changes'],
    'alerts'          => ['label' => 'Alertas', 'href' => BASE_URL . '/intelligence/alerts'],
    'recommendations' => ['label' => 'Recomendações', 'href' => BASE_URL . '/intelligence/recommendations'],
    'evolution'       => ['label' => 'Evolução', 'href' => BASE_URL . '/intelligence/evolution'],
];
$activeTab = $activeTab ?? 'situation';
?>
<nav class="intel-subnav" aria-label="Áreas do Centro de Inteligência Organizacional">

    <?php foreach ($intelTabs as $key => $tab): ?>

        <a href="<?= $tab['href'] ?>" class="<?= $activeTab === $key ? 'active' : '' ?>"><?= htmlspecialchars($tab['label']) ?></a>

    <?php endforeach; ?>

</nav>
