<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
header('X-Robots-Tag: noindex, nofollow');

if (admin_logged_in()) {
    redirect(url('admin/index.php'));
}

$error = '';
if (is_post()) {
    if (!csrf_valid()) {
        $error = 'Session expired. Please try again.';
    } elseif (!db_available()) {
        $error = 'Database is not reachable. Check your .env settings.';
    } elseif (rate_limited('admin_login', 5, 900)) {
        $error = 'Too many attempts. Try again in 15 minutes.';
    } else {
        $user = input_str('username', 50);
        $pass = (string) ($_POST['password'] ?? '');
        $row  = db_one('SELECT id, username, password_hash FROM admin_users WHERE username = ?', [$user]);
        // Always run password_verify to keep response time similar for unknown users.
        $hash = $row['password_hash'] ?? password_hash('dummy-password', PASSWORD_DEFAULT);
        if ($row && password_verify($pass, $hash)) {
            session_regenerate_id(true);
            $_SESSION['admin_id']   = (int) $row['id'];
            $_SESSION['admin_name'] = $row['username'];
            db_run('UPDATE admin_users SET last_login_at = ? WHERE id = ?', [now(), $row['id']]);
            redirect(url('admin/index.php'));
        }
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <title>Admin login — <?= e(APP_NAME) ?></title>
  <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body>
  <form class="login-box" method="post" autocomplete="off">
    <h1>🔐 Admin login</h1>
    <?php if ($error): ?><div class="alert alert--error"><?= e($error) ?></div><?php endif; ?>
    <?= csrf_field() ?>
    <label for="username">Username</label>
    <input id="username" name="username" required autofocus>
    <label for="password" style="margin-top:1rem">Password</label>
    <input id="password" name="password" type="password" required>
    <button class="btn btn-primary" style="margin-top:1.25rem;width:100%" type="submit">Log in</button>
  </form>
</body>
</html>
