<?php /** @var array $features */ ?>
<section class="section features" id="why">
    <div class="container">
        <header class="section__head">
            <span class="section__kicker reveal" data-reveal>Why choose Lachman Sons</span>
            <h2 class="section__title reveal" data-reveal data-reveal-delay="80">
                Reasons our customers stay
            </h2>
            <p class="section__sub reveal" data-reveal data-reveal-delay="160">
                Just drop off your clothes or request a pickup online — we wash, dry and
                iron, you relax. Here is what keeps thousands coming back.
            </p>
        </header>

        <div class="features__grid">
            <?php foreach ($features as $i => $f): ?>
                <article class="feature-card reveal" data-reveal data-reveal-delay="<?= ($i % 3) * 80 ?>"
                         style="--accent: <?= e($f['accent']) ?>;">
                    <div class="feature-card__icon">
                        <img src="<?= icon_src($f['icon']) ?>" alt="" width="72" height="72" loading="lazy">
                    </div>
                    <div class="feature-card__body">
                        <h3 class="feature-card__title"><?= e($f['title']) ?></h3>
                        <p class="feature-card__text"><?= e($f['description']) ?></p>
                    </div>
                    <span class="feature-card__index" aria-hidden="true">0<?= $i + 1 ?></span>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
