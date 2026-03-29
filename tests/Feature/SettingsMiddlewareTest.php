<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Inertia;
use Salehye\Settings\Http\Middleware\ShareSettingsMiddleware;
use Salehye\Settings\Models\Setting;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Set up test config for settings fields
    config([
        'settings.fields' => [
            'general' => [
                'label' => ['en' => 'General Settings'],
                'fields' => [
                    'site_name' => [
                        'label' => ['en' => 'Site Name'],
                        'type' => 'text',
                        'is_public' => true,
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
                        'is_public' => false,
                        'is_system' => true,
                    ],
                ],
            ],
            'contact' => [
                'label' => ['en' => 'Contact Information'],
                'fields' => [
                    'contact_email' => [
                        'label' => ['en' => 'Contact Email'],
                        'type' => 'email',
                        'is_public' => true,
                    ],
                ],
            ],
            'social' => [
                'label' => ['en' => 'Social Media'],
                'fields' => [
                    'social_twitter' => [
                        'label' => ['en' => 'Twitter'],
                        'type' => 'url',
                        'is_public' => true,
                    ],
                ],
            ],
        ]
    ]);
});

it('shares public settings with Inertia', function () {
    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'value' => 'Shared Site',
        'is_public' => true,
        'is_active' => true,
    ]);

    $response = $this->get('/');

    $response->assertInertia(
        fn($page) => $page
            ->has('settings.site_name', 'Shared Site')
    );
});

it('does not share private settings with Inertia', function () {
    Setting::create([
        'key' => 'maintenance_mode',
        'group' => 'system',
        'value' => true,
        'is_public' => false,
        'is_active' => true,
    ]);

    $response = $this->get('/');

    $response->assertInertia(
        fn($page) => $page
            ->missing('settings.maintenance_mode')
    );
});

it('uses correct share key from config', function () {
    config(['settings.inertia.key_name' => 'site_settings']);

    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'value' => 'Custom Key Site',
        'is_public' => true,
        'is_active' => true,
    ]);

    $response = $this->get('/');

    $response->assertInertia(
        fn($page) => $page
            ->has('site_settings.site_name', 'Custom Key Site')
    );
});

it('can disable sharing via config', function () {
    config(['settings.inertia.share_public' => false]);

    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'value' => 'No Share Site',
        'is_public' => true,
        'is_active' => true,
    ]);

    $response = $this->get('/');

    $response->assertInertia(
        fn($page) => $page
            ->missing('settings')
    );
});

it('middleware shares all public settings', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'value' => 'Site', 'is_public' => true, 'is_active' => true]);
    Setting::create(['key' => 'contact_email', 'group' => 'contact', 'value' => 'test@example.com', 'is_public' => true, 'is_active' => true]);
    Setting::create(['key' => 'social_twitter', 'group' => 'social', 'value' => 'https://twitter.com', 'is_public' => true, 'is_active' => true]);

    $response = $this->get('/');

    $response->assertInertia(
        fn($page) => $page
            ->has('settings.site_name')
            ->has('settings.contact_email')
            ->has('settings.social_twitter')
    );
});
