<?php
/** @var string $token */
$social = app_config('social', []);
$hours  = app_config('hours', []);
?>
</main>

<!-- Newsletter + CTA band -->
<section class="cta-band">
    <div class="container cta-band__inner glass">
        <div class="cta-band__text">
            <h2>Laundry tips &amp; member-only offers</h2>
            <p>Join our list for seasonal fabric-care advice and exclusive discounts. No spam — just useful things.</p>
        </div>
        <form class="news-form" id="newsletterForm" novalidate>
            <div class="news-form__field">
                <input type="email" name="email" id="nlEmail" placeholder="Enter your email" autocomplete="email" required aria-label="Email address">
                <input type="hidden" name="csrf" value="<?= e($token) ?>">
                <button type="submit" class="btn btn--primary" data-magnetic>
                    <span class="btn__label">Subscribe</span>
                    <span class="btn__spinner" aria-hidden="true"></span>
                </button>
            </div>
            <p class="news-form__feedback" id="nlFeedback" role="status" aria-live="polite"></p>
        </form>
    </div>
</section>

<footer class="footer">
    <div class="container footer__grid">
        <div class="footer__brand">
            <a href="#top" class="brand brand--footer">
                <span class="brand__mark" aria-hidden="true">
                    <svg viewBox="0 0 48 48" width="40" height="40"><use href="#logo-hanger"></use></svg>
                </span>
                <span class="brand__text">
                    <strong>Lachman Sons</strong>
                    <em>Drycleaners · since <?= e((string) app_config('app.since')) ?></em>
                </span>
            </a>
            <p class="footer__about">
                Premium dry cleaning, steam ironing and garment care with free doorstep
                pickup &amp; delivery across <?= e(app_config('app.location')) ?>.
            </p>
            <div class="footer__social">
                <?php foreach ($social as $s): ?>
                    <a href="<?= e($s['url']) ?>" target="_blank" rel="noopener" aria-label="<?= e($s['label']) ?>" data-magnetic><?= e($s['label']) ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="footer__col">
            <h4>Services</h4>
            <ul>
                <li><a href="#services">Dry Cleaning</a></li>
                <li><a href="#services">Steam Ironing</a></li>
                <li><a href="#services">Wedding &amp; Suits</a></li>
                <li><a href="#services">Leather Cleaning</a></li>
            </ul>
        </div>

        <div class="footer__col">
            <h4>Company</h4>
            <ul>
                <li><a href="#how">How it works</a></li>
                <li><a href="#about">About us</a></li>
                <li><a href="#pricing">Pricing</a></li>
                <li><a href="#reviews">Reviews</a></li>
            </ul>
        </div>

        <div class="footer__col">
            <h4>Get in touch</h4>
            <ul class="footer__contact">
                <li><a href="tel:<?= e(app_config('app.phone_link')) ?>"><?= e(app_config('app.phone')) ?></a></li>
                <li><a href="mailto:<?= e(app_config('app.email')) ?>"><?= e(app_config('app.email')) ?></a></li>
                <li><?= e(app_config('app.address')) ?></li>
            </ul>
            <a href="#book" class="btn btn--soft btn--sm" data-magnetic>Book a pickup</a>
        </div>
    </div>

    <div class="container footer__bottom">
        <p>&copy; <?= date('Y') ?> <?= e(app_config('app.full_name')) ?>. All rights reserved.</p>
        <p class="footer__credit">Crafted with care · 3D icons by <a href="https://3dicons.co" target="_blank" rel="noopener">3dicons.co</a></p>
    </div>
</footer>

<!-- Floating quick actions (mobile) -->
<div class="quick-actions" aria-hidden="false">
    <a href="https://wa.me/<?= e(app_config('app.whatsapp')) ?>" target="_blank" rel="noopener" class="quick-actions__btn quick-actions__btn--wa" aria-label="WhatsApp us">
        <svg viewBox="0 0 24 24" width="24" height="24"><use href="#i-chat"></use></svg>
    </a>
    <a href="tel:<?= e(app_config('app.phone_link')) ?>" class="quick-actions__btn quick-actions__btn--call" aria-label="Call us">
        <svg viewBox="0 0 24 24" width="24" height="24"><use href="#i-phone"></use></svg>
    </a>
</div>

<!-- SVG sprite -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <symbol id="logo-hanger" viewBox="0 0 48 48">
        <path d="M24 11a3.2 3.2 0 1 1 2.4 5.3c.5.5.8 1.2.8 2L42 27.6c1.6 1 2.4 2 2.4 3.3 0 1.9-1.6 3.1-4 3.1H7.6c-2.4 0-4-1.2-4-3.1 0-1.3.8-2.3 2.4-3.3l14.8-9.3c0-.8.3-1.5.8-2A3.2 3.2 0 0 1 24 11Z"
              fill="none" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/>
    </symbol>
    <symbol id="i-phone" viewBox="0 0 24 24">
        <path d="M6.6 3h3l1.5 4-2 1.3a11 11 0 0 0 5.6 5.6l1.3-2 4 1.5v3c0 1-.8 1.8-1.8 1.7A15.8 15.8 0 0 1 4.8 6.8 1.7 1.7 0 0 1 6.6 3Z"
              fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
    </symbol>
    <symbol id="i-arrow" viewBox="0 0 24 24">
        <path d="M5 12h14m-6-6 6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </symbol>
    <symbol id="i-star" viewBox="0 0 24 24">
        <path d="m12 3 2.6 5.5 6 .9-4.3 4.3 1 6L12 17l-5.3 2.7 1-6L3.4 9.4l6-.9L12 3Z" fill="currentColor"/>
    </symbol>
    <symbol id="i-clock" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="1.8"/>
        <path d="M12 7v5l3.5 2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
    </symbol>
    <symbol id="i-chat" viewBox="0 0 24 24">
        <path d="M20 12a8 8 0 0 1-11.6 7.1L4 20l1-4.3A8 8 0 1 1 20 12Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
    </symbol>
    <symbol id="i-chevron" viewBox="0 0 24 24">
        <path d="m6 9 6 6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </symbol>
</svg>

<script src="assets/js/main.js" defer></script>
</body>
</html>
