<?php
/**
 * Local dev router — bridges the Vercel layout for local development.
 *
 * Vercel serves pages as Serverless Functions from api/ and static assets
 * from public/. Locally, the PHP built-in server needs a router to do the
 * same: page requests → api/, static requests → public/ (document root).
 *
 * Usage: php -S localhost:8000 -t public router.php
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Root → api/index.php
if ($uri === '/') {
    require __DIR__ . '/api/index.php';
    exit;
}

// PHP pages → api/*.php
$apiFile = __DIR__ . '/api' . $uri;
if (is_file($apiFile)) {
    require $apiFile;
    exit;
}

// Static assets (css, fonts, images) → public/ (document root via -t public)
return false;
