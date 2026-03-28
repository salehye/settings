<?php

use Salehye\Settings\Concerns\HasSettings;
use Salehye\Settings\Models\Setting;

it('can use HasSettings trait', function () {
    $class = new class {
        use HasSettings;
    };

    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'is_public' => true,
        'value' => 'Trait Site',
    ]);

    expect($class->getSetting('site_name'))->toBe('Trait Site');
});

it('can set settings via trait', function () {
    $class = new class {
        use HasSettings;
    };

    $class->setSetting('site_name', 'New Site');

    expect(Setting::where('key', 'site_name')->first()->value)
        ->toBe('New Site');
});

it('can get multiple settings via trait', function () {
    $class = new class {
        use HasSettings;
    };

    Setting::create(['key' => 'site_name', 'group' => 'general', 'is_public' => true, 'value' => 'Site']);
    Setting::create(['key' => 'timezone', 'group' => 'general', 'is_public' => false, 'value' => 'UTC']);

    $settings = $class->getSettings(['site_name', 'timezone']);

    expect($settings)
        ->toHaveKey('site_name', 'Site')
        ->toHaveKey('timezone', 'UTC');
});

it('can set multiple settings via trait', function () {
    $class = new class {
        use HasSettings;
    };

    $results = $class->setSettings([
        'site_name' => 'Multi Site',
        'timezone' => 'Asia/Riyadh',
    ]);

    expect($results)
        ->toHaveKey('site_name', true)
        ->toHaveKey('timezone', true);
});

it('can get settings group via trait', function () {
    $class = new class {
        use HasSettings;
    };

    Setting::create(['key' => 'site_name', 'group' => 'general', 'is_public' => true, 'value' => 'General']);
    Setting::create(['key' => 'seo_title', 'group' => 'seo', 'is_public' => true, 'value' => 'SEO']);

    $group = $class->getSettingsGroup('general');

    expect($group)
        ->toHaveKey('site_name')
        ->not->toHaveKey('seo_title');
});

it('can get public settings via trait', function () {
    $class = new class {
        use HasSettings;
    };

    Setting::create(['key' => 'site_name', 'group' => 'general', 'is_public' => true, 'value' => 'Public']);
    Setting::create(['key' => 'maintenance_mode', 'group' => 'system', 'is_public' => false, 'value' => false]);

    $public = $class->getPublicSettings();

    expect($public)
        ->toHaveKey('site_name')
        ->not->toHaveKey('maintenance_mode');
});

it('can get all settings via trait', function () {
    $class = new class {
        use HasSettings;
    };

    Setting::create(['key' => 'site_name', 'group' => 'general', 'is_public' => true, 'value' => 'All']);

    $all = $class->getAllSettings();

    expect($all)->toHaveKey('site_name');
});

it('can check if setting exists via trait', function () {
    $class = new class {
        use HasSettings;
    };

    Setting::create(['key' => 'site_name', 'group' => 'general', 'is_public' => true, 'value' => 'Exists']);

    expect($class->hasSetting('site_name'))->toBeTrue()
        ->and($class->hasSetting('nonexistent'))->toBeFalse();
});

it('can delete setting via trait', function () {
    $class = new class {
        use HasSettings;
    };

    Setting::create(['key' => 'site_name', 'group' => 'general', 'is_public' => true, 'value' => 'Delete']);

    $class->deleteSetting('site_name');

    expect(Setting::where('key', 'site_name')->exists())->toBeFalse();
});

it('can get setting label via trait', function () {
    $class = new class {
        use HasSettings;
    };

    app()->setLocale('en');
    $label = $class->getSettingLabel('site_name');

    expect($label)->toBe('Site Name');
});

it('can clear settings cache via trait', function () {
    $class = new class {
        use HasSettings;
    };

    expect($class->clearSettingsCache())->toBeNull();
});

it('can reload settings via trait', function () {
    $class = new class {
        use HasSettings;
    };

    expect($class->reloadSettings())->toBeNull();
});
