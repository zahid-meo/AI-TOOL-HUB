<?php
/** Closes <main>, renders the footer, cookie notice and loads global JS. */
$cats = tool_categories();
$popular = array_values(array_filter(tools_all(), static fn ($t) => $t['popular']));
$popular = array_slice($popular, 0, 5);
$needSearchIndex = $needSearchIndex ?? false;
$pageScripts = $pageScripts ?? [];
?>
</main>
</div><!-- /.page-wrapper -->

<?php ad_slot('footer'); ?>

<footer class="site-footer">
  <div class="container">
    <div class="newsletter">
      <div>
        <h3>Get new tools in your inbox</h3>
        <p>We add new free tools regularly. No spam — unsubscribe any time.</p>
      </div>
      <form class="newsletter__form" id="newsletter-form" novalidate>
        <?= csrf_field() ?>
        <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off" aria-hidden="true">
        <input type="email" name="email" placeholder="you@example.com" required aria-label="Email address">
        <button type="submit" class="btn btn-accent">Subscribe</button>
      </form>
    </div>

    <div class="footer-grid">
      <div class="footer-col">
        <h3><?= e(APP_NAME) ?></h3>
        <p>Free, fast, privacy-friendly online utilities for students, freelancers and creators. Most tools run entirely in your browser.</p>
      </div>
      <div class="footer-col">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="<?= e(url('index.php')) ?>">Home</a></li>
          <li><a href="<?= e(url('about.php')) ?>">About</a></li>
          <li><a href="<?= e(url('contact.php')) ?>">Contact</a></li>
          <li><a href="<?= e(url('sitemap.php')) ?>">Sitemap</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h3>Popular Tools</h3>
        <ul>
          <?php foreach ($popular as $t): ?>
            <li><a href="<?= e(tool_url($t['slug'])) ?>"><?= e($t['name']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="footer-col">
        <h3>Legal</h3>
        <ul>
          <li><a href="<?= e(url('privacy-policy.php')) ?>">Privacy Policy</a></li>
          <li><a href="<?= e(url('terms.php')) ?>">Terms &amp; Conditions</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h3>Contact</h3>
        <p>Email: <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a></p>
        <p>WhatsApp: <a href="https://wa.me/<?= e(CONTACT_WHATSAPP) ?>" rel="noopener" target="_blank">+<?= e(CONTACT_WHATSAPP) ?></a></p>
      </div>
    </div>
    <div class="footer-bottom">© <?= e(APP_YEAR) ?> Muhammad Zahid. All rights reserved.</div>
  </div>
</footer>

<button class="back-to-top hidden" id="back-to-top" type="button" aria-label="Back to top">↑</button>
<div class="toast-wrap" id="toast-wrap" aria-live="polite"></div>

<div class="cookie-bar hidden" id="cookie-bar" role="dialog" aria-label="Cookie notice">
  <p>We use cookies to remember your theme and to show relevant ads. See our <a href="<?= e(url('privacy-policy.php')) ?>">Privacy Policy</a>.</p>
  <button type="button" class="btn btn-primary" id="cookie-accept">Got it</button>
</div>

<script>
  window.ZH = {
    base: <?= json_encode(base_path()) ?>,
    csrf: <?= json_encode(csrf_token()) ?>,
    api: <?= json_encode([
        'contact'    => url('api/contact.php'),
        'newsletter' => url('api/newsletter.php'),
        'track'      => url('api/track.php'),
        'feedback'   => url('api/feedback.php'),
    ]) ?>,
    tool: <?= json_encode($toolSlug ?? null) ?>
    <?php if ($needSearchIndex): ?>,
    tools: <?= json_encode(tools_search_index(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) ?>
    <?php endif; ?>
  };
</script>
<script src="<?= e(asset('js/main.js')) ?>"></script>
<?php foreach ($pageScripts as $src): ?>
<script src="<?= e(asset($src)) ?>"></script>
<?php endforeach; ?>
</body>
</html>
