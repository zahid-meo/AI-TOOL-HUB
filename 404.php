<?php
require_once __DIR__ . '/includes/bootstrap.php';
http_response_code(404);
$pageTitle = 'Page not found';
$pageRobots = 'noindex,follow';
$needSearchIndex = true;
require INCLUDES_PATH . '/header.php';
?>
<div class="container not-found">
  <h1>404</h1>
  <h2>We couldn’t find that page</h2>
  <p class="muted">The link may be broken or the page may have moved. Try searching for a tool:</p>
  <div class="search-box" role="search">
    <svg class="search-box__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
    <input type="search" id="tool-search" placeholder="Search tools…" autocomplete="off" aria-label="Search tools">
    <div class="search-results hidden" id="search-results"></div>
  </div>
  <p style="margin-top:2rem"><a class="btn btn-primary" href="<?= e(url('index.php')) ?>">Back to home</a></p>
</div>
<?php require INCLUDES_PATH . '/footer.php'; ?>
