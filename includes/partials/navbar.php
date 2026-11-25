<?php
$navItems = [
    'home'    => ['Home',    url('index.php')],
    'tools'   => ['Tools',   url('index.php') . '#tools'],
    'about'   => ['About',   url('about.php')],
    'contact' => ['Contact', url('contact.php')],
];
?>
<nav class="navbar" aria-label="Main navigation">
  <div class="container">
    <a href="<?= e(url('index.php')) ?>" class="navbar__brand">
      <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 0L15.09 7.36L23 8.51L17 13.97L18.18 22L12 18.27L5.82 22L7 13.97L1 8.51L8.91 7.36L12 0Z"/></svg>
      <?= e(APP_NAME) ?>
    </a>
    <ul class="navbar__links">
      <?php foreach ($navItems as $key => [$label, $href]): ?>
        <li><a href="<?= e($href) ?>" class="<?= $activeNav === $key ? 'active' : '' ?>"><?= e($label) ?></a></li>
      <?php endforeach; ?>
    </ul>
    <div class="navbar__actions">
      <button class="theme-btn" id="theme-toggle" type="button" aria-label="Toggle dark mode">
        <svg id="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z"/></svg>
        <svg id="icon-sun" class="hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
      </button>
      <button class="hamburger" id="hamburger-btn" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-nav">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
      </button>
    </div>
  </div>
  <div class="mobile-nav" id="mobile-nav">
    <?php foreach ($navItems as [$label, $href]): ?>
      <a href="<?= e($href) ?>"><?= e($label) ?></a>
    <?php endforeach; ?>
    <a href="<?= e(url('privacy-policy.php')) ?>">Privacy</a>
    <a href="<?= e(url('terms.php')) ?>">Terms</a>
  </div>
</nav>
