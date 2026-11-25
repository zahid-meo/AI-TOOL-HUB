<?php
/**
 * Helper functions shared by pages, APIs and the admin panel.
 */
declare(strict_types=1);

/* ---------- Output / URLs ---------- */

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function is_https(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
}

function base_path(): string
{
    static $base = null;
    if ($base !== null) {
        return $base;
    }
    $override = env('APP_BASE_PATH');
    if ($override !== null) {
        return $base = rtrim((string) $override, '/');
    }
    $doc  = realpath($_SERVER['DOCUMENT_ROOT'] ?? '') ?: '';
    $root = realpath(ROOT_PATH) ?: '';
    if ($doc !== '' && $root !== '' && str_starts_with($root, $doc)) {
        $base = rtrim(str_replace('\\', '/', substr($root, strlen($doc))), '/');
    } else {
        $base = '';
    }
    return $base;
}

/** Internal URL (path only). url('tools/word-counter.php') => /tools/word-counter */
function url(string $path = ''): string
{
    $path = ltrim($path, '/');
    if (CLEAN_URLS) {
        if ($path === 'index.php' || $path === 'index') {
            $path = '';
        } elseif (str_ends_with($path, '.php')) {
            $path = substr($path, 0, -4);
        }
    } elseif ($path === 'index') {
        $path = 'index.php';
    }
    return base_path() . '/' . $path;
}

function site_url(): string
{
    $configured = (string) env('APP_URL', '');
    if ($configured !== '') {
        return rtrim($configured, '/');
    }
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return (is_https() ? 'https://' : 'http://') . $host . base_path();
}

function abs_url(string $path = ''): string
{
    $u = url($path);
    $b = base_path();
    if ($b !== '' && str_starts_with($u, $b)) {
        $u = substr($u, strlen($b));
    }
    return site_url() . $u;
}

function asset(string $path): string
{
    $file = ROOT_PATH . '/assets/' . ltrim($path, '/');
    $v = is_file($file) ? (string) filemtime($file) : APP_VERSION;
    return base_path() . '/assets/' . ltrim($path, '/') . '?v=' . $v;
}

function redirect(string $to): never
{
    header('Location: ' . $to);
    exit;
}

/* ---------- Request helpers ---------- */

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function input_str(string $key, int $max = 500): string
{
    $v = $_POST[$key] ?? '';
    if (!is_string($v)) {
        return '';
    }
    $v = trim(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', $v) ?? '');
    return mb_substr($v, 0, $max);
}

function client_ip(): string
{
    return (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
}

function ip_hash(): string
{
    return hash('sha256', client_ip() . '|' . APP_SECRET);
}

function now(): string
{
    return date('Y-m-d H:i:s');
}

/* ---------- Security ---------- */

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_valid(?string $token = null): bool
{
    $token ??= (string) ($_POST['csrf'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? ''));
    return $token !== '' && !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

/**
 * Simple rate limiter. Uses the DB when available, the session otherwise.
 * Returns true when the visitor is OVER the limit.
 */
function rate_limited(string $bucket, int $max, int $windowSeconds): bool
{
    $since = date('Y-m-d H:i:s', time() - $windowSeconds);
    if (db_available()) {
        $count = (int) db_value(
            'SELECT COUNT(*) FROM rate_limits WHERE bucket = ? AND ip_hash = ? AND created_at >= ?',
            [$bucket, ip_hash(), $since],
            0
        );
        if ($count >= $max) {
            return true;
        }
        db_run('INSERT INTO rate_limits (bucket, ip_hash, created_at) VALUES (?, ?, ?)', [$bucket, ip_hash(), now()]);
        if (random_int(1, 50) === 1) {   // opportunistic cleanup
            db_run('DELETE FROM rate_limits WHERE created_at < ?', [date('Y-m-d H:i:s', time() - 86400)]);
        }
        return false;
    }
    $key = 'rl_' . $bucket;
    $hits = array_filter($_SESSION[$key] ?? [], static fn ($t) => $t >= time() - $windowSeconds);
    if (count($hits) >= $max) {
        $_SESSION[$key] = $hits;
        return true;
    }
    $hits[] = time();
    $_SESSION[$key] = $hits;
    return false;
}

function app_log(string $message): void
{
    $dir = STORAGE_PATH . '/logs';
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    @file_put_contents($dir . '/app.log', '[' . date('c') . '] ' . $message . PHP_EOL, FILE_APPEND | LOCK_EX);
}

/* ---------- JSON API helpers ---------- */

function json_out(array $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/** Guard for POST-only JSON endpoints: method + CSRF. */
function api_guard_post(): void
{
    if (!is_post()) {
        json_out(['ok' => false, 'message' => 'Method not allowed.'], 405);
    }
    if (!csrf_valid()) {
        json_out(['ok' => false, 'message' => 'Session expired. Please refresh the page and try again.'], 419);
    }
}

/* ---------- Flash messages ---------- */

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function flash_render(): string
{
    $out = '';
    foreach ($_SESSION['flash'] ?? [] as $f) {
        $out .= '<div class="alert alert--' . e($f['type']) . '">' . e($f['message']) . '</div>';
    }
    unset($_SESSION['flash']);
    return $out;
}

/* ---------- Ads ---------- */

/** Renders a real AdSense unit when configured, or a neutral placeholder otherwise. */
function ad_slot(string $position = 'inline'): void
{
    $slot = match ($position) {
        'header' => ADSENSE_SLOT_HEADER,
        'footer' => ADSENSE_SLOT_FOOTER,
        default  => ADSENSE_SLOT_INLINE,
    };
    if (ADSENSE_CLIENT !== '' && $slot !== '') {
        echo '<div class="adsense-slot adsense-slot--live"><ins class="adsbygoogle" style="display:block" data-ad-client="'
            . e(ADSENSE_CLIENT) . '" data-ad-slot="' . e($slot) . '" data-ad-format="auto" data-full-width-responsive="true"></ins>'
            . '<script>(adsbygoogle=window.adsbygoogle||[]).push({});</script></div>';
        return;
    }
    if (SHOW_AD_PLACEHOLDERS) {
        echo '<div class="adsense-slot" aria-hidden="true">Ad Space — ' . e(ucfirst($position)) . '</div>';
    }
}

/* ---------- Admin auth ---------- */

function admin_logged_in(): bool
{
    return !empty($_SESSION['admin_id']);
}

function admin_require(): void
{
    if (!admin_logged_in()) {
        redirect(url('admin/login.php'));
    }
}
