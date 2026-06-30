<?php
/**
 * Lachman Sons Drycleaners (LS Dry Cleaners) – Application configuration.
 *
 * Ships with a zero-config SQLite database so it runs anywhere PHP is
 * installed. To use MySQL, set DB_DRIVER=mysql with the related env vars;
 * the schema in database/schema.mysql.sql is provided for that path.
 */

declare(strict_types=1);

return [
    'app' => [
        'name'        => 'Lachman Sons',
        'full_name'   => 'Lachman Sons Drycleaners',
        'short_name'  => 'LS Dry Cleaners',
        'tagline'     => 'Dry cleaning, off your to-do list.',
        'since'       => 1980,
        'email'       => 'care@lsdrycleaners.in',
        'phone'       => '+91 98916 43790',
        'phone_link'  => '+919891643790',
        'whatsapp'    => '919891643790',
        'location'    => 'South Delhi · Gurgaon',
        'address'     => 'South Delhi & Gurgaon, India',
        'env'         => getenv('APP_ENV') ?: 'production',
        'base_url'    => getenv('BASE_URL') ?: '',
    ],

    'hours' => [
        ['days' => 'Monday – Friday', 'time' => '8:00 AM – 2:00 PM'],
        ['days' => 'Saturday',        'time' => '9:00 AM – 6:00 PM'],
        ['days' => 'Sunday',          'time' => 'Closed'],
    ],

    'db' => [
        // 'sqlite' (default, no server required) or 'mysql'
        'driver'   => getenv('DB_DRIVER') ?: 'sqlite',
        'sqlite'   => [
            'path' => __DIR__ . '/../data/lsdrycleaners.sqlite',
        ],
        'mysql'    => [
            'host'     => getenv('DB_HOST') ?: '127.0.0.1',
            'port'     => getenv('DB_PORT') ?: '3306',
            'database' => getenv('DB_NAME') ?: 'ls_drycleaners',
            'username' => getenv('DB_USER') ?: 'root',
            'password' => getenv('DB_PASS') ?: '',
            'charset'  => 'utf8mb4',
        ],
    ],

    'social' => [
        ['label' => 'Instagram', 'url' => 'https://instagram.com'],
        ['label' => 'Facebook',  'url' => 'https://facebook.com'],
        ['label' => 'WhatsApp',  'url' => 'https://wa.me/919891643790'],
    ],
];
