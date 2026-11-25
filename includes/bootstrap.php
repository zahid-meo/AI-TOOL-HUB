<?php
/**
 * Bootstrap: include this first on every page / API endpoint.
 */
declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once INCLUDES_PATH . '/functions.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once INCLUDES_PATH . '/tools-data.php';
require_once INCLUDES_PATH . '/stats.php';

if (session_status() === PHP_SESSION_NONE && PHP_SAPI !== 'cli') {
    session_name('zth_session');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => is_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    @session_start();
}
