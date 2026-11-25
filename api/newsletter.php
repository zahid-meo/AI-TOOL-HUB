<?php
/** POST /api/newsletter.php — subscribe an email address. */
require_once dirname(__DIR__) . '/includes/bootstrap.php';

api_guard_post();

if (input_str('website') !== '') {
    json_out(['ok' => true, 'message' => 'Thanks for subscribing!']);
}
if (rate_limited('newsletter', 5, 3600)) {
    json_out(['ok' => false, 'message' => 'Too many attempts. Please try again later.'], 429);
}

$email = strtolower(input_str('email', 190));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_out(['ok' => false, 'message' => 'Please enter a valid email address.'], 422);
}
if (!db_available()) {
    json_out(['ok' => false, 'message' => 'Subscriptions are temporarily unavailable. Please try again later.'], 503);
}

$existing = db_one('SELECT id, is_active FROM newsletter_subscribers WHERE email = ?', [$email]);
if ($existing) {
    if ((int) $existing['is_active'] === 0) {
        db_run('UPDATE newsletter_subscribers SET is_active = 1, unsubscribed_at = NULL WHERE id = ?', [$existing['id']]);
        json_out(['ok' => true, 'message' => 'Welcome back! You are subscribed again.']);
    }
    json_out(['ok' => true, 'message' => 'You are already subscribed. Thank you!']);
}

$st = db_run(
    'INSERT INTO newsletter_subscribers (email, token, is_active, subscribed_at) VALUES (?, ?, 1, ?)',
    [$email, bin2hex(random_bytes(16)), now()]
);
if (!$st) {
    json_out(['ok' => false, 'message' => 'Could not subscribe right now. Please try again.'], 500);
}
json_out(['ok' => true, 'message' => 'Thanks for subscribing! 🎉']);
