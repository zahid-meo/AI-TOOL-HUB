<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
admin_require();

if (is_post()) {
    if (!csrf_valid()) {
        flash('error', 'Session expired. Please try again.');
        redirect(url('admin/messages.php'));
    }
    $id = (int) ($_POST['id'] ?? 0);
    $action = (string) ($_POST['action'] ?? '');
    if ($id > 0) {
        if ($action === 'read')   { db_run('UPDATE contact_messages SET is_read = 1 WHERE id = ?', [$id]); }
        if ($action === 'unread') { db_run('UPDATE contact_messages SET is_read = 0 WHERE id = ?', [$id]); }
        if ($action === 'delete') { db_run('DELETE FROM contact_messages WHERE id = ?', [$id]); flash('success', 'Message deleted.'); }
    }
    redirect(url('admin/messages.php'));
}

$adminTitle = 'Messages';
$adminActive = 'messages';
$messages = db_all('SELECT id, name, email, subject, message, is_read, created_at FROM contact_messages ORDER BY created_at DESC, id DESC LIMIT 100');

require INCLUDES_PATH . '/admin-header.php';
?>
<div class="panel">
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Date</th><th>From</th><th>Subject</th><th>Message</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($messages as $m): ?>
        <tr class="<?= $m['is_read'] ? '' : 'msg-unread' ?>">
          <td><?= e($m['created_at']) ?></td>
          <td><?= e($m['name']) ?><br><a href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a></td>
          <td><?= e($m['subject']) ?></td>
          <td class="wrap"><details><summary><?= e(mb_strimwidth($m['message'], 0, 80, '…')) ?></summary><div class="msg-body"><?= e($m['message']) ?></div></details></td>
          <td>
            <form method="post" class="inline-form">
              <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $m['id'] ?>">
              <button class="btn btn-secondary btn-sm" name="action" value="<?= $m['is_read'] ? 'unread' : 'read' ?>"><?= $m['is_read'] ? 'Mark unread' : 'Mark read' ?></button>
              <button class="btn btn-ghost btn-sm" name="action" value="delete" onclick="return confirm('Delete this message?')">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$messages): ?><tr><td colspan="5" class="muted">No messages yet.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require INCLUDES_PATH . '/admin-footer.php'; ?>
