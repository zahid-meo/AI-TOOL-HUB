<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Terms & Conditions';
$pageDesc  = 'Terms and conditions for using ' . APP_NAME . ' and its free online tools.';
$crumbs = [['Home', url('index.php')], ['Terms & Conditions', null]];
require INCLUDES_PATH . '/header.php';
require INCLUDES_PATH . '/partials/breadcrumb.php';
?>
<div class="container">
  <article class="page-card">
    <h1>Terms &amp; Conditions</h1>
    <p class="updated">Last updated: 21 November 2026</p>

    <h2>1. Acceptance of terms</h2>
    <p>By accessing or using <?= e(APP_NAME) ?> (“the Site”), you agree to these Terms and Conditions. If you do not agree, please stop using the Site.</p>

    <h2>2. Description of service</h2>
    <p>The Site provides free web utilities, including image compression and conversion, calculators, text tools and developer utilities. Most tools process data locally in your browser; files you open in those tools are not uploaded to our servers.</p>

    <h2>3. User conduct</h2>
    <ul>
      <li>Do not use the tools for illegal activity, including copyright infringement.</li>
      <li>You are responsible for the content you process with the tools.</li>
      <li>Do not attempt to disrupt, overload, scrape aggressively or break the security of the Site.</li>
    </ul>

    <h2>4. Disclaimer of warranties</h2>
    <p>The Site and tools are provided “as is” and “as available” without warranties of any kind. Results (for example EMI, CGPA, BMI or conversions) are for general information only and should be verified before being used for financial, academic, medical or legal decisions.</p>

    <h2>5. Limitation of liability</h2>
    <p>To the maximum extent permitted by law, <?= e(APP_NAME) ?>, its owner Muhammad Zahid and affiliates are not liable for any direct, indirect, incidental or consequential damages arising from use of the Site.</p>

    <h2>6. Third-party services (advertising)</h2>
    <p>The Site may display Google AdSense advertisements. These are served by Google and are subject to Google’s terms and privacy policy. We do not control third-party ad content.</p>

    <h2>7. Intellectual property</h2>
    <p>The source code of the Site is released under the MIT License. Names, logos and written content remain the property of their respective owners.</p>

    <h2>8. Changes to terms</h2>
    <p>We may modify these Terms at any time. Changes take effect when posted. Continued use of the Site means you accept the updated Terms.</p>

    <h2>9. Governing law</h2>
    <p>These Terms are governed by the laws of Pakistan, without regard to conflict-of-law principles.</p>

    <h2>10. Contact</h2>
    <p>Questions about these Terms? Email <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a>.</p>
  </article>
</div>
<?php require INCLUDES_PATH . '/footer.php'; ?>
