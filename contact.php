<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Contact';
$pageDesc  = 'Contact Muhammad Zahid for support, feedback, tool suggestions or collaboration.';
$activeNav = 'contact';
$crumbs = [['Home', url('index.php')], ['Contact', null]];
require INCLUDES_PATH . '/header.php';
require INCLUDES_PATH . '/partials/breadcrumb.php';
?>
<div class="container">
  <div class="page-card">
    <h1>Contact us</h1>
    <p class="lead">Found a bug, want a new tool, or have a business enquiry? Send a message — we usually reply within 1–2 working days.</p>
    <div class="contact-grid" style="margin-top:1.5rem">
      <ul class="contact-info">
        <li><strong>📧 Email</strong><br><a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a></li>
        <li><strong>💬 WhatsApp</strong><br><a href="https://wa.me/<?= e(CONTACT_WHATSAPP) ?>" target="_blank" rel="noopener">+<?= e(CONTACT_WHATSAPP) ?></a></li>
        <li><strong>🌍 Based in</strong><br>Pakistan</li>
      </ul>

      <form id="contact-form" novalidate>
        <?= csrf_field() ?>
        <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off" aria-hidden="true">
        <div id="form-result" role="status"></div>
        <label for="name">Name</label>
        <input type="text" id="name" name="name" maxlength="100" required>
        <label for="email" style="margin-top:1rem">Email</label>
        <input type="email" id="email" name="email" maxlength="190" required>
        <label for="subject" style="margin-top:1rem">Subject</label>
        <select id="subject" name="subject">
          <option>General question</option>
          <option>Suggest a new tool</option>
          <option>Report a bug</option>
          <option>Business</option>
          <option>Collaboration</option>
        </select>
        <label for="message" style="margin-top:1rem">Message</label>
        <textarea id="message" name="message" rows="6" minlength="10" maxlength="3000" required></textarea>
        <button type="submit" class="btn btn-primary" style="margin-top:1rem">Send message</button>
      </form>
    </div>
  </div>
</div>
<?php require INCLUDES_PATH . '/footer.php'; ?>
