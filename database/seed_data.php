<?php
/**
 * Seed content for Lachman Sons Drycleaners. Returns plain arrays consumed by
 * init.php. All copy & pricing reflect the real business (est. 1980).
 */

declare(strict_types=1);

return [
    // ---- Services ---------------------------------------------------------
    'services' => [
        [
            'slug' => 'dry-cleaning', 'title' => 'Dry Cleaning',
            'description' => 'Your licensed, certified dry cleaner with door-step pick up and delivery. Stains lifted, colours protected, fabrics returned crisp and fresh.',
            'icon' => 'magic-trick', 'accent' => '#0ea5e9', 'sort_order' => 1,
        ],
        [
            'slug' => 'steam-ironing', 'title' => 'Steam Ironing',
            'description' => 'Professional steam pressing that removes every crease without scorching delicate fibres — for shirts, suits and everyday wear.',
            'icon' => 'fire', 'accent' => '#f97316', 'sort_order' => 2,
        ],
        [
            'slug' => 'wedding-suits', 'title' => 'Wedding & Suits',
            'description' => "Not just your most expensive suit — your most admired one. We clean and finish wedding wear and sherwanis with white-glove care.",
            'icon' => 'trophy', 'accent' => '#d4af37', 'sort_order' => 3,
        ],
        [
            'slug' => 'curtains-drapery', 'title' => 'Curtains & Drapery',
            'description' => 'Curtains lend a special effect to your decor and are often the first thing guests notice. We clean drapery and dupioni silk with precision.',
            'icon' => 'roll-brush', 'accent' => '#14b8a6', 'sort_order' => 4,
        ],
        [
            'slug' => 'silk-suede', 'title' => 'Silk & Suede',
            'description' => 'Refreshments, ink marks and everyday stains can damage softened leather and fine silk. Our specialists restore and protect them.',
            'icon' => 'heart', 'accent' => '#ec4899', 'sort_order' => 5,
        ],
        [
            'slug' => 'leather-cleaning', 'title' => 'Leather Cleaning',
            'description' => 'Leather garments are a chic addition to any wardrobe. With the right care they keep their finish for years — that care is what we do.',
            'icon' => 'bag', 'accent' => '#8b5cf6', 'sort_order' => 6,
        ],
    ],

    // ---- Why choose us ----------------------------------------------------
    'features' => [
        [
            'title' => 'Hygienic', 'icon' => 'sheild', 'accent' => '#0ea5e9',
            'description' => 'No blending of garments. Ultra-clean laundromats with careful packaging that keeps every order fresh.',
            'sort_order' => 1,
        ],
        [
            'title' => 'Convenience', 'icon' => 'mobile', 'accent' => '#14b8a6',
            'description' => 'Save time and energy. Book your laundry online or drop it at your nearest store — we handle the rest.',
            'sort_order' => 2,
        ],
        [
            'title' => 'High Quality', 'icon' => 'medal', 'accent' => '#d4af37',
            'description' => 'A spotless reputation, built over decades. We serve diverse sectors with consistent, premium quality.',
            'sort_order' => 3,
        ],
        [
            'title' => 'Professional', 'icon' => 'tick', 'accent' => '#22c55e',
            'description' => 'Highly trained attendants who treat your wardrobe like their own. Your satisfaction is always guaranteed.',
            'sort_order' => 4,
        ],
        [
            'title' => 'Best Equipment', 'icon' => 'setting', 'accent' => '#6366f1',
            'description' => 'Award-winning facility using modern, well-maintained machines for cleaner, brighter, fresher garments.',
            'sort_order' => 5,
        ],
        [
            'title' => 'Affordable Pricing', 'icon' => 'rupee', 'accent' => '#f97316',
            'description' => 'High-quality care that stays pocket-friendly. Transparent rates with no hidden charges, ever.',
            'sort_order' => 6,
        ],
    ],

    // ---- How it works -----------------------------------------------------
    'steps' => [
        ['step_no' => '01', 'title' => 'Free Pick Up', 'icon' => 'bag',    'description' => 'Book online or call us. We collect your clothes from your doorstep at a time that suits you.', 'sort_order' => 1],
        ['step_no' => '02', 'title' => 'Wash & Dry',   'icon' => 'bucket', 'description' => 'Garments are sorted, treated and cleaned with the right process for every fabric — no blending.', 'sort_order' => 2],
        ['step_no' => '03', 'title' => 'Fold & Iron',  'icon' => 'fire',   'description' => 'Everything is steam-pressed, neatly folded or hung, and packaged to stay fresh.', 'sort_order' => 3],
        ['step_no' => '04', 'title' => 'Free Delivery', 'icon' => 'travel', 'description' => 'We deliver back to your door, on time, every time — hassle-free and ready to wear.', 'sort_order' => 4],
    ],

    // ---- Stats ------------------------------------------------------------
    'stats' => [
        ['label' => 'Years of expertise', 'value' => 44,    'suffix' => '+', 'sort_order' => 1],
        ['label' => 'Garments cleaned',   'value' => 25000, 'suffix' => '+', 'sort_order' => 2],
        ['label' => 'Happy customers',    'value' => 12000, 'suffix' => '+', 'sort_order' => 3],
        ['label' => 'Business clients',   'value' => 350,   'suffix' => '+', 'sort_order' => 4],
    ],

    // ---- Testimonials -----------------------------------------------------
    'testimonials' => [
        [
            'quote' => 'Highly impressed by the professionalism these young men showed. They picked up from my doorstep and delivered within the promised time.',
            'author' => 'Chandan Singh', 'role' => 'Fashion Designer', 'rating' => 5, 'sort_order' => 1,
        ],
        [
            'quote' => 'Very good service! Always polite, with a quick turnaround between pick-up and drop-off. Would definitely recommend.',
            'author' => 'Riya Pathak', 'role' => 'Homemaker', 'rating' => 5, 'sort_order' => 2,
        ],
        [
            'quote' => 'These guys are the greatest! My favourite coat was stained and I needed it for an interview the next day — I got it back on time, as promised.',
            'author' => 'Rohit Yadav', 'role' => 'Content Writer', 'rating' => 5, 'sort_order' => 3,
        ],
        [
            'quote' => 'Reliable, careful and always on schedule. My suits come back looking better than the day I bought them.',
            'author' => 'Navneet Anand', 'role' => 'Doctor', 'rating' => 5, 'sort_order' => 4,
        ],
        [
            'quote' => 'Needed my coat cleaned overnight before a big day. They delivered exactly when they said they would. Brilliant team.',
            'author' => 'Akash Panday', 'role' => 'Hotel Manager', 'rating' => 5, 'sort_order' => 5,
        ],
        [
            'quote' => 'Professional from pick-up to delivery. The packaging and finish are spotless every single time.',
            'author' => 'Ashish Saxena', 'role' => 'Bank Manager', 'rating' => 5, 'sort_order' => 6,
        ],
    ],

    // ---- Pricing (real rates from the business) ---------------------------
    'pricing' => [
        [
            'slug' => 'gents', 'name' => 'Gents', 'icon' => 'scissor', 'sort_order' => 1,
            'items' => [
                ['name' => 'Coat',                 'detail' => 'Coloured · White',          'price_from' => 170],
                ['name' => 'Shirt',                'detail' => 'Colour · White',            'price_from' => 70],
                ['name' => 'T-Shirt (Half Sleeve)','detail' => 'Cotton',                    'price_from' => 70],
                ['name' => 'T-Shirt (Full Sleeve)','detail' => 'Cotton',                    'price_from' => 80],
                ['name' => 'Woolen T-Shirt',       'detail' => 'Full sleeve · with cap',    'price_from' => 100],
            ],
        ],
        [
            'slug' => 'ladies', 'name' => 'Ladies', 'icon' => 'gift', 'sort_order' => 2,
            'items' => [
                ['name' => 'Blouse',          'detail' => 'Plain · With work',                'price_from' => 50],
                ['name' => 'Shawl',           'detail' => 'Plain · Pashmina · Woolen',        'price_from' => 90],
                ['name' => 'Lehnga (with work)','detail' => '2 piece · 3 piece',              'price_from' => 400],
                ['name' => 'Sweater',         'detail' => 'Half · Full · Long',               'price_from' => 80],
            ],
        ],
        [
            'slug' => 'upholstery', 'name' => 'Upholstery', 'icon' => 'sun', 'sort_order' => 3,
            'items' => [
                ['name' => 'Blanket (Single)', 'detail' => 'Single layer · Double layer',  'price_from' => 200],
                ['name' => 'Blanket (Double)', 'detail' => 'Single layer · Double layer',  'price_from' => 250],
                ['name' => 'Rajai / Quilt',    'detail' => 'Single · Double',              'price_from' => 200],
                ['name' => 'Cushion Cover',    'detail' => 'Cotton · Silk',                'price_from' => 50],
            ],
        ],
    ],
];
