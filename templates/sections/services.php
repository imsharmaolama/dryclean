<?php /** @var array $services */ ?>
<section class="section services" id="services">
    <div class="container">
        <header class="section__head section__head--split">
            <div>
                <span class="section__kicker reveal" data-reveal>Our services</span>
                <h2 class="section__title reveal" data-reveal data-reveal-delay="80">
                    Expert care for<br>every fabric
                </h2>
            </div>
            <p class="section__sub reveal" data-reveal data-reveal-delay="160">
                Drop off at any of our stores or request a pickup online. We wash, dry,
                press and protect — you just have to ask.
            </p>
        </header>

        <div class="services__grid">
            <?php foreach ($services as $i => $svc): ?>
                <article class="service-card reveal" data-reveal data-reveal-delay="<?= ($i % 3) * 90 ?>"
                         style="--accent: <?= e($svc['accent']) ?>;" data-tilt data-tilt-max="6">
                    <div class="service-card__glow" aria-hidden="true"></div>
                    <div class="service-card__icon">
                        <img src="<?= icon_src($svc['icon']) ?>" alt="" width="96" height="96" loading="lazy">
                    </div>
                    <h3 class="service-card__title"><?= e($svc['title']) ?></h3>
                    <p class="service-card__text"><?= e($svc['description']) ?></p>
                    <a href="#book" class="service-card__cta" data-cursor="text">
                        Book this service
                        <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><use href="#i-arrow"></use></svg>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
