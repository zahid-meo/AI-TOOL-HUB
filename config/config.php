<?php
/**
 * Global configuration.
 * Values come from the optional ".env" file in the project root (see .env.example).
 */
declare(strict_types=1);

if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        $v = $_ENV[$key] ?? getenv($key);
        return ($v === false || $v === null || $v === '') ? $default : $v;
    }
}

(static function (): void {
    $file = dirname(__DIR__) . '/.env';
    if (!is_file($file)) {
        return;
    }
    foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) {
            continue;
        }
        [$k, $v] = explode('=', $line, 2);
        $_ENV[trim($k)] = trim($v, " \t\"'");
    }
})();

define('ROOT_PATH', dirname(__DIR__));
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('STORAGE_PATH', ROOT_PATH . '/storage');

define('APP_ENV', (string) env('APP_ENV', 'production'));
define('APP_NAME', (string) env('APP_NAME', 'AI Tools Hub'));
define('APP_TAGLINE', 'Free Online Tools for Students, Freelancers & Creators');
define('APP_VERSION', '2.0.0');
define('APP_YEAR', date('Y'));
define('CLEAN_URLS', filter_var(env('CLEAN_URLS', 'true'), FILTER_VALIDATE_BOOLEAN));
define('APP_SECRET', (string) env('APP_SECRET', 'change-me'));

define('DB_HOST', (string) env('DB_HOST', 'localhost'));
define('DB_PORT', (string) env('DB_PORT', '3306'));
define('DB_NAME', (string) env('DB_NAME', 'ai_tool_hub'));
define('DB_USER', (string) env('DB_USER', 'root'));
define('DB_PASS', (string) env('DB_PASS', ''));
define('DB_DSN_OVERRIDE', (string) env('DB_DSN', ''));   // e.g. sqlite:/path/file.db (local testing only)

define('CONTACT_EMAIL', (string) env('CONTACT_EMAIL', 'zahidchoudhary797@gmail.com'));
define('CONTACT_WHATSAPP', (string) env('CONTACT_WHATSAPP', '923042211410'));
define('MAIL_NOTIFY', filter_var(env('MAIL_NOTIFY', 'false'), FILTER_VALIDATE_BOOLEAN));

define('ADSENSE_CLIENT', (string) env('ADSENSE_CLIENT', ''));
define('ADSENSE_SLOT_HEADER', (string) env('ADSENSE_SLOT_HEADER', ''));
define('ADSENSE_SLOT_INLINE', (string) env('ADSENSE_SLOT_INLINE', ''));
define('ADSENSE_SLOT_FOOTER', (string) env('ADSENSE_SLOT_FOOTER', ''));
define('SHOW_AD_PLACEHOLDERS', filter_var(env('SHOW_AD_PLACEHOLDERS', 'true'), FILTER_VALIDATE_BOOLEAN));
define('GA_MEASUREMENT_ID', (string) env('GA_MEASUREMENT_ID', ''));

if (APP_ENV === 'production') {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', STORAGE_PATH . '/logs/php-error.log');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}
