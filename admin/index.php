<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
admin_require();

$adminTitle = 'Dashboard';
$adminActive = 'dashboard';

$totals = [
    'messages'    => (int) db_value('SELECT COUNT(*) FROM contact_messages', [], 0),
    'unread'      => (int) db_value('SELECT COUNT(*) FROM contact_messages WHERE is_read = 0', [], 0),
    'subscribers' => (int) db_value('SELECT COUNT(*) FROM newsletter_subscribers WHERE is_active = 1', [], 0),
    'views'       => (int) db_value('SELECT COALESCE(SUM(views),0) FROM tool_usage_daily', [], 0),
    'uses'        => (int) db_value('SELECT COALESCE(SUM(uses),0) FROM tool_usage_daily', [], 0),
    'likes'       => (int) db_value('SELECT COUNT(*) FROM tool_feedback WHERE rating = 1', [], 0),
];

// Last 14 days of page views (fill gaps with zeros)
$from = date('Y-m-d', strtotime('-13 days'));
$rows = db_all('SELECT usage_date AS d, SUM(views) AS v FROM tool_usage_daily WHERE usage_date >= ? GROUP BY usage_date', [$from]);
$byDay = [];
foreach ($rows as $r) {
    $byDay[$r['d']] = (int) $r['v'];
}
$series = [];
for ($i = 13; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $series[$d] = $byDay[$d] ?? 0;
}
$max = max(1, max($series));

$top = db_all('SELECT name, category, total_views, total_uses, likes, dislikes FROM v_tool_stats ORDER BY total_views DESC, name ASC LIMIT 15');

require INCLUDES_PATH . '/admin-header.php';
?>
<?php if (!db_available()): ?>
  <div class="alert alert--error">Database connection failed — check your <code>.env</code> settings.</div>
<?php endif; ?>

<div class="cards">
  <div class="card"><b><?= $totals['views'] ?></b><span>Tool page views</span></div>
  <div class="card"><b><?= $totals['uses'] ?></b><span>Tool uses</span></div>
  <div class="card"><b><?= $totals['messages'] ?></b><span>Messages (<?= $totals['unread'] ?> unread)</span></div>
  <div class="card"><b><?= $totals['subscribers'] ?></b><span>Active subscribers</span></div>
  <div class="card"><b><?= $totals['likes'] ?></b><span>👍 Helpful votes</span></div>
</div>

<div class="panel">
  <h2>Page views — last 14 days</h2>
  <div class="bars">
    <?php foreach ($series as $d => $v): ?>
      <div style="height:<?= max(2, (int) round($v / $max * 100)) ?>%" data-tip="<?= e(date('M j', strtotime($d)) . ': ' . $v) ?>"></div>
    <?php endforeach; ?>
  </div>
</div>

<div class="panel">
  <h2>Top tools</h2>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Tool</th><th>Category</th><th class="num">Views</th><th class="num">Uses</th><th class="num">👍</th><th class="num">👎</th></tr></thead>
      <tbody>
      <?php foreach ($top as $t): ?>
        <tr>
          <td><?= e($t['name']) ?></td><td><?= e($t['category']) ?></td>
          <td class="num"><?= (int) $t['total_views'] ?></td><td class="num"><?= (int) $t['total_uses'] ?></td>
          <td class="num"><?= (int) $t['likes'] ?></td><td class="num"><?= (int) $t['dislikes'] ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$top): ?><tr><td colspan="6" class="muted">No data yet — open a few tools and refresh.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require INCLUDES_PATH . '/admin-footer.php'; ?>
