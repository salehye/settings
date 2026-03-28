<?php

use Salehye\Settings\Repositories\SettingsRepository;
use Salehye\Settings\Models\Setting;

it('can get a setting from repository', function () {
    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'value' => 'Repository Site',
    ]);

    $repository = new SettingsRepository();
    config(['settings.cache.enabled' => false]);

    expect($repository->get('site_name'))->toBe('Repository Site');
});

it('returns default when setting not found in repository', function () {
    $repository = new SettingsRepository();
    config(['settings.cache.enabled' => false]);

    expect($repository->get('nonexistent', 'default'))->toBe('default');
});

it('can set a setting in repository', function () {
    $repository = new SettingsRepository();
    config(['settings.cache.enabled' => false]);

    $repository->set('site_name', 'New Site');

    expect(Setting::where('key', 'site_name')->first()->value)
        ->toBe('New Site');
});

it('clears cache when setting is updated', function () {
    $repository = new SettingsRepository();
    config(['settings.cache.enabled' => true]);

    // First call caches the value
    $repository->getAll();

    // Update should clear cache
    $repository->set('site_name', 'Cached Update');

    expect($repository->get('site_name'))->toBe('Cached Update');
});

it('can get many settings', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'value' => 'Site']);
    Setting::create(['key' => 'timezone', 'group' => 'general', 'value' => 'UTC']);

    $repository = new SettingsRepository();
    config(['settings.cache.enabled' => false]);

    $settings = $repository->getMany(['site_name', 'timezone']);

    expect($settings)
        ->toHaveKey('site_name', 'Site')
        ->toHaveKey('timezone', 'UTC');
});

it('can get settings by group', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'value' => 'General']);
    Setting::create(['key' => 'seo_title', 'group' => 'seo', 'value' => 'SEO']);

    $repository = new SettingsRepository();
    config(['settings.cache.enabled' => false]);

    $settings = $repository->getByGroup('general');

    expect($settings)
        ->toHaveKey('site_name')
        ->not->toHaveKey('seo_title');
});

it('can get public settings', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'value' => 'Public']);
    Setting::create(['key' => 'maintenance_mode', 'group' => 'system', 'value' => false]);

    $repository = new SettingsRepository();
    config(['settings.cache.enabled' => false]);

    $settings = $repository->getPublic();

    expect($settings)
        ->toHaveKey('site_name')
        ->not->toHaveKey('maintenance_mode');
});

it('can check if setting exists', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'value' => 'Exists']);

    $repository = new SettingsRepository();
    config(['settings.cache.enabled' => false]);

    expect($repository->has('site_name'))->toBeTrue()
        ->and($repository->has('nonexistent'))->toBeFalse();
});

it('can delete a setting', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'value' => 'Delete Me']);

    $repository = new SettingsRepository();
    config(['settings.cache.enabled' => false]);

    $repository->delete('site_name');

    expect(Setting::where('key', 'site_name')->exists())->toBeFalse();
});

it('can clear cache', function () {
    $repository = new SettingsRepository();
    config(['settings.cache.enabled' => true]);

    $repository->getAll(); // Populate cache
    $repository->clearCache();

    expect($repository->getAll())->toBeArray();
});

it('can reload settings', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'value' => 'Reloaded']);

    $repository = new SettingsRepository();
    config(['settings.cache.enabled' => false]);

    $repository->reload();

    expect($repository->get('site_name'))->toBe('Reloaded');
});
