<?php
/** Expects $crumbs = [['Label', 'url'|null], ...] */
if (empty($crumbs)) { return; }
?>
<nav class="breadcrumb container" aria-label="Breadcrumb">
  <?php foreach ($crumbs as $i => [$label, $href]): ?>
    <?php if ($i > 0): ?><span class="breadcrumb__sep">/</span><?php endif; ?>
    <?php if ($href): ?><a href="<?= e($href) ?>"><?= e($label) ?></a><?php else: ?><span aria-current="page"><?= e($label) ?></span><?php endif; ?>
  <?php endforeach; ?>
</nav>
