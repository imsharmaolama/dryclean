<?php
/**
 * Router for the PHP built-in server so that unknown URLs return the custom
 * 404 page (the dev server ignores .htaccess).
 *
 *   php -S localhost:8000 -t public public/router.php
 *
 * Not used by Apache/Nginx in production.
 */

declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$full = __DIR__ . $path;

// Homepage.
if ($path === '/' || $path === '') {
    return false; // let the server serve index.php
}

// Existing static file, PHP script, or directory with an index → serve it.
if (file_exists($full)) {
    if (is_dir($full)) {
        if (is_file($full . '/index.php') || is_file($full . '/index.html')) {
            return false;
        }
    } else {
        return false;
    }
}

// Anything else: custom 404.
http_response_code(404);
require __DIR__ . '/404.php';
return true;
