<?php
/**
 * Trilha de navegação padrão das áreas internas do produto.
 * Espera $breadcrumb: array de ['label' => string, 'href' => string|null].
 * O último item é sempre tratado como a página atual (sem link).
 */
$breadcrumb = $breadcrumb ?? [];
$lastIndex = count($breadcrumb) - 1;
?>
<?php if (!empty($breadcrumb)): ?>

<nav class="breadcrumb" aria-label="Trilha de navegação">

    <?php foreach ($breadcrumb as $index => $crumb): ?>

        <?php if ($index > 0): ?><span class="breadcrumb-sep">/</span><?php endif; ?>

        <?php if (!empty($crumb['href']) && $index !== $lastIndex): ?>
            <a href="<?= $crumb['href'] ?>"><?= htmlspecialchars($crumb['label']) ?></a>
        <?php else: ?>
            <span class="breadcrumb-current"><?= htmlspecialchars($crumb['label']) ?></span>
        <?php endif; ?>

    <?php endforeach; ?>

</nav>

<?php endif; ?>
