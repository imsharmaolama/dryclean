<section class="hero" id="home">
    <div class="container hero__grid">
        <div class="hero__content">
            <span class="eyebrow reveal" data-reveal>
                <span class="eyebrow__pulse"></span>
                Trusted since <?= e((string) app_config('app.since')) ?> · <?= e(app_config('app.location')) ?>
            </span>

            <h1 class="hero__title">
                <span class="line" data-reveal data-reveal-delay="0">Dry cleaning,</span>
                <span class="line" data-reveal data-reveal-delay="80">off your</span>
                <span class="line line--accent" data-reveal data-reveal-delay="160">
                    to&#8209;do list.
                    <svg class="hero__underline" viewBox="0 0 320 24" preserveAspectRatio="none" aria-hidden="true">
                        <path d="M3 17C70 6 250 4 317 14" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round"/>
                    </svg>
                </span>
            </h1>

            <p class="hero__lead reveal" data-reveal data-reveal-delay="240">
                Lachman Sons is a name you can trust. Highly trained attendants, free doorstep
                pickup &amp; delivery, and spotless results on every garment — guaranteed.
            </p>

            <div class="hero__cta reveal" data-reveal data-reveal-delay="320">
                <a href="#book" class="btn btn--primary btn--lg" data-magnetic data-cursor="hide">
                    Book a free pickup
                    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><use href="#i-arrow"></use></svg>
                </a>
                <a href="#pricing" class="btn btn--soft btn--lg" data-magnetic data-cursor="hide">See pricing</a>
            </div>

            <div class="hero__trust reveal" data-reveal data-reveal-delay="400">
                <div class="hero__rating">
                    <span class="stars" aria-hidden="true">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <svg viewBox="0 0 24 24" width="18" height="18"><use href="#i-star"></use></svg>
                        <?php endfor; ?>
                    </span>
                    <strong>4.9</strong>
                    <span>from 1,200+ reviews</span>
                </div>
                <div class="hero__sep" aria-hidden="true"></div>
                <p class="hero__trust-note">12,000+ happy customers across Delhi&nbsp;NCR</p>
            </div>
        </div>

        <div class="hero__visual" data-tilt>
            <!-- Main glass card -->
            <div class="hero-card glass" data-parallax="0.06">
                <div class="hero-card__top">
                    <span class="hero-card__tag"><span class="dot"></span> Pickup scheduled</span>
                    <span class="hero-card__time">Today · 5:30 PM</span>
                </div>
                <div class="hero-card__icon">
                    <img src="<?= icon_src('bucket') ?>" alt="" loading="eager" width="150" height="150">
                    <span class="hero-card__ring"></span>
                </div>
                <div class="hero-card__rows">
                    <div class="hero-card__row"><span>Wash &amp; dry</span><i class="check"></i></div>
                    <div class="hero-card__row"><span>Steam press</span><i class="check"></i></div>
                    <div class="hero-card__row hero-card__row--active"><span>Out for delivery</span><i class="spin"></i></div>
                </div>
            </div>

            <!-- Floating 3D icons -->
            <figure class="float-icon float-icon--1" data-parallax="0.12" data-float>
                <img src="<?= icon_src('bag') ?>" alt="" width="92" height="92" loading="eager">
            </figure>
            <figure class="float-icon float-icon--2" data-parallax="0.18" data-float>
                <img src="<?= icon_src('fire') ?>" alt="" width="78" height="78" loading="eager">
            </figure>
            <figure class="float-icon float-icon--3" data-parallax="0.1" data-float>
                <img src="<?= icon_src('travel') ?>" alt="" width="104" height="104" loading="eager">
            </figure>
            <figure class="float-icon float-icon--4" data-parallax="0.22" data-float>
                <img src="<?= icon_src('sheild') ?>" alt="" width="70" height="70" loading="eager">
            </figure>

            <!-- mini stat chip -->
            <div class="hero-chip glass" data-parallax="0.14">
                <strong>44+ yrs</strong>
                <span>of garment care</span>
            </div>
        </div>
    </div>

    <a href="#how" class="hero__scroll" data-cursor="text" aria-label="Scroll to how it works">
        <span class="hero__scroll-mouse"><i></i></span>
        Scroll
    </a>
</section>
