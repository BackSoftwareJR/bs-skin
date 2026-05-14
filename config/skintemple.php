<?php

return [

    'admin' => [
        'email_primary' => env('SKINTEMPLE_ADMIN_EMAIL_PRIMARY', 'jrovera05@gmail.com'),
        'email_secondary' => env('SKINTEMPLE_ADMIN_EMAIL_SECONDARY', 'backsoftware.crm@gmail.com'),
    ],

    'payment' => [
        'default_provider' => env('SKINTEMPLE_PAYMENT_PROVIDER', 'manual'),
        'manual_instructions' => env('SKINTEMPLE_PAYMENT_INSTRUCTIONS'),
    ],

    'newsletter' => [
        'provider' => env('SKINTEMPLE_NEWSLETTER_PROVIDER', 'database'),
    ],

    'invoice' => [
        'provider' => env('INVOICE_PROVIDER', 'none'),
    ],

    'shipping' => [
        'flat_rate' => (float) env('SKINTEMPLE_SHIPPING_FLAT_RATE', 7.90),
        'free_threshold' => (float) env('SKINTEMPLE_SHIPPING_FREE_THRESHOLD', 99.00),
    ],

    'media' => [
        'max_size_kb' => (int) env('MEDIA_MAX_SIZE_KB', 800),
        'max_width' => (int) env('MEDIA_MAX_WIDTH', 2400),
        'max_height' => (int) env('MEDIA_MAX_HEIGHT', 2400),
        'allowed_mime' => explode(',', env('MEDIA_ALLOWED_MIME', 'image/webp,image/jpeg,image/png')),
        'driver' => env('IMAGE_DRIVER', 'gd'),
    ],

    'company' => [
        'name' => 'SkinTemple',
        'vat' => '11863510019',
        'address' => 'Strada Santa Vittoria 11, 10024 Moncalieri (TO)',
        'country' => 'Italia',
        'email' => 'info@skintemple.it',
    ],

];
