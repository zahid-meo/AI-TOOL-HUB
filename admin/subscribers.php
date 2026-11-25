<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
admin_require();

if (isset($_GET['export'])) {
    $rows = db_all('SELECT email, is_active, subscribed_at FROM newsletter_subscribers ORDER BY subscribed_at DESC');
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="subscribers-' . date('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['email', 'active', 'subscribed_at']);
    foreach ($rows as $r) {
        $email = preg_match('/^[=+\-@]/', $r['email']) ? "'" . $r['email'] : $r['email'];   // CSV-injection guard
        fputcsv($out, [$email, $r['is_active'], $r['subscribed_at']]);
    }
    fclose($out);
    exit;
}

if (is_post()) {
    if (!csrf_valid()) {
        flash('error', 'Session expired. Please try again.');
        redirect(url('admin/subscribers.php'));
    }
    $id = (int) ($_POST['id'] ?? 0);
    $action = (string) ($_POST['action'] ?? '');
    if ($id > 0) {
        if ($action === 'deactivate') { db_run('UPDATE newsletter_subscribers SET is_active = 0, unsubscribed_at = ? WHERE id = ?', [now(), $id]); }
        if ($action === 'activate')   { db_run('UPDATE newsletter_subscribers SET is_active = 1, unsubscribed_at = NULL WHERE id = ?', [$id]); }
        if ($action === 'delete')     { db_run('DELETE FROM newsletter_subscribers WHERE id = ?', [$id]); flash('success', 'Subscriber deleted.'); }
    }
    redirect(url('admin/subscribers.php'));
}

$adminTitle = 'Subscribers';
$adminActive = 'subscribers';
$subs = db_all('SELECT id, email, is_active, subscribed_at FROM newsletter_subscribers ORDER BY subscribed_at DESC LIMIT 500');

require INCLUDES_PATH . '/admin-header.php';
?>
<p style="margin-bottom:1rem"><a class="btn btn-secondary" href="?export=1">⬇ Export CSV</a></p>
<div class="panel">
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Email</th><th>Status</th><th>Subscribed</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($subs as $s): ?>
        <tr>
          <td><?= e($s['email']) ?></td>
          <td><?= $s['is_active'] ? 'Active' : 'Unsubscribed' ?></td>
          <td><?= e($s['subscribed_at']) ?></td>
          <td>
            <form method="post" class="inline-form">
              <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $s['id'] ?>">
              <button class="btn btn-secondary btn-sm" name="action" value="<?= $s['is_active'] ? 'deactivate' : 'activate' ?>"><?= $s['is_active'] ? 'Unsubscribe' : 'Re-activate' ?></button>
              <button class="btn btn-ghost btn-sm" name="action" value="delete" onclick="return confirm('Delete this subscriber?')">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$subs): ?><tr><td colspan="4" class="muted">No subscribers yet.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require INCLUDES_PATH . '/admin-footer.php'; ?>
