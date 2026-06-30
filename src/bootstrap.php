<?php
/**
 * Application bootstrap – loads config, wires the database, mailer and
 * rate limiter, sends baseline security headers and exposes shared helpers.
 */

declare(strict_types=1);

error_reporting(E_ALL);

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Mailer.php';
require_once __DIR__ . '/RateLimiter.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/repositories.php';
require_once __DIR__ . '/../database/init.php';

use Lumina\Database;
use Lumina\Mailer;
use Lumina\RateLimiter;

$config = require __DIR__ . '/../config/config.php';

$isProd = ($config['app']['env'] ?? 'production') === 'production';
ini_set('display_errors', $isProd ? '0' : '1');

Database::configure($config['db']);
Mailer::configure($config['mail'], __DIR__ . '/../data/notifications.log');
RateLimiter::configure(__DIR__ . '/../data/ratelimit');

// Baseline security headers for web requests.
if (PHP_SAPI !== 'cli' && !headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    header_remove('X-Powered-By');
}

// Ensure schema + seed exist (idempotent, cheap once seeded).
try {
    lumina_init_database(false);
} catch (Throwable $e) {
    if (!$isProd) {
        http_response_code(500);
        echo 'Database init error: ' . e($e->getMessage());
        exit;
    }
    error_log('Lumina DB init failed: ' . $e->getMessage());
}

// Expose config globally for templates.
$GLOBALS['lumina_config'] = $config;

function app_config(string $key, $default = null)
{
    $cfg = $GLOBALS['lumina_config'] ?? [];
    foreach (explode('.', $key) as $segment) {
        if (is_array($cfg) && array_key_exists($segment, $cfg)) {
            $cfg = $cfg[$segment];
        } else {
            return $default;
        }
    }
    return $cfg;
}
