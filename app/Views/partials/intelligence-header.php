<?php
/**
 * Cabeçalho compartilhado pelas cinco áreas do Centro de Inteligência
 * Organizacional. Espera $center (saída de IntelligenceCenterPresenter).
 */
?>
<?php if (!isset($center['error'])): ?>

    <header class="intel-header">

        <div>
            <p class="intel-eyebrow">Centro de Inteligência Organizacional</p>
            <h1><?= htmlspecialchars($center['meta']['companyName'] ?? 'Sua organização') ?></h1>
            <?php if ($center['meta']['analyzedAt']): ?>
                <p class="intel-meta">Análise realizada em <?= htmlspecialchars($center['meta']['analyzedAt']) ?></p>
            <?php endif; ?>
        </div>

        <div class="intel-score-badge <?= $center['situation']['tierClass'] ?>">
            <strong><?= $center['situation']['score'] !== null ? number_format($center['situation']['score'], 0) : '—' ?></strong>
            <span><?= htmlspecialchars($center['situation']['classification'] ?? '—') ?></span>
        </div>

    </header>

<?php endif; ?>
