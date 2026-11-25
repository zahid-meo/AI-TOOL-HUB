<?php
require_once __DIR__ . '/includes/bootstrap.php';
header('Content-Type: application/xml; charset=utf-8');
$urls = [['index.php', '1.0'], ['about.php', '0.5'], ['contact.php', '0.5'], ['privacy-policy.php', '0.3'], ['terms.php', '0.3'], ['sitemap.php', '0.2']];
foreach (tools_all() as $slug => $t) {
    $urls[] = ['tools/' . $slug . '.php', '0.8'];
}
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as [$path, $prio]) {
    echo '  <url><loc>' . e(abs_url($path)) . '</loc><changefreq>weekly</changefreq><priority>' . $prio . '</priority></url>' . "\n";
}
echo '</urlset>';
