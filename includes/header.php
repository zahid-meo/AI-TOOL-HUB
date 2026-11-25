<?php
/**
 * Site <head> + navbar + opening <main>.
 * Set these variables BEFORE including this file:
 *   $pageTitle, $pageDesc, $pagePath (optional canonical path), $pageRobots (optional),
 *   $bodyClass (optional), $jsonLd (optional array of schema.org arrays), $activeNav (optional)
 */
$reqPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if (base_path() !== '' && str_starts_with($reqPath, base_path())) {
    $reqPath = substr($reqPath, strlen(base_path()));
}
$pageTitle  = $pageTitle  ?? APP_TAGLINE;
$pageDesc   = $pageDesc   ?? 'Free online tools for students, freelancers and creators: image compressor, CGPA calculator, EMI calculator, word counter, password generator and more.';
$pagePath   = $pagePath   ?? $reqPath;
$pageRobots = $pageRobots ?? 'index,follow';
$bodyClass  = $bodyClass  ?? '';
$jsonLd     = $jsonLd     ?? [];
$activeNav  = $activeNav  ?? '';
$fullTitle  = ($pageTitle === APP_TAGLINE) ? APP_NAME . ' — ' . APP_TAGLINE : $pageTitle . ' — ' . APP_NAME;
$canonical  = site_url() . '/' . ltrim($pagePath, '/');
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($fullTitle) ?></title>
  <meta name="description" content="<?= e($pageDesc) ?>">
  <meta name="robots" content="<?= e($pageRobots) ?>">
  <meta name="theme-color" content="#4f46e5">
  <link rel="canonical" href="<?= e($canonical) ?>">
  <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='.9em' font-size='90'%3E🛠️%3C/text%3E%3C/svg%3E">

  <meta property="og:type" content="website">
  <meta property="og:site_name" content="<?= e(APP_NAME) ?>">
  <meta property="og:title" content="<?= e($fullTitle) ?>">
  <meta property="og:description" content="<?= e($pageDesc) ?>">
  <meta property="og:url" content="<?= e($canonical) ?>">
  <meta name="twitter:card" content="summary">

  <script>
    /* Apply the saved theme before first paint (prevents a white flash in dark mode). */
    (function () {
      try {
        var t = localStorage.getItem('zh-theme');
        if (!t) t = window.matchMedia && matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', t);
      } catch (e) {}
    })();
  </script>
  <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
<?php foreach ($jsonLd as $schema): ?>
  <script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) ?></script>
<?php endforeach; ?>
<?php if (ADSENSE_CLIENT !== ''): ?>
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=<?= e(ADSENSE_CLIENT) ?>" crossorigin="anonymous"></script>
<?php endif; ?>
<?php if (GA_MEASUREMENT_ID !== ''): ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e(GA_MEASUREMENT_ID) ?>"></script>
  <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?= e(GA_MEASUREMENT_ID) ?>');</script>
<?php endif; ?>
</head>
<body class="<?= e($bodyClass) ?>">
<a class="skip-link" href="#main">Skip to content</a>
<?php require INCLUDES_PATH . '/partials/navbar.php'; ?>

<?php ad_slot('header'); ?>

<div class="page-wrapper">
<main id="main">
