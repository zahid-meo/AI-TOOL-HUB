  </section>

  <?php ad_slot('inline'); ?>

  <section class="feedback" id="feedback" data-tool="<?= e($toolSlug) ?>">
    <span>Was this tool useful?</span>
    <button type="button" class="btn btn-secondary" data-rate="1">👍 Yes</button>
    <button type="button" class="btn btn-secondary" data-rate="0">👎 Not really</button>
    <span class="feedback__msg" id="feedback-msg"></span>
  </section>

  <article class="seo-content">
    <h2>About the <?= e($tool['name']) ?></h2>
    <p><?= e($tool['intro']) ?></p>
    <?php if (!empty($tool['steps'])): ?>
      <h3>How to use it</h3>
      <ol class="steps">
        <?php foreach ($tool['steps'] as $s): ?><li><?= e($s) ?></li><?php endforeach; ?>
      </ol>
    <?php endif; ?>
    <?php if (!empty($tool['faq'])): ?>
      <h3>Frequently asked questions</h3>
      <?php foreach ($tool['faq'] as [$q, $a]): ?>
        <details class="faq"><summary><?= e($q) ?></summary><p><?= e($a) ?></p></details>
      <?php endforeach; ?>
    <?php endif; ?>
  </article>

  <section class="related">
    <h2>More free tools</h2>
    <div class="tools-grid">
      <?php foreach (tools_related($toolSlug, 4) as $t) { require INCLUDES_PATH . '/partials/tool-card.php'; } ?>
    </div>
  </section>
</div>
<?php require INCLUDES_PATH . '/footer.php'; ?>
