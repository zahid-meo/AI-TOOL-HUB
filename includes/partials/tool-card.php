<?php /** Expects $t (tool array) */ $cats = tool_categories(); ?>
<a href="<?= e(tool_url($t['slug'])) ?>" class="tool-card" data-cat="<?= e($t['cat']) ?>" data-slug="<?= e($t['slug']) ?>">
  <button type="button" class="fav-btn" data-fav="<?= e($t['slug']) ?>" aria-label="Add <?= e($t['name']) ?> to favorites" title="Favorite">☆</button>
  <div class="tool-card__icon <?= e($t['cat']) ?>"><?= $t['icon'] ?></div>
  <div class="tool-card__title"><?= e($t['name']) ?>
    <?php if ($t['new']): ?><span class="badge badge--new">New</span><?php endif; ?>
  </div>
  <div class="tool-card__desc"><?= e($t['short']) ?></div>
</a>
