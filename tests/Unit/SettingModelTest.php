<?php

use Salehye\Settings\Models\Setting;

beforeEach(function () {
    // Set up test config for settings fields
    config([
        'settings.fields' => [
            'general' => [
                'label' => ['en' => 'General Settings', 'ar' => 'الإعدادات العامة'],
                'icon' => 'fas fa-cog',
                'fields' => [
                    'site_name' => [
                        'label' => ['en' => 'Site Name', 'ar' => 'اسم الموقع'],
                        'type' => 'text',
                        'group' => 'general',
                        'is_public' => true,
                        'default' => 'Default Site',
                        'rules' => ['required', 'string', 'max:255'],
                        'is_translatable' => true,
                    ],
                    'posts_per_page' => [
                        'label' => ['en' => 'Posts Per Page'],
                        'type' => 'integer',
                        'group' => 'general',
                        'is_public' => true,
                        'default' => 10,
                    ],
                    'json_setting' => [
                        'label' => ['en' => 'JSON Setting'],
                        'type' => 'json',
                        'group' => 'general',
                        'is_public' => false,
                        'default' => [],
                    ],
                ],
            ],
            'system' => [
                'label' => ['en' => 'System Settings'],
                'is_system' => true,
                'fields' => [
                    'maintenance_mode' => [
                        'label' => ['en' => 'Maintenance Mode'],
                        'type' => 'boolean',
                        'group' => 'system',
                        'is_public' => false,
                        'is_system' => true,
                        'default' => false,
                    ],
                ],
            ],
            'seo' => [
                'label' => ['en' => 'SEO Settings'],
                'fields' => [
                    'seo_title' => [
                        'label' => ['en' => 'SEO Title'],
                        'type' => 'text',
                        'group' => 'seo',
                        'is_public' => true,
                    ],
                ],
            ],
        ]
    ]);
});

it('can create a setting', function () {
    $setting = Setting::create([
        'key' => 'test_key',
        'group' => 'general',
        'is_public' => false,
        'value' => 'test_value',
    ]);

    expect($setting)->toBeInstanceOf(Setting::class)
        ->and($setting->key)->toBe('test_key')
        ->and($setting->group)->toBe('general')
        ->and($setting->is_public)->toBeFalse()
        ->and($setting->value)->toBe('test_value');
})->group('model');

it('casts value to array', function () {
    $setting = Setting::create([
        'key' => 'json_setting',
        'group' => 'general',
        'is_public' => false,
        'value' => ['key' => 'value'],
    ]);

    expect($setting->value)->toBeArray()
        ->toHaveKey('key', 'value');
});

it('casts is_public to boolean', function () {
    $setting = Setting::create([
        'key' => 'public_setting',
        'group' => 'general',
        'is_public' => true,
        'value' => 'value',
    ]);

    expect($setting->is_public)->toBeNumeric()
        ->and($setting->is_public)->toEqual(1);
});

it('gets typed value for boolean', function () {
    $setting = Setting::create([
        'key' => 'maintenance_mode',
        'group' => 'system',
        'is_public' => false,
        'value' => '1',
    ]);

    expect($setting->getTypedValue())->toBeTrue();
});

it('gets typed value for integer', function () {
    $setting = Setting::create([
        'key' => 'posts_per_page',
        'group' => 'general',
        'is_public' => false,
        'value' => '10',
    ]);

    expect($setting->getTypedValue())->toBe(10);
});

it('sets typed value', function () {
    $setting = Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'is_public' => true,
        'value' => 'Old Name',
    ]);

    $setting->setTypedValue('New Name');

    expect($setting->value)->toBe('New Name');
});

it('gets definition from config', function () {
    $setting = Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'is_public' => true,
        'value' => 'Test',
    ]);

    $definition = $setting->getDefinition();

    expect($definition)->toBeArray()
        ->toHaveKeys(['type', 'label', 'rules', 'is_translatable']);
});

it('gets field definition statically', function () {
    $definition = Setting::getFieldDefinition('general', 'site_name');

    expect($definition)->toBeArray()
        ->and($definition['type'])->toBe('text')
        ->and($definition['label']['en'])->toBe('Site Name');
});

it('returns null for non-existent field definition', function () {
    $definition = Setting::getFieldDefinition('general', 'nonexistent');

    expect($definition)->toBeNull();
});

it('gets group fields', function () {
    $fields = Setting::getGroupFields('general');

    expect($fields)->toBeArray()
        ->toHaveKeys(['site_name', 'posts_per_page', 'json_setting']);
});

it('checks if setting is public from database', function () {
    $publicSetting = Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'is_public' => true,
        'value' => 'Public',
    ]);

    $privateSetting = Setting::create([
        'key' => 'maintenance_mode',
        'group' => 'system',
        'is_public' => false,
        'value' => false,
    ]);

    expect($publicSetting->isPublic())->toBeTrue()
        ->and($privateSetting->isPublic())->toBeFalse();
});

it('gets label with translation', function () {
    app()->setLocale('en');
    $setting = Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'is_public' => true,
        'value' => 'Test',
    ]);

    expect($setting->getLabel())->toBe('Site Name');

    app()->setLocale('ar');
    expect($setting->getLabel())->toBe('اسم الموقع');
});

it('returns key as label when no translation exists', function () {
    $setting = Setting::create([
        'key' => 'custom_key',
        'group' => 'general',
        'is_public' => false,
        'value' => 'Test',
    ]);

    expect($setting->getLabel())->toBe('custom_key');
});

it('gets description with translation', function () {
    config([
        'settings.fields.general.fields.site_name.description' => [
            'en' => 'The name of the site',
            'ar' => 'اسم الموقع',
        ]
    ]);

    $setting = Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'is_public' => true,
        'value' => 'Test',
    ]);

    app()->setLocale('en');
    expect($setting->getDescription())->toBe('The name of the site');

    app()->setLocale('ar');
    expect($setting->getDescription())->toBe('اسم الموقع');
});

it('gets rules from config', function () {
    $setting = Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'is_public' => true,
        'value' => 'Test',
    ]);

    expect($setting->getRules())->toBe(['required', 'string', 'max:255']);
});

it('gets default value from config', function () {
    $setting = Setting::create([
        'key' => 'posts_per_page',
        'group' => 'general',
        'is_public' => true,
        'value' => null,
    ]);

    expect($setting->getDefaultValue())->toBe(10);
});

it('checks if setting is translatable', function () {
    $setting = Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'is_public' => true,
        'value' => 'Test',
    ]);

    expect($setting->isTranslatable())->toBeTrue();
});

it('checks if setting is system', function () {
    $systemSetting = Setting::create([
        'key' => 'maintenance_mode',
        'group' => 'system',
        'is_public' => false,
        'value' => false,
    ]);

    expect($systemSetting->isSystem())->toBeTrue();
});

it('checks if setting is sensitive', function () {
    config([
        'settings.fields.general.fields.api_key' => [
            'label' => 'API Key',
            'type' => 'text',
            'is_sensitive' => true,
        ]
    ]);

    $setting = Setting::create([
        'key' => 'api_key',
        'group' => 'general',
        'is_public' => false,
        'value' => 'secret',
    ]);

    expect($setting->isSensitive())->toBeTrue();
});

it('scopes to group', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'is_public' => true, 'value' => 'General']);
    Setting::create(['key' => 'seo_title', 'group' => 'seo', 'is_public' => true, 'value' => 'SEO']);

    $generalSettings = Setting::group('general')->get();

    expect($generalSettings)->toHaveCount(1)
        ->and($generalSettings->first()->key)->toBe('site_name');
});

it('scopes to public settings', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'is_public' => true, 'value' => 'Public']);
    Setting::create(['key' => 'maintenance_mode', 'group' => 'system', 'is_public' => false, 'value' => false]);

    $publicSettings = Setting::public()->get();

    expect($publicSettings->pluck('key'))->toContain('site_name')
        ->and($publicSettings->pluck('key'))->not->toContain('maintenance_mode');
});

it('has unique key constraint', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'is_public' => true, 'value' => 'First']);

    expect(fn() => Setting::create(['key' => 'site_name', 'group' => 'general', 'is_public' => true, 'value' => 'Second']))
        ->toThrow(\Illuminate\Database\QueryException::class);
});

it('uses soft deletes', function () {
    $setting = Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'is_public' => true,
        'value' => 'To Delete',
    ]);

    $setting->delete();

    expect(Setting::withTrashed()->where('key', 'site_name')->exists())->toBeTrue()
        ->and(Setting::where('key', 'site_name')->exists())->toBeFalse();
})->skip(!method_exists(\Salehye\Settings\Models\Setting::class, 'withTrashed'), 'Soft deletes not available');
