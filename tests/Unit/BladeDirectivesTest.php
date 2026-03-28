<?php

use Illuminate\Support\Facades\Blade;
use Salehye\Settings\Models\Setting;

it('can use settings helper in blade', function () {
    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'is_public' => true,
        'value' => 'Blade Site',
    ]);

    $view = Blade::render('@settings("site_name")');

    expect(trim($view))->toBe('Blade Site');
});

it('can use setting directive in blade', function () {
    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'is_public' => true,
        'value' => 'Blade Site',
    ]);

    $view = Blade::render('@setting("site_name")');

    expect(trim($view))->toBe('Blade Site');
});

it('can use ifSettings directive in blade', function () {
    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'is_public' => true,
        'value' => 'Exists',
    ]);

    $view = Blade::render('@ifSettings("site_name")YES@endIfSettings');

    expect(trim($view))->toBe('YES');
});

it('ifSettings returns empty for non-existent setting', function () {
    $view = Blade::render('@ifSettings("nonexistent")YES@endIfSettings');

    expect(trim($view))->toBe('');
});

it('can use jsonSettings directive in blade', function () {
    Setting::create([
        'key' => 'seo_keywords',
        'group' => 'seo',
        'is_public' => true,
        'value' => ['keyword1', 'keyword2'],
    ]);

    $view = Blade::render('@jsonSettings("seo_keywords")');

    expect(json_decode(trim($view), true))->toBe(['keyword1', 'keyword2']);
});

it('shares settings with all views via view composer', function () {
    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'is_public' => true,
        'value' => 'Shared Site',
    ]);

    $view = view('settings::components.setting', ['value' => setting('site_name')]);

    expect(trim($view->render()))->toBe('Shared Site');
});

it('can use settingsGroup directive', function () {
    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'is_public' => true,
        'value' => 'General Site',
    ]);

    $view = Blade::render('@settingsGroup("general")');

    $decoded = json_decode(trim($view), true);
    expect($decoded)->toBeArray()
        ->toHaveKey('site_name', 'General Site');
});
