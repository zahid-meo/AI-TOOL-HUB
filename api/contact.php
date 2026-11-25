<?php
/** POST /api/contact.php — saves a contact-form message (and optionally emails it). */
require_once dirname(__DIR__) . '/includes/bootstrap.php';

api_guard_post();

// Honeypot: bots fill the hidden field. Pretend success so they do not retry.
if (input_str('website') !== '') {
    json_out(['ok' => true, 'message' => 'Thank you! Your message has been sent.']);
}

if (rate_limited('contact', 3, 3600)) {
    json_out(['ok' => false, 'message' => 'Too many messages. Please try again later.'], 429);
}

$name    = input_str('name', 100);
$email   = input_str('email', 190);
$subject = input_str('subject', 100);
$message = input_str('message', 3000);

$allowedSubjects = ['General question', 'Suggest a new tool', 'Report a bug', 'Business / collaboration'];
if (!in_array($subject, $allowedSubjects, true)) {
    $subject = 'General question';
}
if (mb_strlen($name) < 2) {
    json_out(['ok' => false, 'message' => 'Please enter your name.'], 422);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_out(['ok' => false, 'message' => 'Please enter a valid email address.'], 422);
}
if (mb_strlen($message) < 10) {
    json_out(['ok' => false, 'message' => 'Your message is too short (minimum 10 characters).'], 422);
}

$saved = false;
if (db_available()) {
    $st = db_run(
        'INSERT INTO contact_messages (name, email, subject, message, ip_hash, user_agent, is_read, created_at) VALUES (?, ?, ?, ?, ?, ?, 0, ?)',
        [$name, $email, $subject, $message, ip_hash(), mb_substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255), now()]
    );
    $saved = $st !== null;
}

$mailed = false;
if (MAIL_NOTIFY || !$saved) {
    $cleanName  = preg_replace('/[\r\n]+/', ' ', $name);
    $headers    = 'From: ' . APP_NAME . ' <no-reply@' . preg_replace('/^www\./', '', (string) ($_SERVER['HTTP_HOST'] ?? 'localhost')) . ">\r\n"
                . 'Reply-To: ' . $cleanName . ' <' . $email . ">\r\nContent-Type: text/plain; charset=UTF-8\r\n";
    $mailed = @mail(CONTACT_EMAIL, '[' . APP_NAME . '] ' . $subject, "From: $cleanName <$email>\n\n$message", $headers);
}

if ($saved || $mailed) {
    json_out(['ok' => true, 'message' => 'Thank you, ' . $name . '! Your message has been sent. We will reply to ' . $email . ' soon.']);
}
json_out(['ok' => false, 'message' => 'We could not send your message right now. Please email ' . CONTACT_EMAIL . ' directly.'], 503);
