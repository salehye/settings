<?php

use Salehye\Settings\Models\Setting;

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

    expect($setting->is_public)->toBeBoolean()
        ->and($setting->is_public)->toBeTrue();
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

    // Without definition, returns as-is
    expect($setting->getTypedValue())->toBe('10');
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
        ->toHaveKeys(['type', 'group', 'is_public', 'default']);
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
        ->toThrow();
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
});
