<?php
/**
 * Idempotent database initializer + seeder for LS Dry Cleaners.
 * Runs automatically on first page load (see src/bootstrap.php) and can also
 * be invoked directly:  php database/init.php  [--fresh]
 */

declare(strict_types=1);

require_once __DIR__ . '/../src/Database.php';

use Lumina\Database;

function lumina_init_database(bool $fresh = false): void
{
    $config = require __DIR__ . '/../config/config.php';
    Database::configure($config['db']);
    $pdo    = Database::connection();
    $driver = Database::driver();

    $auto = $driver === 'mysql'
        ? 'INT AUTO_INCREMENT PRIMARY KEY'
        : 'INTEGER PRIMARY KEY AUTOINCREMENT';
    $now  = $driver === 'mysql'
        ? 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP'
        : "TEXT NOT NULL DEFAULT (datetime('now'))";

    $tables = ['services','features','steps','stats','testimonials',
               'pricing_categories','pricing_items','pickup_requests','newsletter_subscribers'];

    if ($fresh) {
        // children first for FK safety
        foreach (['pricing_items','pricing_categories','services','features','steps','stats','testimonials','pickup_requests','newsletter_subscribers'] as $t) {
            $pdo->exec("DROP TABLE IF EXISTS {$t}");
        }
    }

    $pdo->exec("CREATE TABLE IF NOT EXISTS services (
        id $auto,
        slug TEXT NOT NULL UNIQUE,
        title TEXT NOT NULL,
        description TEXT NOT NULL,
        icon TEXT NOT NULL,
        accent TEXT NOT NULL DEFAULT '#0ea5e9',
        sort_order INTEGER NOT NULL DEFAULT 0
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS features (
        id $auto,
        title TEXT NOT NULL,
        description TEXT NOT NULL,
        icon TEXT NOT NULL,
        accent TEXT NOT NULL DEFAULT '#0ea5e9',
        sort_order INTEGER NOT NULL DEFAULT 0
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS steps (
        id $auto,
        step_no TEXT NOT NULL,
        title TEXT NOT NULL,
        description TEXT NOT NULL,
        icon TEXT NOT NULL,
        sort_order INTEGER NOT NULL DEFAULT 0
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS stats (
        id $auto,
        label TEXT NOT NULL,
        value INTEGER NOT NULL,
        suffix TEXT NOT NULL DEFAULT '',
        sort_order INTEGER NOT NULL DEFAULT 0
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS testimonials (
        id $auto,
        quote TEXT NOT NULL,
        author TEXT NOT NULL,
        role TEXT NOT NULL,
        rating INTEGER NOT NULL DEFAULT 5,
        sort_order INTEGER NOT NULL DEFAULT 0
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS pricing_categories (
        id $auto,
        slug TEXT NOT NULL UNIQUE,
        name TEXT NOT NULL,
        icon TEXT NOT NULL DEFAULT 'rupee',
        sort_order INTEGER NOT NULL DEFAULT 0
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS pricing_items (
        id $auto,
        category_id INTEGER NOT NULL,
        name TEXT NOT NULL,
        detail TEXT NOT NULL,
        price_from INTEGER NOT NULL DEFAULT 0,
        sort_order INTEGER NOT NULL DEFAULT 0
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS pickup_requests (
        id $auto,
        name TEXT NOT NULL,
        phone TEXT NOT NULL,
        email TEXT NOT NULL DEFAULT '',
        area TEXT NOT NULL DEFAULT '',
        service TEXT NOT NULL DEFAULT '',
        message TEXT NOT NULL DEFAULT '',
        ip_address TEXT NOT NULL DEFAULT '',
        created_at $now
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS newsletter_subscribers (
        id $auto,
        email TEXT NOT NULL UNIQUE,
        created_at $now
    )");

    // Seed only when empty so the initializer is safe to run on every request.
    $count = (int) $pdo->query('SELECT COUNT(*) FROM services')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $seed = require __DIR__ . '/seed_data.php';

    $insert = static function (PDO $pdo, string $table, array $rows): void {
        if (!$rows) {
            return;
        }
        $cols  = array_keys($rows[0]);
        $place = '(' . implode(',', array_fill(0, count($cols), '?')) . ')';
        $sql   = 'INSERT INTO ' . $table . ' (' . implode(',', $cols) . ') VALUES ' . $place;
        $stmt  = $pdo->prepare($sql);
        foreach ($rows as $row) {
            $stmt->execute(array_values($row));
        }
    };

    $pdo->beginTransaction();
    try {
        $insert($pdo, 'services', $seed['services']);
        $insert($pdo, 'features', $seed['features']);
        $insert($pdo, 'steps', $seed['steps']);
        $insert($pdo, 'stats', $seed['stats']);
        $insert($pdo, 'testimonials', $seed['testimonials']);

        // Pricing categories + items (items reference category by sort position).
        $catStmt = $pdo->prepare('INSERT INTO pricing_categories (slug, name, icon, sort_order) VALUES (?,?,?,?)');
        $itemStmt = $pdo->prepare('INSERT INTO pricing_items (category_id, name, detail, price_from, sort_order) VALUES (?,?,?,?,?)');
        foreach ($seed['pricing'] as $cat) {
            $catStmt->execute([$cat['slug'], $cat['name'], $cat['icon'], $cat['sort_order']]);
            $catId = (int) $pdo->lastInsertId();
            $i = 1;
            foreach ($cat['items'] as $item) {
                $itemStmt->execute([$catId, $item['name'], $item['detail'], $item['price_from'], $i++]);
            }
        }

        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

// Allow CLI execution.
if (PHP_SAPI === 'cli' && isset($argv[0]) && realpath($argv[0]) === realpath(__FILE__)) {
    $fresh = in_array('--fresh', $argv, true);
    lumina_init_database($fresh);
    echo "LS Dry Cleaners database initialized" . ($fresh ? " (fresh)" : "") . ".\n";
}
