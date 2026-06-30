<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

// Fetch all content from the database.
$services     = repo_services();
$features      = repo_features();
$steps         = repo_steps();
$stats         = repo_stats();
$testimonials = repo_testimonials();
$pricing      = repo_pricing();
$token        = csrf_token();

$tpl = __DIR__ . '/../templates';

require $tpl . '/partials/header.php';
require $tpl . '/sections/hero.php';
require $tpl . '/sections/marquee.php';
require $tpl . '/sections/steps.php';
require $tpl . '/sections/services.php';
require $tpl . '/sections/features.php';
require $tpl . '/sections/about.php';
require $tpl . '/sections/pricing.php';
require $tpl . '/sections/testimonials.php';
require $tpl . '/sections/booking.php';
require $tpl . '/partials/footer.php';
