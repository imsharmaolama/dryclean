/* =========================================================================
   Lachman Sons Drycleaners — interaction & animation engine (vanilla JS)
   No build step, no dependencies. Degrades gracefully + respects
   prefers-reduced-motion and touch devices.
   ========================================================================= */
(function () {
    'use strict';

    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var isTouch = window.matchMedia('(hover: none)').matches;
    var $  = function (s, c) { return (c || document).querySelector(s); };
    var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };

    /* ---- Preloader ----------------------------------------------------- */
    document.body.classList.add('is-loading');
    var preloader = $('#preloader');
    var bar = preloader ? $('.preloader__bar i', preloader) : null;
    var prog = 0;
    var fakeLoad = setInterval(function () {
        prog = Math.min(100, prog + Math.random() * 22);
        if (bar) bar.style.width = prog + '%';
        if (prog >= 100) clearInterval(fakeLoad);
    }, 130);

    function endPreloader() {
        if (bar) bar.style.width = '100%';
        setTimeout(function () {
            if (preloader) preloader.classList.add('is-done');
            document.body.classList.remove('is-loading');
            startReveal();
            drawUnderline();
        }, 350);
    }
    window.addEventListener('load', function () { setTimeout(endPreloader, 400); });
    // safety net so the page never stays locked
    setTimeout(function () { if (document.body.classList.contains('is-loading')) endPreloader(); }, 3500);

    /* ---- Custom cursor + magnetic -------------------------------------- */
    if (!isTouch && !reduceMotion) {
        var cursor = $('#cursor'), dot = $('#cursorDot');
        var cx = window.innerWidth / 2, cy = window.innerHeight / 2, tx = cx, ty = cy;
        document.body.classList.add('cursor-ready');
        window.addEventListener('mousemove', function (e) {
            tx = e.clientX; ty = e.clientY;
            if (dot) { dot.style.left = tx + 'px'; dot.style.top = ty + 'px'; }
        });
        (function loop() {
            cx += (tx - cx) * 0.18; cy += (ty - cy) * 0.18;
            if (cursor) { cursor.style.left = cx + 'px'; cursor.style.top = cy + 'px'; }
            requestAnimationFrame(loop);
        })();
        $$('[data-cursor]').forEach(function (el) {
            var mode = el.getAttribute('data-cursor');
            el.addEventListener('mouseenter', function () { if (cursor) cursor.classList.add(mode === 'hide' ? 'is-hide' : 'is-text'); });
            el.addEventListener('mouseleave', function () { if (cursor) cursor.classList.remove('is-hide', 'is-text'); });
        });

        // magnetic buttons
        $$('[data-magnetic]').forEach(function (el) {
            var strength = 0.32;
            el.addEventListener('mousemove', function (e) {
                var r = el.getBoundingClientRect();
                var mx = e.clientX - (r.left + r.width / 2);
                var my = e.clientY - (r.top + r.height / 2);
                el.style.transform = 'translate(' + mx * strength + 'px,' + my * strength + 'px)';
            });
            el.addEventListener('mouseleave', function () { el.style.transform = ''; });
        });
    }

    /* ---- Scroll progress + sticky header ------------------------------- */
    var progressEl = $('#scrollProgress');
    var header = $('#header');
    function onScroll() {
        var st = window.pageYOffset || document.documentElement.scrollTop;
        var h = document.documentElement.scrollHeight - window.innerHeight;
        if (progressEl) progressEl.style.width = (h > 0 ? (st / h) * 100 : 0) + '%';
        if (header) header.classList.toggle('is-stuck', st > 40);
        parallax(st);
        spyNav(st);
    }
    window.addEventListener('scroll', onScroll, { passive: true });

    /* ---- Reveal on scroll ---------------------------------------------- */
    var revealObserver;
    function startReveal() {
        var items = $$('[data-reveal]');
        if (reduceMotion || !('IntersectionObserver' in window)) {
            items.forEach(function (el) { el.classList.add('is-in'); });
            return;
        }
        revealObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var d = parseInt(entry.target.getAttribute('data-reveal-delay') || '0', 10);
                    setTimeout(function () { entry.target.classList.add('is-in'); }, d);
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
        items.forEach(function (el) { revealObserver.observe(el); });
    }

    function drawUnderline() {
        var u = $('.hero__underline');
        if (u && !reduceMotion) setTimeout(function () { u.classList.add('is-drawn'); }, 600);
        else if (u) u.classList.add('is-drawn');
    }

    /* ---- Counters ------------------------------------------------------ */
    function animateCount(el) {
        var target = parseFloat(el.getAttribute('data-count')) || 0;
        if (reduceMotion) { el.textContent = format(target); return; }
        var dur = 1600, start = null;
        function step(ts) {
            if (!start) start = ts;
            var p = Math.min((ts - start) / dur, 1);
            var eased = 1 - Math.pow(1 - p, 3);
            el.textContent = format(Math.floor(eased * target));
            if (p < 1) requestAnimationFrame(step);
            else el.textContent = format(target);
        }
        requestAnimationFrame(step);
    }
    function format(n) { return n >= 1000 ? n.toLocaleString('en-IN') : String(n); }
    if ('IntersectionObserver' in window) {
        var countObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) { if (e.isIntersecting) { animateCount(e.target); countObs.unobserve(e.target); } });
        }, { threshold: 0.5 });
        $$('[data-count]').forEach(function (el) { countObs.observe(el); });
    } else {
        $$('[data-count]').forEach(function (el) { el.textContent = format(parseFloat(el.getAttribute('data-count')) || 0); });
    }

    /* ---- Parallax ------------------------------------------------------ */
    var parallaxEls = $$('[data-parallax]');
    function parallax(st) {
        if (reduceMotion) return;
        parallaxEls.forEach(function (el) {
            var speed = parseFloat(el.getAttribute('data-parallax')) || 0.1;
            var rect = el.getBoundingClientRect();
            var center = rect.top + rect.height / 2 - window.innerHeight / 2;
            el.style.transform = 'translateY(' + (-center * speed) + 'px)';
        });
    }

    /* ---- Tilt ---------------------------------------------------------- */
    if (!isTouch && !reduceMotion) {
        $$('[data-tilt]').forEach(function (el) {
            var max = parseFloat(el.getAttribute('data-tilt-max')) || 9;
            el.style.transition = 'transform .25s var(--ease)';
            el.addEventListener('mousemove', function (e) {
                var r = el.getBoundingClientRect();
                var px = (e.clientX - r.left) / r.width - 0.5;
                var py = (e.clientY - r.top) / r.height - 0.5;
                el.style.transform = 'perspective(900px) rotateY(' + (px * max) + 'deg) rotateX(' + (-py * max) + 'deg)';
            });
            el.addEventListener('mouseleave', function () { el.style.transform = 'perspective(900px) rotateY(0) rotateX(0)'; });
        });
    }

    /* ---- Marquees ------------------------------------------------------ */
    if (!reduceMotion) {
        $$('[data-marquee]').forEach(function (wrap) {
            var track = wrap.querySelector('.marquee__track, .reviews__track');
            if (!track) return;
            var speed = (parseFloat(wrap.getAttribute('data-marquee-speed')) || 50); // px/s
            var reverse = wrap.hasAttribute('data-marquee-reverse');
            var offset = 0, last = null, half = track.scrollWidth / 2, paused = false;
            wrap.addEventListener('mouseenter', function () { paused = true; });
            wrap.addEventListener('mouseleave', function () { paused = false; });
            function tick(ts) {
                if (last === null) last = ts;
                var dt = (ts - last) / 1000; last = ts;
                if (!paused) {
                    offset += speed * dt * (reverse ? 1 : -1);
                    if (!reverse && Math.abs(offset) >= half) offset += half;
                    if (reverse && offset >= 0) offset -= half;
                    track.style.transform = 'translateX(' + offset + 'px)';
                }
                requestAnimationFrame(tick);
            }
            if (reverse) offset = -half;
            requestAnimationFrame(tick);
            window.addEventListener('resize', function () { half = track.scrollWidth / 2; });
        });
    }

    /* ---- Mobile menu --------------------------------------------------- */
    var toggle = $('#navToggle'), menu = $('#mobileMenu');
    function setMenu(open) {
        if (!menu || !toggle) return;
        toggle.classList.toggle('is-open', open);
        menu.classList.toggle('is-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        menu.setAttribute('aria-hidden', open ? 'false' : 'true');
        document.body.classList.toggle('menu-open', open);
    }
    if (toggle) toggle.addEventListener('click', function () { setMenu(!menu.classList.contains('is-open')); });
    $$('.mobile-menu__link, .mobile-menu__footer a').forEach(function (a) {
        a.addEventListener('click', function () { setMenu(false); });
    });

    /* ---- Active nav link (scroll spy) ---------------------------------- */
    var sections = ['home', 'how', 'services', 'pricing', 'about', 'reviews'];
    function spyNav(st) {
        var current = '';
        sections.forEach(function (id) {
            var sec = document.getElementById(id);
            if (sec && sec.offsetTop - 120 <= st) current = id;
        });
        $$('.nav__link').forEach(function (l) {
            l.classList.toggle('is-active', l.getAttribute('href') === '#' + current);
        });
    }

    /* ---- Pricing tabs -------------------------------------------------- */
    $$('.pricing__tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            var slug = tab.getAttribute('data-tab');
            $$('.pricing__tab').forEach(function (t) { t.classList.remove('is-active'); t.setAttribute('aria-selected', 'false'); });
            tab.classList.add('is-active'); tab.setAttribute('aria-selected', 'true');
            $$('.pricing__panel').forEach(function (p) {
                p.classList.toggle('is-active', p.getAttribute('data-panel') === slug);
            });
        });
    });

    /* ---- Smooth anchor scroll (offset for sticky header) --------------- */
    $$('a[href^="#"]').forEach(function (a) {
        a.addEventListener('click', function (e) {
            var id = a.getAttribute('href');
            if (id === '#' || id === '#top') { e.preventDefault(); window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' }); return; }
            var target = document.querySelector(id);
            if (target) {
                e.preventDefault();
                var y = target.getBoundingClientRect().top + window.pageYOffset - 70;
                window.scrollTo({ top: y, behavior: reduceMotion ? 'auto' : 'smooth' });
            }
        });
    });

    /* ---- Forms (AJAX) -------------------------------------------------- */
    var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

    function postJSON(url, data) {
        return fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify(data)
        }).then(function (r) { return r.json().then(function (j) { return { status: r.status, body: j }; }); });
    }

    // Booking form
    var bookingForm = $('#bookingForm');
    if (bookingForm) {
        var feedback = $('#bookingFeedback');
        bookingForm.addEventListener('submit', function (e) {
            e.preventDefault();
            $$('.field', bookingForm).forEach(function (f) { f.classList.remove('has-error'); });
            $$('[data-error]', bookingForm).forEach(function (s) { s.textContent = ''; });
            if (feedback) { feedback.textContent = ''; feedback.className = 'booking-form__feedback'; }

            var btn = $('button[type="submit"]', bookingForm);
            btn.classList.add('is-loading'); btn.disabled = true;

            var data = {
                name: bookingForm.name.value, phone: bookingForm.phone.value,
                email: bookingForm.email.value, area: bookingForm.area.value,
                service: bookingForm.service.value, message: bookingForm.message.value,
                website: bookingForm.website.value, csrf: csrf
            };
            postJSON('api/booking.php', data).then(function (res) {
                btn.classList.remove('is-loading'); btn.disabled = false;
                if (res.body.ok) {
                    bookingForm.reset();
                    if (feedback) { feedback.textContent = res.body.message; feedback.classList.add('is-ok'); }
                } else {
                    if (res.body.errors) {
                        Object.keys(res.body.errors).forEach(function (key) {
                            var span = bookingForm.querySelector('[data-error="' + key + '"]');
                            if (span) { span.textContent = res.body.errors[key]; span.closest('.field').classList.add('has-error'); }
                        });
                    }
                    if (feedback) { feedback.textContent = res.body.message || 'Please try again.'; feedback.classList.add('is-err'); }
                }
            }).catch(function () {
                btn.classList.remove('is-loading'); btn.disabled = false;
                if (feedback) { feedback.textContent = 'Network error — please call +91 98916 43790.'; feedback.classList.add('is-err'); }
            });
        });
    }

    // Newsletter form
    var nlForm = $('#newsletterForm');
    if (nlForm) {
        var nlFeedback = $('#nlFeedback');
        nlForm.addEventListener('submit', function (e) {
            e.preventDefault();
            if (nlFeedback) { nlFeedback.textContent = ''; nlFeedback.className = 'news-form__feedback'; }
            var btn = $('button[type="submit"]', nlForm);
            btn.classList.add('is-loading'); btn.disabled = true;
            postJSON('api/newsletter.php', { email: nlForm.email.value, csrf: csrf }).then(function (res) {
                btn.classList.remove('is-loading'); btn.disabled = false;
                if (nlFeedback) {
                    nlFeedback.textContent = res.body.message;
                    nlFeedback.classList.add(res.body.ok ? 'is-ok' : 'is-err');
                }
                if (res.body.ok) nlForm.reset();
            }).catch(function () {
                btn.classList.remove('is-loading'); btn.disabled = false;
                if (nlFeedback) { nlFeedback.textContent = 'Network error — please try again.'; nlFeedback.classList.add('is-err'); }
            });
        });
    }

    // Initial paint
    onScroll();
})();
