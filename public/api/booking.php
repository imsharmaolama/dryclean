<?php
declare(strict_types=1);

require_once __DIR__ . '/../../src/bootstrap.php';

use Lumina\Mailer;
use Lumina\RateLimiter;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['ok' => false, 'message' => 'Method not allowed.'], 405);
}

// ---- Rate limit ----------------------------------------------------------
$ip = RateLimiter::clientIp();
if (!RateLimiter::allow('booking', $ip, (int) app_config('rate_limit.max', 8), (int) app_config('rate_limit.window', 600))) {
    json_response(['ok' => false, 'message' => 'Too many requests. Please try again later or call us.'], 429);
}

// Accept JSON or form-encoded bodies.
$raw = file_get_contents('php://input');
if ($raw !== '' && str_contains((string) ($_SERVER['CONTENT_TYPE'] ?? ''), 'application/json')) {
    $data = json_decode($raw, true) ?: [];
} else {
    $data = $_POST;
}

if (!csrf_verify($data['csrf'] ?? null)) {
    json_response(['ok' => false, 'message' => 'Your session expired. Please refresh and try again.'], 419);
}

// Honeypot — bots fill hidden fields, humans don't.
if (!empty($data['website'])) {
    json_response(['ok' => true, 'message' => 'Thanks!']);
}

$name    = trim((string) ($data['name'] ?? ''));
$phone   = trim((string) ($data['phone'] ?? ''));
$email   = trim((string) ($data['email'] ?? ''));
$area    = trim((string) ($data['area'] ?? ''));
$service = trim((string) ($data['service'] ?? ''));
$message = trim((string) ($data['message'] ?? ''));

$phoneDigits = preg_replace('/[^0-9]/', '', $phone);

$errors = [];
if ($name === '' || mb_strlen($name) < 2) {
    $errors['name'] = 'Please tell us your name.';
}
if (strlen((string) $phoneDigits) < 8) {
    $errors['phone'] = 'A valid phone number lets us confirm your pickup.';
}
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'That email doesn’t look right.';
}

if ($errors) {
    json_response(['ok' => false, 'message' => 'Please fix the highlighted fields.', 'errors' => $errors], 422);
}

try {
    $id = repo_store_pickup([
        'name'    => $name,
        'phone'   => $phone,
        'email'   => $email,
        'area'    => $area,
        'service' => $service,
        'message' => $message,
        'ip'      => $ip,
    ]);

    // ---- Notifications (best-effort, never blocks the response) ----------
    send_booking_notifications((int) $id, compact('name', 'phone', 'email', 'area', 'service', 'message'));

    json_response([
        'ok'      => true,
        'id'      => $id,
        'message' => "Thanks {$name}! Your pickup request is in. We'll call you on {$phone} shortly to confirm.",
    ]);
} catch (Throwable $e) {
    error_log('Booking store failed: ' . $e->getMessage());
    json_response(['ok' => false, 'message' => 'Something went wrong on our end. Please call us at +91 98916 43790.'], 500);
}

/**
 * Email the shop (admin) about the new request, and the customer a confirmation.
 */
function send_booking_notifications(int $id, array $d): void
{
    $brand = app_config('app.full_name', 'Lachman Sons Drycleaners');
    $to    = app_config('mail.to', 'owner@lsdrycleaners.in');

    $rows = '';
    $fields = [
        'Name' => $d['name'], 'Phone' => $d['phone'], 'Email' => $d['email'] ?: '—',
        'Area' => $d['area'] ?: '—', 'Service' => $d['service'] ?: '—',
        'Notes' => $d['message'] ?: '—',
    ];
    foreach ($fields as $label => $value) {
        $rows .= '<tr><td style="padding:8px 14px;color:#64748b;font-weight:600;border-bottom:1px solid #eef2f7">'
            . e((string) $label) . '</td><td style="padding:8px 14px;border-bottom:1px solid #eef2f7">'
            . nl2br(e((string) $value)) . '</td></tr>';
    }

    $adminBody = '<div style="font-family:Arial,sans-serif;max-width:560px;margin:auto">'
        . '<h2 style="color:#0ea5e9;margin:0 0 4px">New pickup request #' . $id . '</h2>'
        . '<p style="color:#475569;margin:0 0 16px">A customer just booked a pickup on the website.</p>'
        . '<table style="width:100%;border-collapse:collapse;font-size:14px">' . $rows . '</table>'
        . '<p style="margin-top:18px"><a href="tel:' . e($d['phone']) . '" style="background:#0ea5e9;color:#fff;padding:10px 18px;border-radius:8px;text-decoration:none;font-weight:600">Call ' . e($d['phone']) . '</a></p>'
        . '</div>';

    Mailer::send($to, $brand . ' — Owner', "New pickup request #{$id} — {$d['name']}", $adminBody, $d['email'] ?: null, $d['name']);

    if ($d['email'] !== '') {
        $custBody = '<div style="font-family:Arial,sans-serif;max-width:560px;margin:auto">'
            . '<h2 style="color:#0ea5e9;margin:0 0 8px">Thanks, ' . e($d['name']) . '!</h2>'
            . '<p style="color:#475569">We’ve received your pickup request and our team will call you on <strong>' . e($d['phone']) . '</strong> shortly to confirm the time.</p>'
            . '<table style="width:100%;border-collapse:collapse;font-size:14px;margin-top:10px">' . $rows . '</table>'
            . '<p style="color:#475569;margin-top:16px">Need anything sooner? Call us at <strong>' . e((string) app_config('app.phone')) . '</strong>.</p>'
            . '<p style="color:#94a3b8;font-size:12px;margin-top:18px">— ' . e($brand) . ', trusted since ' . e((string) app_config('app.since')) . '</p>'
            . '</div>';
        Mailer::send($d['email'], $d['name'], "We’ve got your pickup request — {$brand}", $custBody);
    }
}
