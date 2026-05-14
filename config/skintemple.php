<?php
return [
    'name' => env('APP_NAME', 'SkinTemple'),
    
    'company' => [
        'name'     => 'SkinTemple',
        'vat'      => '11863510019',
        'address'  => 'Strada Santa Vittoria 11, 10024 Moncalieri (TO)',
        'email'    => 'info@skintemple.it',
        'phone'    => '',
    ],

    'notifications' => [
        'admin_emails' => array_filter(array_map('trim', explode(',', env('SKINTEMPLE_ADMIN_EMAIL_PRIMARY', 'jrovera05@gmail.com') . ',' . env('SKINTEMPLE_ADMIN_EMAIL_SECONDARY', 'backsoftware.crm@gmail.com')))),
        'notify_new_order'      => true,
        'notify_low_stock'      => true,
        'notify_failed_payment' => true,
    ],

    'commerce' => [
        'currency'                => env('COMMERCE_CURRENCY', 'EUR'),
        'tax_rate_default'        => env('COMMERCE_TAX_RATE', 22.00),
        'prices_include_tax'      => env('COMMERCE_PRICES_INCLUDE_TAX', true),
        'min_order_amount'        => env('COMMERCE_MIN_ORDER', 0),
        'free_shipping_threshold' => env('COMMERCE_FREE_SHIPPING_THRESHOLD', 99.00),
        'default_shipping_cost'   => env('COMMERCE_SHIPPING_COST', 7.90),
        'order_number_prefix'     => env('COMMERCE_ORDER_PREFIX', 'SK-'),
        'invoice_number_prefix'   => env('COMMERCE_INVOICE_PREFIX', 'FT-'),
    ],

    'media' => [
        'disk'             => env('MEDIA_DISK', 'public'),
        'driver'           => env('IMAGE_DRIVER', 'gd'),
        'max_size_kb'      => env('MEDIA_MAX_SIZE_KB', 800),
        'max_width'        => env('MEDIA_MAX_WIDTH', 2400),
        'max_height'       => env('MEDIA_MAX_HEIGHT', 2400),
        'allowed_mimes'    => explode(',', env('MEDIA_ALLOWED_MIME', 'image/webp,image/jpeg,image/png')),
        'conversions'      => ['thumbnail' => [150, 150], 'medium' => [600, 600], 'large' => [1200, 1200]],
    ],

    'otp' => [
        'ttl_minutes'    => 10,
        'max_attempts'   => 5,
        'rate_limit'     => 3,   // max OTP per email ogni rate_window_minutes
        'rate_window_minutes' => 15,
    ],

    'seo' => [
        'default_meta_title'       => 'SkinTemple — Tecnologie Made in Italy',
        'default_meta_description' => 'Soluzioni 100% Made in Italy per centri estetici e studi medici. Tecnologie multifunzione, assistenza dedicata, qualità italiana.',
        'robots'                   => 'index,follow',
    ],

    'features' => [
        'blog'              => env('FEATURE_BLOG', true),
        'wishlist'          => env('FEATURE_WISHLIST', true),
        'reviews'           => env('FEATURE_REVIEWS', false),
        'b2b_pricing'       => env('FEATURE_B2B_PRICING', false),
        'multilingual'      => env('FEATURE_MULTILINGUAL', false),
        'dark_mode'         => env('FEATURE_DARK_MODE', false),
        'einvoice_badge'    => env('FEATURE_EINVOICE_BADGE', true),
        'einvoice_badge_label' => 'Fatturazione elettronica disponibile su richiesta',
    ],

    'integrations' => [
        'stripe'    => ['enabled' => env('STRIPE_ENABLED', false)],
        'paypal'    => ['enabled' => env('PAYPAL_ENABLED', false)],
        'mailchimp' => ['enabled' => env('MAILCHIMP_ENABLED', false)],
        'aruba'     => ['enabled' => env('ARUBA_ENABLED', false)],
        'fic'       => ['enabled' => env('FIC_ENABLED', false)],
    ],

    'announcement' => [
        'text'    => env('ANNOUNCEMENT_TEXT', 'Spedizione gratuita sopra €99 · Tecnologie Made in Italy'),
        'enabled' => env('ANNOUNCEMENT_ENABLED', true),
    ],

    'sitemap' => [
        'cache_minutes' => 360,
        'path'          => 'sitemap.xml',
    ],
];