<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Settings Fields Metadata
    |--------------------------------------------------------------------------
    | Define all settings fields with their properties, validation, and UI hints.
    | This metadata is used for validation, forms, and API documentation.
    */

    'fields' => [
        'general' => [
            'label' => [
                'ar' => 'الإعدادات العامة',
                'en' => 'General Settings',
            ],
            'icon' => 'fas fa-cog',
            'order' => 1,
            'fields' => [
                'site_name' => [
                    'label' => [
                        'ar' => 'اسم الموقع',
                        'en' => 'Site Name',
                    ],
                    'type' => 'text',
                    'rules' => ['required', 'string', 'max:255'],
                    'description' => [
                        'ar' => 'الاسم الذي يظهر في شريط العنوان',
                        'en' => 'The name that appears in the title bar',
                    ],
                    'order' => 1,
                    'is_translatable' => true,
                    'placeholder' => [
                        'ar' => 'مثال: متجري',
                        'en' => 'Example: My Store',
                    ],
                    'icon' => 'fas fa-globe',
                ],
                'site_description' => [
                    'label' => [
                        'ar' => 'وصف الموقع',
                        'en' => 'Site Description',
                    ],
                    'type' => 'textarea',
                    'rules' => ['nullable', 'string', 'max:1000'],
                    'description' => [
                        'ar' => 'وصف الموقع لمحركات البحث',
                        'en' => 'Site description for search engines',
                    ],
                    'order' => 2,
                    'is_translatable' => true,
                    'rows' => 4,
                ],
                'site_logo' => [
                    'label' => [
                        'ar' => 'شعار الموقع',
                        'en' => 'Site Logo',
                    ],
                    'type' => 'image',
                    'rules' => ['nullable', 'image', 'max:2048', 'mimes:png,jpg,svg,webp'],
                    'description' => [
                        'ar' => 'شعار الموقع (يفضل 200x200)',
                        'en' => 'Site logo (recommended 200x200px)',
                    ],
                    'order' => 3,
                    'is_translatable' => false,
                    'accepted' => 'image/*',
                    'max_size' => 2048, // KB
                ],
                'site_favicon' => [
                    'label' => [
                        'ar' => 'أيقونة الموقع',
                        'en' => 'Site Favicon',
                    ],
                    'type' => 'image',
                    'rules' => ['nullable', 'image', 'max:512', 'mimes:ico,png'],
                    'description' => [
                        'ar' => 'أيقونة تبويب المتصفح (16x16 أو 32x32)',
                        'en' => 'Browser tab icon (16x16 or 32x32)',
                    ],
                    'order' => 4,
                    'is_translatable' => false,
                ],
                'items_per_page' => [
                    'label' => [
                        'ar' => 'عدد العناصر في الصفحة',
                        'en' => 'Items Per Page',
                    ],
                    'type' => 'number',
                    'rules' => ['nullable', 'integer', 'between:5,100'],
                    'description' => [
                        'ar' => 'عدد العناصر المعروضة في كل صفحة',
                        'en' => 'Number of items displayed per page',
                    ],
                    'order' => 5,
                    'is_translatable' => false,
                    'min' => 5,
                    'max' => 100,
                    'step' => 1,
                    'default' => 20,
                ],
                'enable_comments' => [
                    'label' => [
                        'ar' => 'تفعيل التعليقات',
                        'en' => 'Enable Comments',
                    ],
                    'type' => 'boolean',
                    'rules' => ['boolean'],
                    'description' => [
                        'ar' => 'السماح للمستخدمين بإضافة تعليقات',
                        'en' => 'Allow users to add comments',
                    ],
                    'order' => 6,
                    'is_translatable' => false,
                    'default' => true,
                ],
                'site_timezone' => [
                    'label' => [
                        'ar' => 'المنطقة الزمنية',
                        'en' => 'Site Timezone',
                    ],
                    'type' => 'select',
                    'rules' => ['nullable', 'timezone'],
                    'options' => [
                        'Asia/Riyadh' => [
                            'ar' => 'الرياض (GMT+3)',
                            'en' => 'Riyadh (GMT+3)',
                        ],
                        'Asia/Dubai' => [
                            'ar' => 'دبي (GMT+4)',
                            'en' => 'Dubai (GMT+4)',
                        ],
                        'Asia/Kuwait' => [
                            'ar' => 'الكويت (GMT+3)',
                            'en' => 'Kuwait (GMT+3)',
                        ],
                        'Africa/Cairo' => [
                            'ar' => 'القاهرة (GMT+2)',
                            'en' => 'Cairo (GMT+2)',
                        ],
                        'UTC' => 'UTC (GMT+0)',
                    ],
                    'order' => 7,
                    'is_translatable' => false,
                    'default' => 'Asia/Riyadh',
                    'searchable' => true,
                ],
                'site_language' => [
                    'label' => [
                        'ar' => 'اللغة الافتراضية',
                        'en' => 'Default Language',
                    ],
                    'type' => 'select',
                    'rules' => ['required', 'in:ar,en,fr'],
                    'options' => [
                        'ar' => 'العربية',
                        'en' => 'English',
                        'fr' => 'Français',
                    ],
                    'order' => 8,
                    'is_translatable' => false,
                    'default' => 'ar',
                ],
                'maintenance_mode' => [
                    'label' => [
                        'ar' => 'وضع الصيانة',
                        'en' => 'Maintenance Mode',
                    ],
                    'type' => 'boolean',
                    'rules' => ['boolean'],
                    'description' => [
                        'ar' => 'تفعيل وضع الصيانة (سيكون الموقع غير متاح)',
                        'en' => 'Enable maintenance mode (site will be offline)',
                    ],
                    'order' => 99,
                    'is_translatable' => false,
                    'is_system' => true,
                    'default' => false,
                    'requires_restart' => true,
                ],
                'debug_mode' => [
                    'label' => [
                        'ar' => 'وضع التصحيح',
                        'en' => 'Debug Mode',
                    ],
                    'type' => 'boolean',
                    'rules' => ['boolean'],
                    'description' => [
                        'ar' => 'إظهار أخطاء التصحيح (للمطورين فقط)',
                        'en' => 'Show debug errors (developers only)',
                    ],
                    'order' => 100,
                    'is_translatable' => false,
                    'is_system' => true,
                    'default' => false,
                    'warning' => [
                        'ar' => '⚠️ لا تفعل أبداً في الإنتاج!',
                        'en' => '⚠️ Never enable in production!',
                    ],
                ],
            ],
        ],

        'contact' => [
            'label' => [
                'ar' => 'معلومات الاتصال',
                'en' => 'Contact Information',
            ],
            'icon' => 'fas fa-address-book',
            'order' => 2,
            'fields' => [
                'company_name' => [
                    'label' => [
                        'ar' => 'اسم الشركة',
                        'en' => 'Company Name',
                    ],
                    'type' => 'text',
                    'rules' => ['nullable', 'string', 'max:255'],
                    'order' => 1,
                    'is_translatable' => true,
                    'placeholder' => [
                        'ar' => 'شركة ذات مسؤولية محدودة',
                        'en' => 'Company Ltd.',
                    ],
                ],
                'company_email' => [
                    'label' => [
                        'ar' => 'البريد الإلكتروني',
                        'en' => 'Company Email',
                    ],
                    'type' => 'email',
                    'rules' => ['nullable', 'email', 'max:255'],
                    'order' => 2,
                    'is_translatable' => false,
                    'placeholder' => [
                        'ar' => 'info@company.com',
                        'en' => 'info@company.com',
                    ],
                ],
                'company_phone' => [
                    'label' => [
                        'ar' => 'رقم الهاتف',
                        'en' => 'Phone Number',
                    ],
                    'type' => 'tel',
                    'rules' => ['nullable', 'string', 'max:20'],
                    'order' => 3,
                    'is_translatable' => false,
                    'placeholder' => [
                        'ar' => '+966 50 000 0000',
                        'en' => '+966 50 000 0000',
                    ],
                    'pattern' => '^\\+?[0-9\\s\\-\\(\\)]+$',
                ],
                'company_whatsapp' => [
                    'label' => [
                        'ar' => 'رقم واتساب',
                        'en' => 'WhatsApp Number',
                    ],
                    'type' => 'tel',
                    'rules' => ['nullable', 'string', 'max:20'],
                    'order' => 4,
                    'is_translatable' => false,
                ],
                'company_address' => [
                    'label' => [
                        'ar' => 'العنوان',
                        'en' => 'Address',
                    ],
                    'type' => 'textarea',
                    'rules' => ['nullable', 'string', 'max:1000'],
                    'order' => 5,
                    'is_translatable' => true,
                    'rows' => 3,
                ],
                'company_city' => [
                    'label' => [
                        'ar' => 'المدينة',
                        'en' => 'City',
                    ],
                    'type' => 'text',
                    'rules' => ['nullable', 'string', 'max:100'],
                    'order' => 6,
                    'is_translatable' => true,
                ],
                'company_country' => [
                    'label' => [
                        'ar' => 'الدولة',
                        'en' => 'Country',
                    ],
                    'type' => 'select',
                    'rules' => ['nullable', 'string', 'max:2'],
                    'options' => [
                        'SA' => [
                            'ar' => 'المملكة العربية السعودية',
                            'en' => 'Saudi Arabia',
                        ],
                        'AE' => [
                            'ar' => 'الإمارات العربية المتحدة',
                            'en' => 'United Arab Emirates',
                        ],
                        'KW' => [
                            'ar' => 'الكويت',
                            'en' => 'Kuwait',
                        ],
                        'EG' => [
                            'ar' => 'مصر',
                            'en' => 'Egypt',
                        ],
                    ],
                    'order' => 7,
                    'is_translatable' => false,
                ],
                'working_hours' => [
                    'label' => [
                        'ar' => 'ساعات العمل',
                        'en' => 'Working Hours',
                    ],
                    'type' => 'repeater',
                    'rules' => ['nullable', 'array'],
                    'description' => [
                        'ar' => 'أوقات العمل خلال الأسبوع',
                        'en' => 'Weekly working hours',
                    ],
                    'order' => 8,
                    'is_translatable' => false,
                    'repeater_fields' => [
                        'day' => [
                            'type' => 'select',
                            'label' => [
                                'ar' => 'اليوم',
                                'en' => 'Day',
                            ],
                            'options' => [
                                'saturday' => [
                                    'ar' => 'السبت',
                                    'en' => 'Saturday',
                                ],
                                'sunday' => [
                                    'ar' => 'الأحد',
                                    'en' => 'Sunday',
                                ],
                                'monday' => [
                                    'ar' => 'الإثنين',
                                    'en' => 'Monday',
                                ],
                                'tuesday' => [
                                    'ar' => 'الثلاثاء',
                                    'en' => 'Tuesday',
                                ],
                                'wednesday' => [
                                    'ar' => 'الأربعاء',
                                    'en' => 'Wednesday',
                                ],
                                'thursday' => [
                                    'ar' => 'الخميس',
                                    'en' => 'Thursday',
                                ],
                                'friday' => [
                                    'ar' => 'الجمعة',
                                    'en' => 'Friday',
                                ],
                            ],
                            'required' => true,
                        ],
                        'from' => [
                            'type' => 'time',
                            'label' => [
                                'ar' => 'من',
                                'en' => 'From',
                            ],
                            'required' => true,
                        ],
                        'to' => [
                            'type' => 'time',
                            'label' => [
                                'ar' => 'إلى',
                                'en' => 'To',
                            ],
                            'required' => true,
                        ],
                        'is_off' => [
                            'type' => 'boolean',
                            'label' => [
                                'ar' => 'إجازة',
                                'en' => 'Day Off',
                            ],
                            'default' => false,
                        ],
                    ],
                ],
            ],
        ],

        'social' => [
            'label' => [
                'ar' => 'وسائل التواصل الاجتماعي',
                'en' => 'Social Media',
            ],
            'icon' => 'fas fa-share-alt',
            'order' => 3,
            'fields' => [
                'facebook_url' => [
                    'label' => [
                        'ar' => 'فيسبوك',
                        'en' => 'Facebook',
                    ],
                    'type' => 'url',
                    'rules' => ['nullable', 'url'],
                    'placeholder' => 'https://facebook.com/username',
                    'order' => 1,
                    'is_translatable' => false,
                    'icon' => 'fab fa-facebook',
                ],
                'twitter_url' => [
                    'label' => [
                        'ar' => 'تويتر / إكس',
                        'en' => 'Twitter / X',
                    ],
                    'type' => 'url',
                    'rules' => ['nullable', 'url'],
                    'placeholder' => 'https://twitter.com/username',
                    'order' => 2,
                    'is_translatable' => false,
                    'icon' => 'fab fa-twitter',
                ],
                'instagram_url' => [
                    'label' => [
                        'ar' => 'إنستغرام',
                        'en' => 'Instagram',
                    ],
                    'type' => 'url',
                    'rules' => ['nullable', 'url'],
                    'placeholder' => 'https://instagram.com/username',
                    'order' => 3,
                    'is_translatable' => false,
                    'icon' => 'fab fa-instagram',
                ],
                'linkedin_url' => [
                    'label' => [
                        'ar' => 'لينكد إن',
                        'en' => 'LinkedIn',
                    ],
                    'type' => 'url',
                    'rules' => ['nullable', 'url'],
                    'placeholder' => 'https://linkedin.com/company/name',
                    'order' => 4,
                    'is_translatable' => false,
                    'icon' => 'fab fa-linkedin',
                ],
                'youtube_url' => [
                    'label' => [
                        'ar' => 'يوتيوب',
                        'en' => 'YouTube',
                    ],
                    'type' => 'url',
                    'rules' => ['nullable', 'url'],
                    'placeholder' => 'https://youtube.com/c/channel',
                    'order' => 5,
                    'is_translatable' => false,
                    'icon' => 'fab fa-youtube',
                ],
                'tiktok_url' => [
                    'label' => [
                        'ar' => 'تيك توك',
                        'en' => 'TikTok',
                    ],
                    'type' => 'url',
                    'rules' => ['nullable', 'url'],
                    'placeholder' => 'https://tiktok.com/@username',
                    'order' => 6,
                    'is_translatable' => false,
                    'icon' => 'fab fa-tiktok',
                ],
                'snapchat_url' => [
                    'label' => [
                        'ar' => 'سناب شات',
                        'en' => 'Snapchat',
                    ],
                    'type' => 'url',
                    'rules' => ['nullable', 'url'],
                    'placeholder' => 'https://snapchat.com/add/username',
                    'order' => 7,
                    'is_translatable' => false,
                    'icon' => 'fab fa-snapchat',
                ],
            ],
        ],

        'seo' => [
            'label' => [
                'ar' => 'إعدادات تحسين محركات البحث',
                'en' => 'SEO Settings',
            ],
            'icon' => 'fas fa-search',
            'order' => 4,
            'fields' => [
                'meta_title' => [
                    'label' => [
                        'ar' => 'عنوان الصفحة',
                        'en' => 'Meta Title',
                    ],
                    'type' => 'text',
                    'rules' => ['nullable', 'string', 'max:60'],
                    'description' => [
                        'ar' => 'موصى به: 50-60 حرف',
                        'en' => 'Recommended: 50-60 characters',
                    ],
                    'order' => 1,
                    'is_translatable' => true,
                    'counter' => ['max' => 60],
                ],
                'meta_description' => [
                    'label' => [
                        'ar' => 'وصف الصفحة',
                        'en' => 'Meta Description',
                    ],
                    'type' => 'textarea',
                    'rules' => ['nullable', 'string', 'max:160'],
                    'description' => [
                        'ar' => 'موصى به: 150-160 حرف',
                        'en' => 'Recommended: 150-160 characters',
                    ],
                    'order' => 2,
                    'is_translatable' => true,
                    'rows' => 3,
                    'counter' => ['max' => 160],
                ],
                'meta_keywords' => [
                    'label' => [
                        'ar' => 'الكلمات المفتاحية',
                        'en' => 'Meta Keywords',
                    ],
                    'type' => 'tags',
                    'rules' => ['nullable', 'array'],
                    'description' => [
                        'ar' => 'كلمات مفتاحية مفصولة بفواصل',
                        'en' => 'Comma separated keywords',
                    ],
                    'order' => 3,
                    'is_translatable' => true,
                ],
                'og_image' => [
                    'label' => [
                        'ar' => 'صورة المشاركة',
                        'en' => 'Open Graph Image',
                    ],
                    'type' => 'image',
                    'rules' => ['nullable', 'image', 'max:2048'],
                    'description' => [
                        'ar' => 'صورة المشاركة (1200x630)',
                        'en' => 'Social media sharing image (1200x630)',
                    ],
                    'order' => 4,
                    'is_translatable' => false,
                    'dimensions' => ['width' => 1200, 'height' => 630],
                ],
            ],
        ],

        'mail' => [
            'label' => [
                'ar' => 'إعدادات البريد',
                'en' => 'Mail Settings',
            ],
            'icon' => 'fas fa-envelope',
            'order' => 5,
            'is_system' => true,
            'fields' => [
                'mail_driver' => [
                    'label' => [
                        'ar' => 'نظام البريد',
                        'en' => 'Mail Driver',
                    ],
                    'type' => 'select',
                    'rules' => ['required', 'in:smtp,sendmail,mailgun,ses,log,array'],
                    'options' => [
                        'smtp' => 'SMTP',
                        'sendmail' => 'Sendmail',
                        'mailgun' => 'Mailgun',
                        'ses' => 'Amazon SES',
                        'log' => [
                            'ar' => 'سجل (للاختبار)',
                            'en' => 'Log (for testing)',
                        ],
                        'array' => [
                            'ar' => 'معطل',
                            'en' => 'Disabled',
                        ],
                    ],
                    'order' => 1,
                    'is_translatable' => false,
                    'default' => 'smtp',
                ],
                'mail_host' => [
                    'label' => [
                        'ar' => 'سيرفر البريد',
                        'en' => 'Mail Host',
                    ],
                    'type' => 'text',
                    'rules' => ['nullable', 'string', 'max:255'],
                    'placeholder' => 'smtp.gmail.com',
                    'order' => 2,
                    'is_translatable' => false,
                ],
                'mail_port' => [
                    'label' => [
                        'ar' => 'المنفذ',
                        'en' => 'Mail Port',
                    ],
                    'type' => 'number',
                    'rules' => ['nullable', 'integer'],
                    'default' => 587,
                    'order' => 3,
                    'is_translatable' => false,
                ],
                'mail_username' => [
                    'label' => [
                        'ar' => 'اسم المستخدم',
                        'en' => 'Mail Username',
                    ],
                    'type' => 'text',
                    'rules' => ['nullable', 'string', 'max:255'],
                    'order' => 4,
                    'is_translatable' => false,
                ],
                'mail_password' => [
                    'label' => [
                        'ar' => 'كلمة المرور',
                        'en' => 'Mail Password',
                    ],
                    'type' => 'password',
                    'rules' => ['nullable', 'string', 'max:255'],
                    'order' => 5,
                    'is_translatable' => false,
                    'is_sensitive' => true,
                    'encrypted' => true,
                ],
                'mail_encryption' => [
                    'label' => [
                        'ar' => 'نوع التشفير',
                        'en' => 'Encryption',
                    ],
                    'type' => 'select',
                    'rules' => ['nullable', 'in:tls,ssl,starttls'],
                    'options' => [
                        'tls' => 'TLS',
                        'ssl' => 'SSL',
                        'starttls' => 'STARTTLS',
                    ],
                    'order' => 6,
                    'is_translatable' => false,
                    'default' => 'tls',
                ],
                'mail_from_address' => [
                    'label' => [
                        'ar' => 'عنوان المرسل',
                        'en' => 'From Address',
                    ],
                    'type' => 'email',
                    'rules' => ['nullable', 'email', 'max:255'],
                    'order' => 7,
                    'is_translatable' => false,
                ],
                'mail_from_name' => [
                    'label' => [
                        'ar' => 'اسم المرسل',
                        'en' => 'From Name',
                    ],
                    'type' => 'text',
                    'rules' => ['nullable', 'string', 'max:255'],
                    'order' => 8,
                    'is_translatable' => true,
                ],
            ],
        ],

        'analytics' => [
            'label' => [
                'ar' => 'التحليلات والتتبع',
                'en' => 'Analytics & Tracking',
            ],
            'icon' => 'fas fa-chart-line',
            'order' => 6,
            'fields' => [
                'enable_analytics' => [
                    'label' => [
                        'ar' => 'تفعيل التحليلات',
                        'en' => 'Enable Analytics',
                    ],
                    'type' => 'boolean',
                    'rules' => ['boolean'],
                    'description' => [
                        'ar' => 'تفعيل جميع أدوات التحليل',
                        'en' => 'Enable all analytics tools',
                    ],
                    'order' => 1,
                    'is_translatable' => false,
                    'default' => false,
                ],
                'google_analytics_id' => [
                    'label' => [
                        'ar' => 'معرف Google Analytics',
                        'en' => 'Google Analytics ID',
                    ],
                    'type' => 'text',
                    'rules' => ['nullable', 'string', 'max:50'],
                    'placeholder' => 'G-XXXXXXXXXX',
                    'order' => 2,
                    'is_translatable' => false,
                    'is_sensitive' => true,
                    'encrypted' => true,
                ],
                'google_tag_manager_id' => [
                    'label' => [
                        'ar' => 'معرف Google Tag Manager',
                        'en' => 'Google Tag Manager ID',
                    ],
                    'type' => 'text',
                    'rules' => ['nullable', 'string', 'max:50'],
                    'placeholder' => 'GTM-XXXXXXX',
                    'order' => 3,
                    'is_translatable' => false,
                    'is_sensitive' => true,
                    'encrypted' => true,
                ],
                'facebook_pixel_id' => [
                    'label' => [
                        'ar' => 'معرف Facebook Pixel',
                        'en' => 'Facebook Pixel ID',
                    ],
                    'type' => 'text',
                    'rules' => ['nullable', 'string', 'max:50'],
                    'placeholder' => '123456789012345',
                    'order' => 4,
                    'is_translatable' => false,
                    'is_sensitive' => true,
                    'encrypted' => true,
                ],
                'custom_tracking_code' => [
                    'label' => [
                        'ar' => 'كود التتبع المخصص',
                        'en' => 'Custom Tracking Code',
                    ],
                    'type' => 'textarea',
                    'rules' => ['nullable', 'string'],
                    'description' => [
                        'ar' => 'إضافة أكواد تتبع مخصصة',
                        'en' => 'Add custom tracking scripts',
                    ],
                    'order' => 99,
                    'is_translatable' => false,
                    'rows' => 5,
                    'monospace' => true,
                ],
            ],
        ],

        'payment' => [
            'label' => [
                'ar' => 'بوابات الدفع',
                'en' => 'Payment Gateways',
            ],
            'icon' => 'fas fa-credit-card',
            'order' => 7,
            'is_system' => true,
            'fields' => [
                'stripe_enabled' => [
                    'label' => [
                        'ar' => 'تفعيل Stripe',
                        'en' => 'Enable Stripe',
                    ],
                    'type' => 'boolean',
                    'rules' => ['boolean'],
                    'order' => 1,
                    'is_translatable' => false,
                    'default' => false,
                ],
                'stripe_key' => [
                    'label' => [
                        'ar' => 'مفتاح Stripe العام',
                        'en' => 'Stripe Public Key',
                    ],
                    'type' => 'text',
                    'rules' => ['nullable', 'string'],
                    'order' => 2,
                    'is_translatable' => false,
                    'is_sensitive' => true,
                    'encrypted' => true,
                ],
                'stripe_secret' => [
                    'label' => [
                        'ar' => 'مفتاح Stripe السري',
                        'en' => 'Stripe Secret Key',
                    ],
                    'type' => 'password',
                    'rules' => ['nullable', 'string'],
                    'order' => 3,
                    'is_translatable' => false,
                    'is_sensitive' => true,
                    'encrypted' => true,
                ],
                'paypal_enabled' => [
                    'label' => [
                        'ar' => 'تفعيل PayPal',
                        'en' => 'Enable PayPal',
                    ],
                    'type' => 'boolean',
                    'rules' => ['boolean'],
                    'order' => 10,
                    'is_translatable' => false,
                    'default' => false,
                ],
                'paypal_client_id' => [
                    'label' => [
                        'ar' => 'معرف PayPal',
                        'en' => 'PayPal Client ID',
                    ],
                    'type' => 'text',
                    'rules' => ['nullable', 'string'],
                    'order' => 11,
                    'is_translatable' => false,
                    'is_sensitive' => true,
                    'encrypted' => true,
                ],
                'paypal_secret' => [
                    'label' => [
                        'ar' => 'كلمة سر PayPal',
                        'en' => 'PayPal Secret',
                    ],
                    'type' => 'password',
                    'rules' => ['nullable', 'string'],
                    'order' => 12,
                    'is_translatable' => false,
                    'is_sensitive' => true,
                    'encrypted' => true,
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Complex Data Groups (Partners, Customers, Team, etc.)
    |--------------------------------------------------------------------------
    | These groups use 'is_entry' => true for multi-record data storage.
    | Each entry is stored as a separate row in the settings table.
    */

    'partners' => [
        'label' => [
            'ar' => 'الشركاء',
            'en' => 'Partners',
        ],
        'icon' => 'fas fa-handshake',
        'order' => 8,
        'is_entry' => true,
        'entry_fields' => [
            'name' => [
                'type' => 'text',
                'label' => [
                    'ar' => 'اسم الشريك',
                    'en' => 'Partner Name',
                ],
                'required' => true,
                'is_translatable' => true,
            ],
            'logo' => [
                'type' => 'image',
                'label' => [
                    'ar' => 'الشعار',
                    'en' => 'Logo',
                ],
                'required' => true,
            ],
            'website' => [
                'type' => 'url',
                'label' => [
                    'ar' => 'الموقع الإلكتروني',
                    'en' => 'Website',
                ],
                'required' => false,
            ],
            'description' => [
                'type' => 'textarea',
                'label' => [
                    'ar' => 'الوصف',
                    'en' => 'Description',
                ],
                'required' => false,
                'is_translatable' => true,
            ],
            'is_featured' => [
                'type' => 'boolean',
                'label' => [
                    'ar' => 'مميز',
                    'en' => 'Featured',
                ],
                'default' => false,
            ],
        ],
    ],

    'customers' => [
        'label' => [
            'ar' => 'العملاء',
            'en' => 'Customers',
        ],
        'icon' => 'fas fa-users',
        'order' => 9,
        'is_entry' => true,
        'entry_fields' => [
            'name' => [
                'type' => 'text',
                'label' => [
                    'ar' => 'اسم العميل',
                    'en' => 'Customer Name',
                ],
                'required' => true,
                'is_translatable' => true,
            ],
            'company' => [
                'type' => 'text',
                'label' => [
                    'ar' => 'الشركة',
                    'en' => 'Company',
                ],
                'required' => false,
                'is_translatable' => true,
            ],
            'email' => [
                'type' => 'email',
                'label' => [
                    'ar' => 'البريد الإلكتروني',
                    'en' => 'Email',
                ],
                'required' => false,
            ],
            'phone' => [
                'type' => 'tel',
                'label' => [
                    'ar' => 'الهاتف',
                    'en' => 'Phone',
                ],
                'required' => false,
            ],
            'notes' => [
                'type' => 'textarea',
                'label' => [
                    'ar' => 'ملاحظات',
                    'en' => 'Notes',
                ],
                'required' => false,
                'is_translatable' => true,
            ],
        ],
    ],

    'team' => [
        'label' => [
            'ar' => 'فريق العمل',
            'en' => 'Team Members',
        ],
        'icon' => 'fas fa-user-tie',
        'order' => 10,
        'is_entry' => true,
        'entry_fields' => [
            'name' => [
                'type' => 'text',
                'label' => [
                    'ar' => 'الاسم',
                    'en' => 'Name',
                ],
                'required' => true,
                'is_translatable' => true,
            ],
            'job_title' => [
                'type' => 'text',
                'label' => [
                    'ar' => 'المسمى الوظيفي',
                    'en' => 'Job Title',
                ],
                'required' => true,
                'is_translatable' => true,
            ],
            'avatar' => [
                'type' => 'image',
                'label' => [
                    'ar' => 'الصورة الشخصية',
                    'en' => 'Avatar',
                ],
                'required' => false,
            ],
            'bio' => [
                'type' => 'textarea',
                'label' => [
                    'ar' => 'نبذة',
                    'en' => 'Biography',
                ],
                'required' => false,
                'is_translatable' => true,
            ],
            'email' => [
                'type' => 'email',
                'label' => [
                    'ar' => 'البريد الإلكتروني',
                    'en' => 'Email',
                ],
                'required' => false,
            ],
        ],
    ],

    'services' => [
        'label' => [
            'ar' => 'الخدمات',
            'en' => 'Services',
        ],
        'icon' => 'fas fa-concierge-bell',
        'order' => 11,
        'is_entry' => true,
        'entry_fields' => [
            'name' => [
                'type' => 'text',
                'label' => [
                    'ar' => 'اسم الخدمة',
                    'en' => 'Service Name',
                ],
                'required' => true,
                'is_translatable' => true,
            ],
            'description' => [
                'type' => 'textarea',
                'label' => [
                    'ar' => 'الوصف',
                    'en' => 'Description',
                ],
                'required' => true,
                'is_translatable' => true,
            ],
            'price' => [
                'type' => 'number',
                'label' => [
                    'ar' => 'السعر',
                    'en' => 'Price',
                ],
                'required' => false,
                'min' => 0,
            ],
            'is_featured' => [
                'type' => 'boolean',
                'label' => [
                    'ar' => 'مميز',
                    'en' => 'Featured',
                ],
                'default' => false,
            ],
        ],
    ],

    'testimonials' => [
        'label' => [
            'ar' => 'آراء العملاء',
            'en' => 'Testimonials',
        ],
        'icon' => 'fas fa-comments',
        'order' => 12,
        'is_entry' => true,
        'entry_fields' => [
            'client_name' => [
                'type' => 'text',
                'label' => [
                    'ar' => 'اسم العميل',
                    'en' => 'Client Name',
                ],
                'required' => true,
                'is_translatable' => true,
            ],
            'client_title' => [
                'type' => 'text',
                'label' => [
                    'ar' => 'المسمى',
                    'en' => 'Title',
                ],
                'required' => false,
                'is_translatable' => true,
            ],
            'rating' => [
                'type' => 'number',
                'label' => [
                    'ar' => 'التقييم',
                    'en' => 'Rating',
                ],
                'required' => true,
                'min' => 1,
                'max' => 5,
                'default' => 5,
            ],
            'testimonial' => [
                'type' => 'textarea',
                'label' => [
                    'ar' => 'الرأي',
                    'en' => 'Testimonial',
                ],
                'required' => true,
                'is_translatable' => true,
            ],
        ],
    ],

    'faqs' => [
        'label' => [
            'ar' => 'الأسئلة الشائعة',
            'en' => 'FAQs',
        ],
        'icon' => 'fas fa-question-circle',
        'order' => 13,
        'is_entry' => true,
        'entry_fields' => [
            'question' => [
                'type' => 'text',
                'label' => [
                    'ar' => 'السؤال',
                    'en' => 'Question',
                ],
                'required' => true,
                'is_translatable' => true,
            ],
            'answer' => [
                'type' => 'textarea',
                'label' => [
                    'ar' => 'الإجابة',
                    'en' => 'Answer',
                ],
                'required' => true,
                'is_translatable' => true,
            ],
            'category' => [
                'type' => 'select',
                'label' => [
                    'ar' => 'التصنيف',
                    'en' => 'Category',
                ],
                'options' => [
                    'general' => 'عام',
                    'technical' => 'تقني',
                    'billing' => 'فواتير',
                    'support' => 'دعم',
                ],
                'required' => false,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Sensitive Fields (Auto-Encryption)
    |--------------------------------------------------------------------------
    */

    'sensitive_fields' => [
        'password',
        'secret',
        'api_key',
        'token',
        'private_key',
        'mail_password',
        'stripe_secret',
        'paypal_secret',
        'google_analytics_id',
        'facebook_pixel_id',
    ],

    /*
    |--------------------------------------------------------------------------
    | Supported Languages
    |--------------------------------------------------------------------------
    */

    'locales' => [
        'ar' => [
            'name' => 'العربية',
            'dir' => 'rtl',
        ],
        'en' => [
            'name' => 'English',
            'dir' => 'ltr',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Locale
    |--------------------------------------------------------------------------
    */

    'default_locale' => 'ar',

    /*
    |--------------------------------------------------------------------------
    | Field Types Configuration
    |--------------------------------------------------------------------------
    */

    'field_types' => [
        'text' => ['component' => 'text-input'],
        'email' => ['component' => 'email-input'],
        'password' => ['component' => 'password-input'],
        'tel' => ['component' => 'tel-input'],
        'url' => ['component' => 'url-input'],
        'number' => ['component' => 'number-input'],
        'textarea' => ['component' => 'textarea-input'],
        'boolean' => ['component' => 'checkbox-input'],
        'select' => ['component' => 'select-input'],
        'image' => ['component' => 'image-upload'],
        'file' => ['component' => 'file-upload'],
        'tags' => ['component' => 'tags-input'],
        'repeater' => ['component' => 'repeater-field'],
        'code' => ['component' => 'code-editor'],
        'datetime' => ['component' => 'datetime-picker'],
        'date' => ['component' => 'date-picker'],
        'time' => ['component' => 'time-picker'],
        'color' => ['component' => 'color-picker'],
        'markdown' => ['component' => 'markdown-editor'],
        'rich_text' => ['component' => 'rich-text-editor'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    */

    'cache' => [
        'enabled' => true,
        'ttl' => 3600, // seconds
        'prefix' => 'settings_',
        'store' => 'redis', // or 'file', 'database', 'memcached'
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    */

    'security' => [
        'auto_encryption' => true, // Auto-encrypt sensitive fields
        'encryption_cipher' => 'AES-256-CBC',
        'audit_log_enabled' => true, // Log changes
        'require_permission' => false, // Require permission to edit
        'permission_name' => 'edit-settings',
        'allowed_ips' => [], // Empty = all IPs allowed
    ],

    /*
    |--------------------------------------------------------------------------
    | InertiaJS Configuration
    |--------------------------------------------------------------------------
    */

    'inertia' => [
        'share_public' => true,
        'key_name' => 'settings',
        'exclude_groups' => ['mail', 'payment', 'analytics'], // Don't share sensitive
    ],

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    */

    'api' => [
        'enabled' => true,
        'middleware' => ['auth:sanctum'],
        'rate_limit' => 60, // requests per minute
    ],

];
