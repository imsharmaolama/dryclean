<?php
/** @var array $testimonials */
// split into two rows for opposing marquees
$half  = (int) ceil(count($testimonials) / 2);
$rowA  = array_slice($testimonials, 0, $half);
$rowB  = array_slice($testimonials, $half);
if (!$rowB) { $rowB = $rowA; }

$card = static function (array $t): string {
    ob_start(); ?>
    <figure class="review-card">
        <div class="review-card__stars" aria-label="<?= (int) $t['rating'] ?> out of 5">
            <?php for ($i = 0; $i < (int) $t['rating']; $i++): ?>
                <svg viewBox="0 0 24 24" width="16" height="16"><use href="#i-star"></use></svg>
            <?php endfor; ?>
        </div>
        <blockquote class="review-card__quote">“<?= e($t['quote']) ?>”</blockquote>
        <figcaption class="review-card__by">
            <span class="review-card__avatar" aria-hidden="true"><?= e(mb_substr($t['author'], 0, 1)) ?></span>
            <span>
                <strong><?= e($t['author']) ?></strong>
                <em><?= e($t['role']) ?></em>
            </span>
        </figcaption>
    </figure>
<?php return ob_get_clean();
};
?>
<section class="section reviews" id="reviews">
    <div class="container">
        <header class="section__head">
            <span class="section__kicker reveal" data-reveal>Client testimonials</span>
            <h2 class="section__title reveal" data-reveal data-reveal-delay="80">
                Customer satisfaction drives us
            </h2>
            <p class="section__sub reveal" data-reveal data-reveal-delay="160">
                Real words from people across Delhi NCR who trust us with their wardrobes.
            </p>
        </header>
    </div>

    <div class="reviews__rail reveal" data-reveal>
        <div class="reviews__row" data-marquee data-marquee-speed="60">
            <div class="reviews__track">
                <?php foreach (array_merge($rowA, $rowA) as $t) { echo $card($t); } ?>
            </div>
        </div>
        <div class="reviews__row reviews__row--reverse" data-marquee data-marquee-speed="75" data-marquee-reverse>
            <div class="reviews__track">
                <?php foreach (array_merge($rowB, $rowB) as $t) { echo $card($t); } ?>
            </div>
        </div>
        <span class="reviews__fade reviews__fade--l" aria-hidden="true"></span>
        <span class="reviews__fade reviews__fade--r" aria-hidden="true"></span>
    </div>
</section>
