<?php
/**
 * Lachman Sons — lightweight admin dashboard.
 * Protected with HTTP Basic Auth (credentials from config/.env).
 * Lists pickup requests and newsletter subscribers.
 */

declare(strict_types=1);

require_once __DIR__ . '/../../src/bootstrap.php';

// ---- Auth ----------------------------------------------------------------
$user = app_config('admin.user', 'admin');
$pass = (string) app_config('admin.pass', '');
$hash = (string) app_config('admin.pass_hash', '');

$givenUser = $_SERVER['PHP_AUTH_USER'] ?? '';
$givenPass = $_SERVER['PHP_AUTH_PW'] ?? '';

// Some CGI/FastCGI setups pass credentials via the Authorization header only.
if ($givenUser === '' && isset($_SERVER['HTTP_AUTHORIZATION']) && str_starts_with($_SERVER['HTTP_AUTHORIZATION'], 'Basic ')) {
    $decoded = base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)) ?: '';
    if (str_contains($decoded, ':')) {
        [$givenUser, $givenPass] = explode(':', $decoded, 2);
    }
}

$userOk = hash_equals($user, $givenUser);
$passOk = $hash !== ''
    ? password_verify($givenPass, $hash)
    : ($pass !== '' && hash_equals($pass, $givenPass));

if (!$userOk || !$passOk) {
    header('WWW-Authenticate: Basic realm="Lachman Sons Admin"');
    http_response_code(401);
    echo '<!doctype html><meta charset="utf-8"><title>Authentication required</title>'
        . '<body style="font-family:system-ui;background:#0b1220;color:#e2e8f0;display:grid;place-items:center;height:100vh;margin:0">'
        . '<div style="text-align:center"><h1 style="margin:0 0 8px">401</h1><p>Authentication required.</p></div>';
    exit;
}

$pickups     = repo_all_pickups();
$subscribers = repo_all_subscribers();
$brand       = e((string) app_config('app.full_name'));

function fmt_date(?string $d): string
{
    if (!$d) return '—';
    $ts = strtotime($d);
    return $ts ? date('d M Y, H:i', $ts) : e($d);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin · <?= $brand ?></title>
    <style>
        :root { --b:#0b1220; --c:#111c30; --line:#1e2c44; --t:#e7eefc; --m:#8aa0c2; --a:#22d3ee; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif; background: var(--b); color: var(--t); }
        header { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 18px 24px; border-bottom: 1px solid var(--line); position: sticky; top: 0; background: rgba(11,18,32,.9); backdrop-filter: blur(8px); }
        header h1 { font-size: 1.1rem; margin: 0; }
        header a { color: var(--a); text-decoration: none; font-size: .9rem; }
        .wrap { max-width: 1100px; margin: 0 auto; padding: 24px; }
        .cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
        .card { background: var(--c); border: 1px solid var(--line); border-radius: 14px; padding: 18px 20px; }
        .card b { font-size: 1.8rem; display: block; color: var(--a); }
        .card span { color: var(--m); font-size: .85rem; }
        h2 { font-size: 1rem; margin: 26px 0 12px; color: var(--m); text-transform: uppercase; letter-spacing: .08em; }
        table { width: 100%; border-collapse: collapse; background: var(--c); border: 1px solid var(--line); border-radius: 14px; overflow: hidden; }
        th, td { text-align: left; padding: 11px 14px; border-bottom: 1px solid var(--line); font-size: .9rem; vertical-align: top; }
        th { color: var(--m); font-weight: 600; background: #0e1830; }
        tr:last-child td { border-bottom: 0; }
        td a { color: var(--a); text-decoration: none; }
        .pill { display: inline-block; padding: 2px 9px; border-radius: 100px; background: rgba(34,211,238,.12); color: var(--a); font-size: .78rem; }
        .empty { color: var(--m); padding: 16px; }
        .table-scroll { overflow-x: auto; }
        @media (max-width: 720px) { .cards { grid-template-columns: repeat(2, 1fr); } }
    </style>
</head>
<body>
    <header>
        <h1><?= $brand ?> · Admin</h1>
        <a href="../">← Back to site</a>
    </header>
    <div class="wrap">
        <div class="cards">
            <div class="card"><b><?= count($pickups) ?></b><span>Pickup requests</span></div>
            <div class="card"><b><?= count($subscribers) ?></b><span>Newsletter subscribers</span></div>
            <div class="card"><b><?= repo_count('services') ?></b><span>Services live</span></div>
            <div class="card"><b><?= repo_count('testimonials') ?></b><span>Testimonials</span></div>
        </div>

        <h2>Pickup requests</h2>
        <div class="table-scroll">
        <table>
            <thead>
                <tr><th>#</th><th>When</th><th>Name</th><th>Phone</th><th>Email</th><th>Area</th><th>Service</th><th>Notes</th></tr>
            </thead>
            <tbody>
                <?php if (!$pickups): ?>
                    <tr><td colspan="8" class="empty">No pickup requests yet.</td></tr>
                <?php else: foreach ($pickups as $p): ?>
                    <tr>
                        <td><?= (int) $p['id'] ?></td>
                        <td><?= fmt_date($p['created_at'] ?? null) ?></td>
                        <td><?= e($p['name']) ?></td>
                        <td><a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $p['phone'])) ?>"><?= e($p['phone']) ?></a></td>
                        <td><?= $p['email'] ? '<a href="mailto:' . e($p['email']) . '">' . e($p['email']) . '</a>' : '—' ?></td>
                        <td><?= e($p['area'] ?: '—') ?></td>
                        <td><?= $p['service'] ? '<span class="pill">' . e($p['service']) . '</span>' : '—' ?></td>
                        <td><?= e($p['message'] ?: '—') ?></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
        </div>

        <h2>Newsletter subscribers</h2>
        <div class="table-scroll">
        <table>
            <thead><tr><th>#</th><th>Email</th><th>Subscribed</th></tr></thead>
            <tbody>
                <?php if (!$subscribers): ?>
                    <tr><td colspan="3" class="empty">No subscribers yet.</td></tr>
                <?php else: foreach ($subscribers as $s): ?>
                    <tr>
                        <td><?= (int) $s['id'] ?></td>
                        <td><a href="mailto:<?= e($s['email']) ?>"><?= e($s['email']) ?></a></td>
                        <td><?= fmt_date($s['created_at'] ?? null) ?></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</body>
</html>
