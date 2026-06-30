<?php
/**
 * Application bootstrap – loads config, wires the database and exposes
 * shared config + helpers to every entry point.
 */

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '0'); // keep production output clean

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/repositories.php';
require_once __DIR__ . '/../database/init.php';

use Lumina\Database;

$config = require __DIR__ . '/../config/config.php';
Database::configure($config['db']);

// Ensure schema + seed exist (idempotent, cheap once seeded).
try {
    lumina_init_database(false);
} catch (Throwable $e) {
    if (($config['app']['env'] ?? 'production') !== 'production') {
        http_response_code(500);
        echo 'Database init error: ' . e($e->getMessage());
        exit;
    }
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
