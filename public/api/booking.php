<?php
declare(strict_types=1);

require_once __DIR__ . '/../../src/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['ok' => false, 'message' => 'Method not allowed.'], 405);
}

// Accept JSON or form-encoded bodies.
$raw  = file_get_contents('php://input');
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

// Normalise phone for validation (keep digits and +).
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
        'ip'      => $_SERVER['REMOTE_ADDR'] ?? '',
    ]);
    json_response([
        'ok'      => true,
        'id'      => $id,
        'message' => "Thanks {$name}! Your pickup request is in. We'll call you on {$phone} shortly to confirm.",
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'message' => 'Something went wrong on our end. Please call us at +91 98916 43790.'], 500);
}
