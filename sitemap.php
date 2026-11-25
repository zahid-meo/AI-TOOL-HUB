<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Sitemap';
$pageDesc  = 'All pages and tools on ' . APP_NAME . '.';
$crumbs = [['Home', url('index.php')], ['Sitemap', null]];
require INCLUDES_PATH . '/header.php';
require INCLUDES_PATH . '/partials/breadcrumb.php';
?>
<div class="container">
  <article class="page-card">
    <h1>Sitemap</h1>
    <h2>Pages</h2>
    <ul>
      <li><a href="<?= e(url('index.php')) ?>">Home</a></li>
      <li><a href="<?= e(url('about.php')) ?>">About</a></li>
      <li><a href="<?= e(url('contact.php')) ?>">Contact</a></li>
      <li><a href="<?= e(url('privacy-policy.php')) ?>">Privacy Policy</a></li>
      <li><a href="<?= e(url('terms.php')) ?>">Terms &amp; Conditions</a></li>
    </ul>
    <?php foreach (tool_categories() as $slug => $c): ?>
      <h2><?= $c['icon'] ?> <?= e($c['name']) ?></h2>
      <ul>
        <?php foreach (tools_by_category()[$slug] ?? [] as $t): ?>
          <li><a href="<?= e(tool_url($t['slug'])) ?>"><?= e($t['name']) ?></a> — <span class="muted"><?= e($t['short']) ?></span></li>
        <?php endforeach; ?>
      </ul>
    <?php endforeach; ?>
  </article>
</div>
<?php require INCLUDES_PATH . '/footer.php'; ?>
