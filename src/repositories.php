<?php
declare(strict_types=1);

use Lumina\Database;

/**
 * Lightweight data-access functions for the LS Dry Cleaners site.
 * Kept procedural for clarity; each returns plain arrays for the templates.
 */

function repo_services(): array
{
    return Database::connection()
        ->query('SELECT * FROM services ORDER BY sort_order ASC')
        ->fetchAll();
}

function repo_features(): array
{
    return Database::connection()
        ->query('SELECT * FROM features ORDER BY sort_order ASC')
        ->fetchAll();
}

function repo_steps(): array
{
    return Database::connection()
        ->query('SELECT * FROM steps ORDER BY sort_order ASC')
        ->fetchAll();
}

function repo_stats(): array
{
    return Database::connection()
        ->query('SELECT * FROM stats ORDER BY sort_order ASC')
        ->fetchAll();
}

function repo_testimonials(): array
{
    return Database::connection()
        ->query('SELECT * FROM testimonials ORDER BY sort_order ASC')
        ->fetchAll();
}

/** Returns categories, each with a nested `items` array. */
function repo_pricing(): array
{
    $pdo        = Database::connection();
    $categories = $pdo->query('SELECT * FROM pricing_categories ORDER BY sort_order ASC')->fetchAll();
    $itemsStmt  = $pdo->prepare('SELECT * FROM pricing_items WHERE category_id = :cid ORDER BY sort_order ASC');

    foreach ($categories as &$cat) {
        $itemsStmt->execute([':cid' => $cat['id']]);
        $cat['items'] = $itemsStmt->fetchAll();
    }
    unset($cat);

    return $categories;
}

function repo_store_pickup(array $data): int
{
    $pdo  = Database::connection();
    $stmt = $pdo->prepare(
        'INSERT INTO pickup_requests (name, phone, email, area, service, message, ip_address)
         VALUES (:name, :phone, :email, :area, :service, :message, :ip)'
    );
    $stmt->execute([
        ':name'    => $data['name'],
        ':phone'   => $data['phone'],
        ':email'   => $data['email'] ?? '',
        ':area'    => $data['area'] ?? '',
        ':service' => $data['service'] ?? '',
        ':message' => $data['message'] ?? '',
        ':ip'      => $data['ip'] ?? '',
    ]);

    return (int) $pdo->lastInsertId();
}

function repo_subscribe_newsletter(string $email): string
{
    $pdo = Database::connection();

    $check = $pdo->prepare('SELECT id FROM newsletter_subscribers WHERE email = :email');
    $check->execute([':email' => $email]);
    if ($check->fetch()) {
        return 'exists';
    }

    $stmt = $pdo->prepare('INSERT INTO newsletter_subscribers (email) VALUES (:email)');
    $stmt->execute([':email' => $email]);

    return 'created';
}

// ---- Admin read helpers --------------------------------------------------

function repo_all_pickups(int $limit = 500): array
{
    $stmt = Database::connection()->prepare(
        'SELECT * FROM pickup_requests ORDER BY id DESC LIMIT :lim'
    );
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function repo_all_subscribers(int $limit = 500): array
{
    $stmt = Database::connection()->prepare(
        'SELECT * FROM newsletter_subscribers ORDER BY id DESC LIMIT :lim'
    );
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function repo_count(string $table): int
{
    $allowed = ['pickup_requests', 'newsletter_subscribers', 'services', 'testimonials'];
    if (!in_array($table, $allowed, true)) {
        return 0;
    }
    return (int) Database::connection()->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
}
