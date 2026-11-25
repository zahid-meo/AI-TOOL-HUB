<?php
/**
 * Create or reset an admin user (CLI only).
 *   php scripts/create-admin.php <username> [password]
 * If the password is omitted you will be asked for it.
 */
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('CLI only.');
}

require_once dirname(__DIR__) . '/includes/bootstrap.php';

$username = $argv[1] ?? '';
if (!preg_match('/^[A-Za-z0-9_.-]{3,50}$/', $username)) {
    fwrite(STDERR, "Usage: php scripts/create-admin.php <username 3-50 chars> [password]\n");
    exit(1);
}
$password = $argv[2] ?? '';
if ($password === '') {
    fwrite(STDOUT, 'Password (min 10 chars): ');
    $password = trim((string) fgets(STDIN));
}
if (strlen($password) < 10) {
    fwrite(STDERR, "Password must be at least 10 characters.\n");
    exit(1);
}
if (!db_available()) {
    fwrite(STDERR, "Cannot connect to the database. Check your .env settings.\n");
    exit(1);
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$exists = db_value('SELECT id FROM admin_users WHERE username = ?', [$username]);
if ($exists) {
    db_run('UPDATE admin_users SET password_hash = ? WHERE id = ?', [$hash, $exists]);
    echo "Password updated for '$username'.\n";
} else {
    db_run('INSERT INTO admin_users (username, password_hash, created_at) VALUES (?, ?, ?)', [$username, $hash, now()]);
    echo "Admin '$username' created.\n";
}
