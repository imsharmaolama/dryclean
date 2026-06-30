<?php /** @var array $pricing */ ?>
<section class="section pricing" id="pricing">
    <div class="container">
        <header class="section__head">
            <span class="section__kicker reveal" data-reveal>Transparent pricing</span>
            <h2 class="section__title reveal" data-reveal data-reveal-delay="80">
                Honest rates, no surprises
            </h2>
            <p class="section__sub reveal" data-reveal data-reveal-delay="160">
                A snapshot of our most-requested items. Final pricing depends on fabric and
                finish — your pickup confirmation always shows the rate first.
            </p>
        </header>

        <div class="pricing__tabs reveal" data-reveal role="tablist" aria-label="Pricing categories">
            <?php foreach ($pricing as $i => $cat): ?>
                <button class="pricing__tab <?= $i === 0 ? 'is-active' : '' ?>"
                        role="tab" data-tab="<?= e($cat['slug']) ?>"
                        aria-selected="<?= $i === 0 ? 'true' : 'false' ?>"
                        data-magnetic>
                    <img src="<?= icon_src($cat['icon']) ?>" alt="" width="30" height="30" loading="lazy">
                    <?= e($cat['name']) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div class="pricing__panels">
            <?php foreach ($pricing as $i => $cat): ?>
                <div class="pricing__panel <?= $i === 0 ? 'is-active' : '' ?>" data-panel="<?= e($cat['slug']) ?>" role="tabpanel">
                    <div class="price-grid">
                        <?php foreach ($cat['items'] as $j => $item): ?>
                            <div class="price-row reveal" data-reveal data-reveal-delay="<?= $j * 60 ?>">
                                <div class="price-row__info">
                                    <h3 class="price-row__name"><?= e($item['name']) ?></h3>
                                    <p class="price-row__detail"><?= e($item['detail']) ?></p>
                                </div>
                                <div class="price-row__price">
                                    <span class="price-row__from">from</span>
                                    <strong>₹<?= (int) $item['price_from'] ?></strong>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <p class="pricing__note reveal" data-reveal>
            Looking for something not listed? <a href="#book" data-cursor="text">Request a pickup</a> and we’ll share an exact quote.
        </p>
    </div>
</section>
