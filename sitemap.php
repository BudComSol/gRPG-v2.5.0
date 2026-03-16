<?php
declare(strict_types=1);
define('GRPG_INC', true);
define('NO_SESSION', true);
define('NO_CSRF', true);
define('NO_FUNCTIONS', true);
define('NO_ERROR_LOGGER', true);
require_once __DIR__ . '/inc/dbcon.php';

$baseUrl = (defined('BASE_URL') && BASE_URL !== '' && BASE_URL !== false) ? (string) BASE_URL : null;
if ($baseUrl === null) {
    $proto   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host    = preg_replace('/[^\w\-\.:]/', '', $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost');
    $baseUrl = $proto . '://' . $host;
}
$baseUrl = rtrim($baseUrl, '/');

header('Content-Type: application/xml; charset=utf-8');

$today = date('Y-m-d');

$pages = [
    ['loc' => $baseUrl . '/',                'changefreq' => 'weekly',  'priority' => '1.0'],
    ['loc' => $baseUrl . '/login.php',       'changefreq' => 'monthly', 'priority' => '0.9'],
    ['loc' => $baseUrl . '/register.php',    'changefreq' => 'monthly', 'priority' => '0.9'],
    ['loc' => $baseUrl . '/forgot.php',      'changefreq' => 'monthly', 'priority' => '0.5'],
    ['loc' => $baseUrl . '/terms.php',       'changefreq' => 'monthly', 'priority' => '0.3'],
    ['loc' => $baseUrl . '/privacy.php',     'changefreq' => 'monthly', 'priority' => '0.3'],
];

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($pages as $page) {
    echo '    <url>' . "\n";
    echo '        <loc>' . htmlspecialchars($page['loc'], ENT_XML1, 'UTF-8') . '</loc>' . "\n";
    echo '        <lastmod>' . $today . '</lastmod>' . "\n";
    echo '        <changefreq>' . htmlspecialchars($page['changefreq'], ENT_XML1, 'UTF-8') . '</changefreq>' . "\n";
    echo '        <priority>' . htmlspecialchars($page['priority'], ENT_XML1, 'UTF-8') . '</priority>' . "\n";
    echo '    </url>' . "\n";
}
echo '</urlset>' . "\n";
