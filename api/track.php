<?php
/** POST /api/track.php — anonymous per-tool counters (type = view | use). */
require_once dirname(__DIR__) . '/includes/bootstrap.php';

if (!is_post() || !csrf_valid()) {
    http_response_code(204);
    exit;
}

$slug = input_str('slug', 80);
$type = input_str('type', 10) === 'use' ? 'use' : 'view';

if (!tool_get($slug)) {
    http_response_code(204);
    exit;
}

// Count a "view" once per visitor session per tool per 30 minutes; cap "use" events.
$key = $type . ':' . $slug;
$last = $_SESSION['tracked'][$key] ?? 0;
if ($type === 'view' && $last > time() - 1800) {
    http_response_code(204);
    exit;
}
$_SESSION['tracked'][$key] = time();
$_SESSION['track_count'] = ($_SESSION['track_count'] ?? 0) + 1;
if ($_SESSION['track_count'] > 200) {
    http_response_code(204);
    exit;
}

record_usage($slug, $type);
http_response_code(204);
