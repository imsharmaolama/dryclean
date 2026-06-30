<?php
/** @var string $token */
$baseUrl  = (string) app_config('app.base_url', '');
$ogImage  = $baseUrl !== '' ? $baseUrl . '/assets/img/icons/bucket.png' : 'assets/img/icons/bucket.png';
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type'    => 'DryCleaningOrLaundry',
    'name'     => app_config('app.full_name'),
    'image'    => $ogImage,
    'description' => 'Premium dry cleaning, steam ironing and garment care with free doorstep pickup & delivery across South Delhi and Gurgaon. Trusted since ' . app_config('app.since') . '.',
    'telephone'  => app_config('app.phone'),
    'email'      => app_config('app.email'),
    'foundingDate' => (string) app_config('app.since'),
    'priceRange'   => '₹₹',
    'areaServed'   => ['South Delhi', 'Gurgaon', 'Delhi NCR'],
    'address'    => [
        '@type'           => 'PostalAddress',
        'addressLocality' => 'South Delhi',
        'addressRegion'   => 'Delhi',
        'addressCountry'  => 'IN',
    ],
    'openingHoursSpecification' => [
        ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => ['Monday','Tuesday','Wednesday','Thursday','Friday'], 'opens' => '08:00', 'closes' => '14:00'],
        ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => ['Saturday'], 'opens' => '09:00', 'closes' => '18:00'],
    ],
    'aggregateRating' => ['@type' => 'AggregateRating', 'ratingValue' => '4.9', 'reviewCount' => '1200'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#06121f">
    <title><?= e(app_config('app.full_name')) ?> — Premium Dry Cleaning in South Delhi &amp; Gurgaon</title>
    <meta name="description" content="Lachman Sons Drycleaners — trusted since 1980. Free doorstep pickup &amp; delivery, expert dry cleaning, steam ironing, leather &amp; wedding wear care across South Delhi and Gurgaon.">
    <meta name="keywords" content="dry cleaning South Delhi, dry cleaners Gurgaon, laundry pickup delivery, steam ironing, leather cleaning, wedding suit dry clean, Lachman Sons">
    <meta name="csrf-token" content="<?= e($token) ?>">
    <?php if ($baseUrl !== ''): ?><link rel="canonical" href="<?= e($baseUrl) ?>/"><?php endif; ?>

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= e(app_config('app.full_name')) ?>">
    <meta property="og:title" content="<?= e(app_config('app.full_name')) ?> — Premium Dry Cleaning">
    <meta property="og:description" content="Trusted since 1980. Free doorstep pickup &amp; delivery across South Delhi and Gurgaon.">
    <meta property="og:image" content="<?= e($ogImage) ?>">
    <?php if ($baseUrl !== ''): ?><meta property="og:url" content="<?= e($baseUrl) ?>/"><?php endif; ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e(app_config('app.full_name')) ?> — Premium Dry Cleaning">
    <meta name="twitter:description" content="Trusted since 1980. Free doorstep pickup &amp; delivery across South Delhi and Gurgaon.">
    <meta name="twitter:image" content="<?= e($ogImage) ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@1,9..144,500;1,9..144,600&family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="preload" href="assets/img/icons/bucket.png" as="image">

    <link rel="icon" type="image/png" href="assets/img/icons/bucket.png">
    <link rel="apple-touch-icon" href="assets/img/icons/bucket.png">
    <link rel="manifest" href="manifest.webmanifest">
    <link rel="stylesheet" href="assets/css/styles.css">

    <script type="application/ld+json"><?= json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
</head>
<body data-scroll-container>

<!-- Preloader -->
<div class="preloader" id="preloader" aria-hidden="true">
    <div class="preloader__inner">
        <div class="preloader__bubbles">
            <span></span><span></span><span></span>
        </div>
        <div class="preloader__mark">LS</div>
        <div class="preloader__bar"><i></i></div>
    </div>
</div>

<!-- Custom cursor -->
<div class="cursor" id="cursor" aria-hidden="true"></div>
<div class="cursor-dot" id="cursorDot" aria-hidden="true"></div>

<!-- Scroll progress -->
<div class="scroll-progress" id="scrollProgress" aria-hidden="true"></div>

<!-- Ambient background -->
<div class="ambient" aria-hidden="true">
    <span class="ambient__blob ambient__blob--1"></span>
    <span class="ambient__blob ambient__blob--2"></span>
    <span class="ambient__blob ambient__blob--3"></span>
    <div class="ambient__grid"></div>
</div>

<!-- Top announcement bar -->
<div class="topbar" id="topbar">
    <div class="container topbar__row">
        <span class="topbar__item"><span class="dot"></span> Free doorstep pickup &amp; delivery across <?= e(app_config('app.location')) ?></span>
        <a class="topbar__phone" href="tel:<?= e(app_config('app.phone_link')) ?>" data-cursor="text">Call <?= e(app_config('app.phone')) ?></a>
    </div>
</div>

<!-- Header / Nav -->
<header class="header" id="header">
    <div class="container header__row">
        <a href="#top" class="brand" data-cursor="text" aria-label="<?= e(app_config('app.full_name')) ?> home">
            <span class="brand__mark" aria-hidden="true">
                <svg viewBox="0 0 48 48" width="40" height="40"><use href="#logo-hanger"></use></svg>
            </span>
            <span class="brand__text">
                <strong>Lachman Sons</strong>
                <em>Drycleaners · since <?= e((string) app_config('app.since')) ?></em>
            </span>
        </a>

        <nav class="nav" id="primaryNav" aria-label="Primary">
            <a href="#how" class="nav__link" data-cursor="text">How it works</a>
            <a href="#services" class="nav__link" data-cursor="text">Services</a>
            <a href="#pricing" class="nav__link" data-cursor="text">Pricing</a>
            <a href="#about" class="nav__link" data-cursor="text">About</a>
            <a href="#reviews" class="nav__link" data-cursor="text">Reviews</a>
        </nav>

        <div class="header__actions">
            <a href="tel:<?= e(app_config('app.phone_link')) ?>" class="btn btn--ghost btn--sm header__call" data-magnetic>
                <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><use href="#i-phone"></use></svg>
                <span><?= e(app_config('app.phone')) ?></span>
            </a>
            <a href="#book" class="btn btn--primary btn--sm" data-magnetic data-cursor="hide">Book a pickup</a>
            <button class="nav-toggle" id="navToggle" aria-label="Open menu" aria-expanded="false" aria-controls="mobileMenu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>

<!-- Mobile menu -->
<div class="mobile-menu" id="mobileMenu" aria-hidden="true">
    <nav class="mobile-menu__nav" aria-label="Mobile">
        <a href="#how" class="mobile-menu__link"><span>01</span> How it works</a>
        <a href="#services" class="mobile-menu__link"><span>02</span> Services</a>
        <a href="#pricing" class="mobile-menu__link"><span>03</span> Pricing</a>
        <a href="#about" class="mobile-menu__link"><span>04</span> About</a>
        <a href="#reviews" class="mobile-menu__link"><span>05</span> Reviews</a>
    </nav>
    <div class="mobile-menu__footer">
        <a href="#book" class="btn btn--primary btn--block">Book a pickup</a>
        <a href="tel:<?= e(app_config('app.phone_link')) ?>" class="mobile-menu__call">
            <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><use href="#i-phone"></use></svg>
            <?= e(app_config('app.phone')) ?>
        </a>
    </div>
</div>

<main id="top">
