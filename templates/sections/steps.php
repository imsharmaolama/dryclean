<?php /** @var array $steps */ ?>
<section class="section steps" id="how">
    <div class="container">
        <header class="section__head">
            <span class="section__kicker reveal" data-reveal>How Lachman Sons works</span>
            <h2 class="section__title reveal" data-reveal data-reveal-delay="80">
                Skip the hassle of laundry day
            </h2>
            <p class="section__sub reveal" data-reveal data-reveal-delay="160">
                Book your laundry in your area with Lachman Sons. Four simple steps —
                and great discounts on your next order.
            </p>
        </header>

        <ol class="steps__grid">
            <?php foreach ($steps as $i => $step): ?>
                <li class="step-card reveal" data-reveal data-reveal-delay="<?= $i * 90 ?>">
                    <span class="step-card__no"><?= e($step['step_no']) ?></span>
                    <div class="step-card__icon">
                        <img src="<?= icon_src($step['icon']) ?>" alt="" width="84" height="84" loading="lazy">
                    </div>
                    <h3 class="step-card__title"><?= e($step['title']) ?></h3>
                    <p class="step-card__text"><?= e($step['description']) ?></p>
                    <?php if ($i < count($steps) - 1): ?>
                        <span class="step-card__link" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="22" height="22"><use href="#i-arrow"></use></svg>
                        </span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
