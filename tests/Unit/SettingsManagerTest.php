<?php

use Salehye\Settings\Facades\Settings;
use Salehye\Settings\Models\Setting;
use Salehye\Settings\SettingsManager;

beforeEach(function () {
    // Run the settings table migration before each test
    if (!Schema::hasTable('settings')) {
        include_once __DIR__ . '/../../database/migrations/create_settings_table.php.stub';
        (new \CreateSettingsTable())->up();
    }

    // Set up test config for settings fields
    config([
        'settings.fields' => [
            'general' => [
                'label' => ['en' => 'General Settings', 'ar' => 'الإعدادات العامة'],
                'icon' => 'fas fa-cog',
                'order' => 1,
                'fields' => [
                    'site_name' => [
                        'label' => ['en' => 'Site Name', 'ar' => 'اسم الموقع'],
                        'type' => 'text',
                        'rules' => ['required', 'string', 'max:255'],
                        'default' => 'Default Site',
                        'is_translatable' => true,
                    ],
                    'timezone' => [
                        'label' => ['en' => 'Timezone', 'ar' => 'المنطقة الزمنية'],
                        'type' => 'select',
                        'default' => 'UTC',
                    ],
                    'maintenance_mode' => [
                        'label' => ['en' => 'Maintenance Mode', 'ar' => 'وضع الصيانة'],
                        'type' => 'boolean',
                        'is_system' => true,
                        'default' => false,
                    ],
                    'seo_keywords' => [
                        'label' => ['en' => 'SEO Keywords', 'ar' => 'كلمات مفتاحية'],
                        'type' => 'tags',
                        'default' => [],
                    ],
                ],
            ],
            'seo' => [
                'label' => ['en' => 'SEO Settings'],
                'fields' => [
                    'seo_title' => [
                        'label' => ['en' => 'SEO Title'],
                        'type' => 'text',
                    ],
                ],
            ],
            'system' => [
                'label' => ['en' => 'System Settings'],
                'is_system' => true,
                'fields' => [
                    'debug_mode' => [
                        'label' => ['en' => 'Debug Mode'],
                        'type' => 'boolean',
                        'is_system' => true,
                    ],
                ],
            ],
        ]
    ]);
});

it('can get a setting value', function () {
    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'value' => 'My Test Site',
    ]);

    expect(Settings::get('site_name'))->toBe('My Test Site');
});

it('returns default value when setting not found', function () {
    expect(Settings::get('nonexistent', 'default_value'))->toBe('default_value');
});

it('returns config default when setting not found', function () {
    expect(Settings::get('site_name'))->toBe('Default Site');
});

it('can set a setting value', function () {
    Settings::set('site_name', 'New Site Name');

    expect(Setting::where('key', 'site_name')->first()->value)
        ->toBe('New Site Name');
});

it('can get multiple settings', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'value' => 'Site 1']);
    Setting::create(['key' => 'timezone', 'group' => 'general', 'value' => 'UTC']);

    $settings = Settings::getMany(['site_name', 'timezone']);

    expect($settings)
        ->toHaveKey('site_name', 'Site 1')
        ->toHaveKey('timezone', 'UTC');
});

it('can set multiple settings', function () {
    $result = Settings::setMany([
        'site_name' => 'Multi Site',
        'timezone' => 'Asia/Riyadh',
    ]);

    expect($result)
        ->toHaveKey('site_name', true)
        ->toHaveKey('timezone', true);
});

it('can get settings by group', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'value' => 'General Site']);
    Setting::create(['key' => 'timezone', 'group' => 'general', 'value' => 'UTC']);
    Setting::create(['key' => 'seo_title', 'group' => 'seo', 'value' => 'SEO Title']);

    $generalSettings = Settings::group('general');

    expect($generalSettings)
        ->toHaveKey('site_name')
        ->toHaveKey('timezone')
        ->not->toHaveKey('seo_title');
});

it('can get public settings only', function () {
    // These keys are defined as public in config
    Setting::create(['key' => 'site_name', 'group' => 'general', 'value' => 'Public Site', 'is_public' => true, 'is_active' => true]);
    Setting::create(['key' => 'maintenance_mode', 'group' => 'general', 'value' => false, 'is_public' => false, 'is_active' => true]);

    $publicSettings = Settings::public();

    expect($publicSettings)
        ->toHaveKey('site_name')
        ->not->toHaveKey('maintenance_mode');
});

it('can get all settings', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'value' => 'All Site']);
    Setting::create(['key' => 'maintenance_mode', 'group' => 'general', 'value' => true]);

    $allSettings = Settings::all();

    expect($allSettings)
        ->toHaveKey('site_name')
        ->toHaveKey('maintenance_mode');
});

it('can check if setting exists', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'value' => 'Exists']);

    expect(Settings::has('site_name'))->toBeTrue()
        ->and(Settings::has('nonexistent'))->toBeFalse();
});

it('can delete a setting', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'value' => 'To Delete']);

    Settings::delete('site_name');

    expect(Setting::where('key', 'site_name')->exists())->toBeFalse();
});

it('can get setting type', function () {
    expect(Settings::type('site_name'))->toBe('text')
        ->and(Settings::type('maintenance_mode'))->toBe('boolean')
        ->and(Settings::type('seo_keywords'))->toBe('tags');
});

it('can get setting label', function () {
    app()->setLocale('en');
    expect(Settings::label('site_name'))->toBe('Site Name');

    app()->setLocale('ar');
    expect(Settings::label('site_name'))->toBe('اسم الموقع');
});

it('can get setting label with default fallback', function () {
    app()->setLocale('fr');
    expect(Settings::label('site_name'))->toBe('Site Name'); // Falls back to en
});

it('can clear cache', function () {
    Settings::set('site_name', 'Cached Site');
    Settings::clearCache();

    expect(Settings::get('site_name'))->toBe('Cached Site');
});

it('can reload settings', function () {
    Settings::set('site_name', 'Before Reload');
    Settings::reload();

    expect(Settings::get('site_name'))->toBe('Before Reload');
});
