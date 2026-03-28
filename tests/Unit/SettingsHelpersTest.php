<?php

use Salehye\Settings\Facades\Settings;
use Salehye\Settings\Models\Setting;

it('has settings helper function', function () {
    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'is_public' => true,
        'value' => 'Test Site',
    ]);

    expect(settings('site_name'))->toBe('Test Site');
});

it('has setting helper function', function () {
    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'is_public' => true,
        'value' => 'Test Site',
    ]);

    expect(setting('site_name'))->toBe('Test Site');
});

it('has set_setting helper function', function () {
    set_setting('site_name', 'New Site');

    expect(Setting::where('key', 'site_name')->first()->value)
        ->toBe('New Site');
});

it('has settings_group helper function', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'is_public' => true, 'value' => 'Site']);
    Setting::create(['key' => 'timezone', 'group' => 'general', 'is_public' => false, 'value' => 'UTC']);

    $group = settings_group('general');

    expect($group)
        ->toHaveKey('site_name')
        ->toHaveKey('timezone');
});

it('has settings_public helper function', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'is_public' => true, 'value' => 'Public']);
    Setting::create(['key' => 'maintenance_mode', 'group' => 'system', 'is_public' => false, 'value' => false]);

    $public = settings_public();

    expect($public)
        ->toHaveKey('site_name')
        ->not->toHaveKey('maintenance_mode');
});

it('has settings_all helper function', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'is_public' => true, 'value' => 'Site']);

    $all = settings_all();

    expect($all)->toHaveKey('site_name');
});

it('returns SettingsManager when no key provided', function () {
    expect(settings())->toBeInstanceOf(Settings::class);
});

it('can use Settings facade as function', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'is_public' => true, 'value' => 'Functional']);

    expect(Settings('site_name'))->toBe('Functional');
});

it('returns all settings when invoked without arguments', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'is_public' => true, 'value' => 'Site']);

    expect(Settings())->toHaveKey('site_name');
});

it('can access settings via magic getter', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'is_public' => true, 'value' => 'Magic Site']);

    expect(Settings::$siteName)->toBe('Magic Site');
});

it('can check settings via magic isset', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'is_public' => true, 'value' => 'Site']);

    expect(isset(Settings::$site_name))->toBeTrue()
        ->and(isset(Settings::$nonexistent))->toBeFalse();
});
