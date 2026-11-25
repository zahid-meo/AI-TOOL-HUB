<?php
/**
 * Opens a tool page. Before including set: $toolSlug = 'word-counter';
 */
$tool = tool_get($toolSlug);
if (!$tool) {
    http_response_code(404);
    require ROOT_PATH . '/404.php';
    exit;
}
$cats = tool_categories();
$pageTitle  = $tool['name'] . ' — Free Online Tool';
$pageDesc   = $tool['short'] . ' Free, fast and private.';
$activeNav  = 'tools';
$crumbs = [['Home', url('index.php')], [$cats[$tool['cat']]['name'], url('index.php') . '#tools'], [$tool['name'], null]];

$pageUrl = abs_url('tools/' . $toolSlug . '.php');
$jsonLd = [
    [
        '@context' => 'https://schema.org', '@type' => 'WebApplication',
        'name' => $tool['name'], 'url' => $pageUrl, 'description' => $tool['short'],
        'applicationCategory' => 'UtilitiesApplication', 'operatingSystem' => 'Any',
        'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'USD'],
    ],
    [
        '@context' => 'https://schema.org', '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => abs_url('index.php')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => $cats[$tool['cat']]['name'], 'item' => abs_url('index.php') . '#tools'],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $tool['name'], 'item' => $pageUrl],
        ],
    ],
];
if (!empty($tool['faq'])) {
    $jsonLd[] = [
        '@context' => 'https://schema.org', '@type' => 'FAQPage',
        'mainEntity' => array_map(static fn ($f) => [
            '@type' => 'Question', 'name' => $f[0],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]],
        ], $tool['faq']),
    ];
}

$pageScripts = [];
if ($tool['cat'] === 'image') {
    $pageScripts[] = 'js/image-utils.js';
}
$pageScripts[] = 'js/tools/' . $toolSlug . '.js';
require INCLUDES_PATH . '/header.php';
require INCLUDES_PATH . '/partials/breadcrumb.php';
?>
<div class="container">
  <header class="tool-head">
    <div class="tool-head__icon <?= e($tool['cat']) ?>"><?= $tool['icon'] ?></div>
    <div>
      <h1><?= e($tool['name']) ?></h1>
      <p><?= e($tool['short']) ?></p>
    </div>
    <button type="button" class="fav-btn fav-btn--head" data-fav="<?= e($toolSlug) ?>" aria-label="Toggle favorite" title="Favorite">☆</button>
  </header>

  <section class="tool-section" aria-label="<?= e($tool['name']) ?>">
