<section class="marquee-band" aria-hidden="true">
    <div class="marquee" data-marquee>
        <div class="marquee__track">
            <?php
            $words = ['Dry Cleaning', 'Steam Ironing', 'Free Pickup', 'Leather Care', 'Wedding Wear', 'Curtains', 'Free Delivery', 'Since 1980'];
            // duplicate the list for a seamless loop
            foreach (array_merge($words, $words) as $w): ?>
                <span class="marquee__item"><?= e($w) ?></span>
                <span class="marquee__star">✦</span>
            <?php endforeach; ?>
        </div>
    </div>
</section>
