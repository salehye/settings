<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Settings Definitions (Optional)
    |--------------------------------------------------------------------------
    |
    | Define your settings metadata here. This is OPTIONAL - you can create
    | settings directly in the database without defining them here.
    |
    | Use this for:
    | - Default values for new installations
    | - Validation rules
    | - Translated labels
    | - Type hints
    |
    */

    'definitions' => [
        // Example: General Settings
        'site_name' => [
            'type' => 'string',
            'group' => 'general',
            'is_public' => true,
            'default' => 'My Website',
            'rules' => ['nullable', 'string', 'max:255'],
            'translations' => [
                'ar' => 'اسم الموقع',
                'en' => 'Site Name',
            ],
        ],

        'site_description' => [
            'type' => 'text',
            'group' => 'general',
            'is_public' => true,
            'default' => '',
            'rules' => ['nullable', 'string', 'max:1000'],
            'translations' => [
                'ar' => 'وصف الموقع',
                'en' => 'Site Description',
            ],
        ],

        'timezone' => [
            'type' => 'string',
            'group' => 'general',
            'is_public' => false,
            'default' => 'UTC',
            'rules' => ['nullable', 'timezone'],
        ],

        'locale' => [
            'type' => 'string',
            'group' => 'general',
            'is_public' => false,
            'default' => 'en',
            'rules' => ['nullable', 'in:ar,en'],
        ],

        // Example: System Settings
        'maintenance_mode' => [
            'type' => 'boolean',
            'group' => 'system',
            'is_public' => false,
            'default' => false,
        ],

        'cache_enabled' => [
            'type' => 'boolean',
            'group' => 'system',
            'is_public' => false,
            'default' => true,
        ],

        // Example: SEO Settings
        'seo_title' => [
            'type' => 'string',
            'group' => 'seo',
            'is_public' => true,
            'default' => '',
            'rules' => ['nullable', 'string', 'max:60'],
            'translations' => [
                'ar' => 'عنوان الصفحة',
                'en' => 'Page Title',
            ],
        ],

        'seo_description' => [
            'type' => 'text',
            'group' => 'seo',
            'is_public' => true,
            'default' => '',
            'rules' => ['nullable', 'string', 'max:160'],
            'translations' => [
                'ar' => 'وصف الصفحة',
                'en' => 'Page Description',
            ],
        ],

        'seo_keywords' => [
            'type' => 'json',
            'group' => 'seo',
            'is_public' => true,
            'default' => [],
            'rules' => ['nullable', 'array'],
        ],

        // Example: Social Media
        'social_twitter' => [
            'type' => 'string',
            'group' => 'social',
            'is_public' => true,
            'default' => '',
            'rules' => ['nullable', 'url'],
        ],

        'social_facebook' => [
            'type' => 'string',
            'group' => 'social',
            'is_public' => true,
            'default' => '',
            'rules' => ['nullable', 'url'],
        ],

        'social_instagram' => [
            'type' => 'string',
            'group' => 'social',
            'is_public' => true,
            'default' => '',
            'rules' => ['nullable', 'url'],
        ],

        'social_linkedin' => [
            'type' => 'string',
            'group' => 'social',
            'is_public' => true,
            'default' => '',
            'rules' => ['nullable', 'url'],
        ],

        // Example: Contact
        'contact_email' => [
            'type' => 'string',
            'group' => 'contact',
            'is_public' => true,
            'default' => '',
            'rules' => ['nullable', 'email'],
        ],

        'contact_phone' => [
            'type' => 'string',
            'group' => 'contact',
            'is_public' => true,
            'default' => '',
            'rules' => ['nullable', 'string'],
        ],

        'contact_address' => [
            'type' => 'text',
            'group' => 'contact',
            'is_public' => true,
            'default' => '',
            'rules' => ['nullable', 'string', 'max:500'],
            'translations' => [
                'ar' => 'العنوان',
                'en' => 'Address',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    */

    'cache' => [
        'enabled' => true,
        'ttl' => 3600, // 1 hour in seconds
        'key' => 'settings_cache',
    ],

    /*
    |--------------------------------------------------------------------------
    | InertiaJS Configuration
    |--------------------------------------------------------------------------
    */

    'inertia' => [
        'share_public' => true,
        'key_name' => 'settings',
    ],

    /*
    |--------------------------------------------------------------------------
    | Available Groups (For UI/Organization)
    |--------------------------------------------------------------------------
    */

    'groups' => [
        'general',
        'system',
        'seo',
        'social',
        'contact',
        'features',
        'notifications',
        'payment',
    ],

    /*
    |--------------------------------------------------------------------------
    | Available Types (For Type Casting)
    |--------------------------------------------------------------------------
    */

    'types' => [
        'string',
        'text',
        'boolean',
        'integer',
        'float',
        'json',
        'array',
    ],

];
