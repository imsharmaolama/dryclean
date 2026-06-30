<?php
/** @var array $stats */
$hours = app_config('hours', []);
?>
<section class="section about" id="about">
    <div class="container about__grid">
        <div class="about__visual" data-reveal>
            <div class="about__media" data-tilt data-tilt-max="5">
                <div class="about__badge glass">
                    <img src="<?= icon_src('medal') ?>" alt="" width="60" height="60" loading="lazy">
                    <div>
                        <strong>Award-winning</strong>
                        <span>green &amp; eco-friendly facility</span>
                    </div>
                </div>
                <div class="about__since">
                    <span class="about__since-num" data-count="<?= (int) (date('Y') - (int) app_config('app.since')) ?>">0</span><span class="about__since-plus">+</span>
                    <p>years of trusted, expert care since <?= e((string) app_config('app.since')) ?></p>
                </div>
                <div class="about__icons" aria-hidden="true">
                    <img src="<?= icon_src('sun') ?>" alt="" width="56" height="56" data-float loading="lazy">
                    <img src="<?= icon_src('heart') ?>" alt="" width="48" height="48" data-float loading="lazy">
                    <img src="<?= icon_src('tick') ?>" alt="" width="52" height="52" data-float loading="lazy">
                </div>
            </div>
        </div>

        <div class="about__content">
            <span class="section__kicker reveal" data-reveal>About Lachman Sons</span>
            <h2 class="section__title reveal" data-reveal data-reveal-delay="80">
                Decades of care behind every garment
            </h2>
            <p class="about__text reveal" data-reveal data-reveal-delay="160">
                Since <?= e((string) app_config('app.since')) ?>, Lachman Sons has put expert care, handling and experience
                behind every order. Our award-winning facility uses green energy and
                environmentally friendly processes for brighter, fresher, cleaner garments —
                with the kind of personal service a family business is known for.
            </p>

            <div class="stats reveal" data-reveal data-reveal-delay="240">
                <?php foreach ($stats as $stat): ?>
                    <div class="stat">
                        <div class="stat__value">
                            <span data-count="<?= (int) $stat['value'] ?>">0</span><?= e($stat['suffix']) ?>
                        </div>
                        <div class="stat__label"><?= e($stat['label']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="hours-card glass reveal" data-reveal data-reveal-delay="300">
                <h3 class="hours-card__title">
                    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><use href="#i-clock"></use></svg>
                    Opening hours
                </h3>
                <ul class="hours-card__list">
                    <?php foreach ($hours as $h): ?>
                        <li class="<?= str_contains(strtolower($h['time']), 'closed') ? 'is-closed' : '' ?>">
                            <span><?= e($h['days']) ?></span>
                            <strong><?= e($h['time']) ?></strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>
