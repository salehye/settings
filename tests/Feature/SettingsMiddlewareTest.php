<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Inertia;
use Salehye\Settings\Http\Middleware\ShareSettingsMiddleware;
use Salehye\Settings\Models\Setting;

uses(RefreshDatabase::class);

it('shares public settings with Inertia', function () {
    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'value' => 'Shared Site',
    ]);

    $response = $this->get('/');

    $response->assertInertia(fn ($page) => $page
        ->has('settings.site_name', 'Shared Site')
    );
});

it('does not share private settings with Inertia', function () {
    Setting::create([
        'key' => 'maintenance_mode',
        'group' => 'system',
        'value' => true,
    ]);

    $response = $this->get('/');

    $response->assertInertia(fn ($page) => $page
        ->missing('settings.maintenance_mode')
    );
});

it('uses correct share key from config', function () {
    config(['settings.inertia.key_name' => 'site_settings']);

    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'value' => 'Custom Key Site',
    ]);

    $response = $this->get('/');

    $response->assertInertia(fn ($page) => $page
        ->has('site_settings.site_name', 'Custom Key Site')
    );
});

it('can disable sharing via config', function () {
    config(['settings.inertia.share_public' => false]);

    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'value' => 'No Share Site',
    ]);

    $response = $this->get('/');

    $response->assertInertia(fn ($page) => $page
        ->missing('settings')
    );
});

it('middleware shares all public settings', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'value' => 'Site']);
    Setting::create(['key' => 'contact_email', 'group' => 'contact', 'value' => 'test@example.com']);
    Setting::create(['key' => 'social_twitter', 'group' => 'social', 'value' => 'https://twitter.com']);

    $response = $this->get('/');

    $response->assertInertia(fn ($page) => $page
        ->has('settings.site_name')
        ->has('settings.contact_email')
        ->has('settings.social_twitter')
    );
});
