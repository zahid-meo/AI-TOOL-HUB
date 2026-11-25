<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'About';
$pageDesc  = 'Learn about Muhammad Zahid and ' . APP_NAME . ', a free collection of fast, privacy-friendly online utilities for students, freelancers and creators.';
$activeNav = 'about';
$crumbs = [['Home', url('index.php')], ['About', null]];
require INCLUDES_PATH . '/header.php';
require INCLUDES_PATH . '/partials/breadcrumb.php';
?>
<div class="container">
  <article class="page-card">
    <h1>About <?= e(APP_NAME) ?></h1>
    <p class="lead"><?= e(APP_NAME) ?> is a collection of fast, free online utilities that help students, freelancers, office workers and creators save time.</p>

    <h2>Our vision</h2>
    <p>Everyday tasks — compressing a photo, calculating a CGPA, checking a loan instalment, counting words — should not need a sign-up or a heavy app. We build simple tools that open instantly, work on any device and respect your privacy. Most tools run entirely in your browser, so your files and text are never uploaded.</p>

    <h2>What you will find here</h2>
    <ul>
      <?php foreach (tool_categories() as $slug => $c): ?>
        <li><strong><?= $c['icon'] ?> <?= e($c['name']) ?></strong> —
          <?= e(implode(', ', array_map(static fn ($t) => $t['name'], tools_by_category()[$slug] ?? []))) ?></li>
      <?php endforeach; ?>
    </ul>

    <h2>The creator</h2>
    <p><strong>Muhammad Zahid</strong> is a software developer from Pakistan who enjoys building practical, performance-focused web and mobile products.</p>
    <ul>
      <li>Email: <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a></li>
      <li>WhatsApp: <a href="https://wa.me/<?= e(CONTACT_WHATSAPP) ?>" target="_blank" rel="noopener">+<?= e(CONTACT_WHATSAPP) ?></a></li>
    </ul>

    <h2>Open source</h2>
    <p>The source code is released under the MIT License, so anyone can study, modify and host the project.</p>

    <h2>How the site is funded</h2>
    <p>The tools are free. The site may show Google AdSense advertisements to cover hosting costs. Ads are clearly separated from the tools and never required to use them.</p>

    <p style="margin-top:1.5rem"><a class="btn btn-primary" href="<?= e(url('index.php')) ?>#tools">Explore the tools</a>
       <a class="btn btn-secondary" href="<?= e(url('contact.php')) ?>">Suggest a tool</a></p>
  </article>
</div>
<?php require INCLUDES_PATH . '/footer.php'; ?>
