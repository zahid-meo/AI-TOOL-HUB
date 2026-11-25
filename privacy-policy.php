<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Privacy Policy';
$pageDesc  = 'How ' . APP_NAME . ' collects, uses and protects information, including cookies, Google AdSense and your GDPR rights.';
$crumbs = [['Home', url('index.php')], ['Privacy Policy', null]];
require INCLUDES_PATH . '/header.php';
require INCLUDES_PATH . '/partials/breadcrumb.php';
?>
<div class="container">
  <article class="page-card">
    <h1>Privacy Policy</h1>
    <p class="updated">Last updated: 21 November 2026</p>

    <h2>1. Introduction</h2>
    <p>This Privacy Policy explains how <?= e(APP_NAME) ?> (“we”, “our”, “the site”) collects, uses and protects information about visitors who use our web-based utilities.</p>

    <h2>2. Information we collect</h2>
    <ul>
      <li><strong>Files and text you process in the tools</strong> — Image tools, calculators and text tools run in your browser. The files and text you enter are <em>not</em> uploaded to our servers.</li>
      <li><strong>Contact form</strong> — If you send a message we store your name, email address, subject and message so we can reply. A hashed (non-reversible) form of your IP address and your browser user-agent are stored to prevent spam.</li>
      <li><strong>Newsletter</strong> — If you subscribe, we store your email address until you unsubscribe.</li>
      <li><strong>Anonymous usage statistics</strong> — We count how many times each tool is opened or used per day. These counters are not linked to you. Optional helpful/not helpful votes are stored with a hashed IP to prevent duplicates.</li>
      <li><strong>Technical data</strong> — Like most websites, our server and third-party services (Google AdSense / Analytics, if enabled) may receive your IP address, device type, browser, language and referring page.</li>
    </ul>

    <h2>3. Cookies and local storage</h2>
    <ul>
      <li><strong>Session cookie</strong> — a strictly necessary cookie used for security (CSRF protection) and form handling.</li>
      <li><strong>Local storage</strong> — stores your theme (light/dark), favorite tools and recently used tools on your device only.</li>
      <li><strong>Advertising cookies</strong> — Google and its partners may use cookies to serve and personalize ads. You can manage this at <a href="https://adssettings.google.com" target="_blank" rel="noopener">Google Ads Settings</a>.</li>
    </ul>

    <h2>4. How we use information</h2>
    <ul>
      <li>To provide, maintain and improve the tools.</li>
      <li>To respond to messages and, if you subscribed, to email you about new tools.</li>
      <li>To display advertisements that support the site.</li>
      <li>To protect the site from spam and abuse.</li>
    </ul>

    <h2>5. Sharing and third parties</h2>
    <p>We do not sell or rent personal data. Google (AdSense / Analytics, when enabled) processes data under <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Google’s Privacy Policy</a>. Our hosting provider may process server logs.</p>

    <h2>6. Data retention</h2>
    <p>Contact messages are kept for as long as needed to handle your request. Newsletter emails are kept until you unsubscribe. Anonymous counters are kept in aggregate form.</p>

    <h2>7. Your rights (GDPR and similar laws)</h2>
    <p>If you are in the European Economic Area, the UK or another region with data-protection rights, you may request access to, correction of, or deletion of the personal data we hold about you, and withdraw consent at any time. Email <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a>.</p>

    <h2>8. Security</h2>
    <p>We use HTTPS, prepared database statements, CSRF protection and hashed identifiers. No method of transmission or storage is 100% secure, but we work to protect your data.</p>

    <h2>9. Children</h2>
    <p>The site is not directed to children under 13 and we do not knowingly collect their personal data.</p>

    <h2>10. Changes to this policy</h2>
    <p>We may update this policy from time to time. The “Last updated” date above shows the latest revision.</p>

    <h2>11. Contact</h2>
    <p>Questions? Email <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a>.</p>
  </article>
</div>
<?php require INCLUDES_PATH . '/footer.php'; ?>
