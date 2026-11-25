<?php
/** Admin layout top. Set $adminTitle and $adminActive before including. */
$adminTitle  = $adminTitle  ?? 'Dashboard';
$adminActive = $adminActive ?? 'dashboard';
$unread = (int) db_value('SELECT COUNT(*) FROM contact_messages WHERE is_read = 0', [], 0);
header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-store');
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <title><?= e($adminTitle) ?> — Admin — <?= e(APP_NAME) ?></title>
  <script>try{var t=localStorage.getItem('zh-theme');if(t)document.documentElement.setAttribute('data-theme',t);}catch(e){}</script>
  <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body>
<div class="admin-shell">
  <aside class="admin-side">
    <h2>⚙️ Admin</h2>
    <a href="<?= e(url('admin/index.php')) ?>" class="<?= $adminActive === 'dashboard' ? 'active' : '' ?>">📊 Dashboard</a>
    <a href="<?= e(url('admin/messages.php')) ?>" class="<?= $adminActive === 'messages' ? 'active' : '' ?>">✉️ Messages<?= $unread ? ' (' . $unread . ')' : '' ?></a>
    <a href="<?= e(url('admin/subscribers.php')) ?>" class="<?= $adminActive === 'subscribers' ? 'active' : '' ?>">📬 Subscribers</a>
    <a href="<?= e(url('index.php')) ?>" target="_blank" rel="noopener">🌐 View site</a>
    <a href="<?= e(url('admin/logout.php')) ?>">🚪 Log out</a>
  </aside>
  <main class="admin-main">
    <h1><?= e($adminTitle) ?></h1>
    <?= flash_render() ?>
