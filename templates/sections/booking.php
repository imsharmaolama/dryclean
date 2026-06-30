<?php
/** @var array $services */
/** @var string $token */
?>
<section class="section booking" id="book">
    <div class="container booking__grid">
        <div class="booking__intro">
            <span class="section__kicker reveal" data-reveal>Book a free pickup</span>
            <h2 class="section__title reveal" data-reveal data-reveal-delay="80">
                Let’s get your clothes<br>looking their best
            </h2>
            <p class="booking__lead reveal" data-reveal data-reveal-delay="160">
                Tell us where and when — we’ll collect from your doorstep, clean with care,
                and deliver back fresh. No more trips to the laundromat.
            </p>

            <ul class="booking__points reveal" data-reveal data-reveal-delay="240">
                <li><i class="check"></i> Free pickup &amp; delivery across <?= e(app_config('app.location')) ?></li>
                <li><i class="check"></i> Rate confirmed before we clean</li>
                <li><i class="check"></i> Hygienic, no-blending process</li>
            </ul>

            <div class="booking__contact reveal" data-reveal data-reveal-delay="300">
                <a href="tel:<?= e(app_config('app.phone_link')) ?>" class="booking__contact-item" data-magnetic>
                    <span class="booking__contact-ic"><svg viewBox="0 0 24 24" width="20" height="20"><use href="#i-phone"></use></svg></span>
                    <span><em>Call us</em><strong><?= e(app_config('app.phone')) ?></strong></span>
                </a>
                <a href="https://wa.me/<?= e(app_config('app.whatsapp')) ?>" target="_blank" rel="noopener" class="booking__contact-item" data-magnetic>
                    <span class="booking__contact-ic"><svg viewBox="0 0 24 24" width="20" height="20"><use href="#i-chat"></use></svg></span>
                    <span><em>WhatsApp</em><strong>Message us</strong></span>
                </a>
            </div>
        </div>

        <div class="booking__form-wrap reveal" data-reveal data-reveal-delay="160">
            <form class="booking-form glass" id="bookingForm" novalidate>
                <h3 class="booking-form__title">Request your pickup</h3>

                <div class="field">
                    <label for="bf-name">Full name</label>
                    <input type="text" id="bf-name" name="name" autocomplete="name" placeholder="Your name" required>
                    <span class="field__error" data-error="name"></span>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="bf-phone">Phone</label>
                        <input type="tel" id="bf-phone" name="phone" autocomplete="tel" placeholder="+91 ..." required>
                        <span class="field__error" data-error="phone"></span>
                    </div>
                    <div class="field">
                        <label for="bf-email">Email <em>(optional)</em></label>
                        <input type="email" id="bf-email" name="email" autocomplete="email" placeholder="you@email.com">
                        <span class="field__error" data-error="email"></span>
                    </div>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="bf-area">Area / locality</label>
                        <input type="text" id="bf-area" name="area" placeholder="e.g. Saket, Gurgaon">
                    </div>
                    <div class="field">
                        <label for="bf-service">Service</label>
                        <div class="select">
                            <select id="bf-service" name="service">
                                <option value="">Choose a service</option>
                                <?php foreach ($services as $svc): ?>
                                    <option value="<?= e($svc['title']) ?>"><?= e($svc['title']) ?></option>
                                <?php endforeach; ?>
                                <option value="Other">Other</option>
                            </select>
                            <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><use href="#i-chevron"></use></svg>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label for="bf-message">Notes <em>(optional)</em></label>
                    <textarea id="bf-message" name="message" rows="3" placeholder="Number of items, preferred pickup time, special care…"></textarea>
                </div>

                <!-- honeypot -->
                <input type="text" name="website" tabindex="-1" autocomplete="off" class="hp" aria-hidden="true">
                <input type="hidden" name="csrf" value="<?= e($token) ?>">

                <button type="submit" class="btn btn--primary btn--lg btn--block" data-magnetic>
                    <span class="btn__label">Request pickup</span>
                    <span class="btn__spinner" aria-hidden="true"></span>
                </button>

                <div class="booking-form__or"><span>or</span></div>

                <button type="button" class="btn btn--wa btn--lg btn--block" id="bookWhatsApp"
                        data-wa="<?= e(app_config('app.whatsapp')) ?>" data-magnetic>
                    <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><use href="#i-chat"></use></svg>
                    Book instantly on WhatsApp
                </button>

                <p class="booking-form__feedback" id="bookingFeedback" role="status" aria-live="polite"></p>
            </form>
        </div>
    </div>
</section>
