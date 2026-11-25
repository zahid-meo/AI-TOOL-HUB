<?php
/** POST /api/feedback.php — thumbs up / down for a tool. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';

api_guard_post();

$slug   = input_str('slug', 80);
$rating = input_str('rating', 1) === '1' ? 1 : 0;

if (!tool_get($slug)) {
    json_out(['ok' => false, 'message' => 'Unknown tool.'], 422);
}
if (rate_limited('feedback', 10, 3600)) {
    json_out(['ok' => false, 'message' => 'Too many votes. Please try again later.'], 429);
}
if (!db_available()) {
    json_out(['ok' => false, 'message' => 'Feedback is temporarily unavailable.'], 503);
}

$toolId = ensure_tool_id($slug);
if (!$toolId) {
    json_out(['ok' => false, 'message' => 'Could not record feedback.'], 500);
}

$today = date('Y-m-d') . ' 00:00:00';
$already = db_value('SELECT COUNT(*) FROM tool_feedback WHERE tool_id = ? AND ip_hash = ? AND created_at >= ?', [$toolId, ip_hash(), $today], 0);
if ((int) $already > 0) {
    json_out(['ok' => true, 'message' => 'Thanks — you already voted today.']);
}

db_run('INSERT INTO tool_feedback (tool_id, rating, ip_hash, created_at) VALUES (?, ?, ?, ?)', [$toolId, $rating, ip_hash(), now()]);
json_out(['ok' => true, 'message' => 'Thanks for the feedback!']);
