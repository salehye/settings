<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Salehye\Settings\Facades\Settings;
use Salehye\Settings\Models\Setting;

uses(RefreshDatabase::class);

beforeEach(function () {
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
                    ],
                    'timezone' => [
                        'label' => ['en' => 'Timezone'],
                        'type' => 'select',
                        'default' => 'UTC',
                    ],
                    'contact_email' => [
                        'label' => ['en' => 'Contact Email'],
                        'type' => 'email',
                        'rules' => ['nullable', 'email', 'max:255'],
                    ],
                ],
            ],
            'seo' => [
                'label' => ['en' => 'SEO Settings'],
                'order' => 2,
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
                    'maintenance_mode' => [
                        'label' => ['en' => 'Maintenance Mode'],
                        'type' => 'boolean',
                        'is_system' => true,
                        'is_public' => false,
                    ],
                ],
            ],
        ]
    ]);
});

it('can access settings index page', function () {
    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'value' => 'Test Site',
        'is_public' => true,
        'is_active' => true,
    ]);

    $response = $this->get('/settings');

    $response->assertStatus(200);
    $response->assertInertia(
        fn(Assert $page) => $page
            ->component('Settings/Index')
            ->has('settings.general')
            ->has('groups')
    );
});

it('can access settings group page', function () {
    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'value' => 'General Site',
        'is_public' => true,
        'is_active' => true,
    ]);

    $response = $this->get('/settings/general');

    $response->assertStatus(200);
    $response->assertInertia(
        fn(Assert $page) => $page
            ->component('Settings/Group')
            ->where('group', 'general')
            ->has('settings')
            ->has('groupMeta')
    );
});

it('can update settings via API', function () {
    $response = $this->putJson('/settings', [
        'settings' => [
            'site_name' => 'Updated Site',
            'timezone' => 'Asia/Riyadh',
        ],
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Settings updated successfully',
        ]);

    expect(Settings::get('site_name'))->toBe('Updated Site');
    expect(Settings::get('timezone'))->toBe('Asia/Riyadh');
});

it('returns validation error for invalid settings', function () {
    $response = $this->putJson('/settings', [
        'settings' => [
            'contact_email' => 'invalid-email',
        ],
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'Some settings failed to update',
        ]);
});

it('can get public settings via API', function () {
    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'value' => 'Public Site',
    ]);
    Setting::create([
        'key' => 'maintenance_mode',
        'group' => 'system',
        'value' => false,
        'is_public' => false,
    ]);

    $response = $this->getJson('/settings/api/public');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'settings' => [
                'site_name',
            ],
        ])
        ->assertJsonMissing([
            'settings' => [
                'maintenance_mode' => false,
            ],
        ]);
});

it('groups settings correctly by group', function () {
    Setting::create(['key' => 'site_name', 'group' => 'general', 'value' => 'Site']);
    Setting::create(['key' => 'seo_title', 'group' => 'seo', 'value' => 'SEO']);

    $response = $this->get('/settings');

    $response->assertInertia(
        fn(Assert $page) => $page
            ->has('settings.general')
            ->has('settings.seo')
    );
});

it('returns group metadata with labels', function () {
    $response = $this->get('/settings');

    $response->assertInertia(
        fn(Assert $page) => $page
            ->has(
                'groups.general',
                fn(Assert $group) => $group
                    ->where('label', 'General Settings')
                    ->where('icon', 'fas fa-cog')
                    ->where('order', 1)
            )
            ->has(
                'groups.seo',
                fn(Assert $group) => $group
                    ->where('label', 'SEO Settings')
                    ->where('order', 2)
            )
    );
});

it('returns group meta on group page', function () {
    $response = $this->get('/settings/general');

    $response->assertInertia(
        fn(Assert $page) => $page
            ->where('groupMeta.label', 'General Settings')
            ->where('groupMeta.icon', 'fas fa-cog')
    );
});
