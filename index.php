<?php
require_once __DIR__ . '/includes/bootstrap.php';

$cats        = tool_categories();
$allTools    = tools_all();
$popular     = popular_tools(6);
$activeNav   = 'home';
$needSearchIndex = true;
$jsonLd = [[
    '@context' => 'https://schema.org', '@type' => 'WebSite',
    'name' => APP_NAME, 'url' => abs_url('index.php'), 'description' => APP_TAGLINE,
    'author' => ['@type' => 'Person', 'name' => 'Muhammad Zahid'],
]];

require INCLUDES_PATH . '/header.php';
?>
<section class="hero">
  <h1>Every tool you need, in one place</h1>
  <p>Free, fast, privacy-friendly utilities for students, freelancers, office workers and creators. Most tools run in your browser — your files never leave your device.</p>

  <div class="search-box" role="search">
    <svg class="search-box__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
    <input type="search" id="tool-search" placeholder="Search tools… (try “compress”, “gpa”, “password”)" autocomplete="off" aria-label="Search tools">
    <kbd>/</kbd>
    <div class="search-results hidden" id="search-results"></div>
  </div>

  <div class="stats-strip">
    <div><strong><?= count($allTools) ?></strong> free tools</div>
    <div><strong><?= count($cats) ?></strong> categories</div>
    <div><strong>0</strong> sign-ups needed</div>
    <div><strong>100%</strong> in-browser privacy</div>
  </div>
</section>

<div class="container">
  <section id="favorites-section" class="hidden">
    <div class="section-title"><h2>⭐ Your favorites</h2></div>
    <div class="chip-row" id="favorites-row"></div>
  </section>
  <section id="recent-section" class="hidden">
    <div class="section-title"><h2>🕘 Recently used</h2></div>
    <div class="chip-row" id="recent-row"></div>
  </section>

  <section id="popular">
    <div class="section-title"><h2>🔥 Popular tools</h2></div>
    <div class="tools-grid">
      <?php foreach ($popular as $t) { require INCLUDES_PATH . '/partials/tool-card.php'; } ?>
    </div>
  </section>

  <section id="tools">
    <div class="section-title"><h2>All tools</h2><small><?= count($allTools) ?> tools</small></div>
    <div class="filter-bar" style="padding:0 0 1.25rem; justify-content:flex-start">
      <button type="button" class="filter-pill active" data-filter="all">All</button>
      <?php foreach ($cats as $slug => $c): ?>
        <button type="button" class="filter-pill" data-filter="<?= e($slug) ?>"><?= $c['icon'] ?> <?= e($c['name']) ?></button>
      <?php endforeach; ?>
    </div>
    <div class="tools-grid" id="tools-grid">
      <?php foreach ($allTools as $t) { require INCLUDES_PATH . '/partials/tool-card.php'; } ?>
    </div>
  </section>

  <section class="seo-content" style="max-width:none">
    <h2>Why use <?= e(APP_NAME) ?>?</h2>
    <div class="stat-grid" style="margin-top:1rem">
      <div class="stat"><b>🔒 Private</b><span>Images and text are processed locally in your browser.</span></div>
      <div class="stat"><b>⚡ Fast</b><span>No installs, no sign-ups and lightweight pages.</span></div>
      <div class="stat"><b>🆓 Free</b><span>Every tool is free to use, forever.</span></div>
      <div class="stat"><b>📱 Responsive</b><span>Works on phones, tablets and desktops, with dark mode.</span></div>
    </div>
  </section>
</div>
<?php ad_slot('inline'); ?>
<?php require INCLUDES_PATH . '/footer.php'; ?>
