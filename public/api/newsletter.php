<?php
declare(strict_types=1);

require_once __DIR__ . '/../../src/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['ok' => false, 'message' => 'Method not allowed.'], 405);
}

$raw  = file_get_contents('php://input');
$data = [];
if ($raw !== '' && str_contains((string) ($_SERVER['CONTENT_TYPE'] ?? ''), 'application/json')) {
    $data = json_decode($raw, true) ?: [];
} else {
    $data = $_POST;
}

if (!csrf_verify($data['csrf'] ?? null)) {
    json_response(['ok' => false, 'message' => 'Your session expired. Please refresh and try again.'], 419);
}

$email = trim((string) ($data['email'] ?? ''));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_response(['ok' => false, 'message' => 'Please enter a valid email address.'], 422);
}

try {
    $result = repo_subscribe_newsletter($email);
    $msg = $result === 'exists'
        ? "You're already on the list — see you in the inbox."
        : "You're in! Expect fabric-care tips and member-only offers — never spam.";
    json_response(['ok' => true, 'status' => $result, 'message' => $msg]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'message' => 'Subscription failed. Please try again.'], 500);
}
