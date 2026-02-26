<?php
header("Content-Type: application/xml; charset=utf-8");
require_once __DIR__ . '/includes/config.php';

echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <url>
      <loc><?= APP_URL ?>/</loc>
      <lastmod><?= date('Y-m-d') ?></lastmod>
      <changefreq>monthly</changefreq>
      <priority>1.0</priority>
   </url>
</urlset>
