<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Salehye\Settings\Facades\Settings;
use Salehye\Settings\Models\Setting;

uses(RefreshDatabase::class);

it('can access settings index page', function () {
    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'value' => 'Test Site',
    ]);

    $response = $this->get('/settings');

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Settings/Index')
        ->has('settings.general')
    );
});

it('can access settings group page', function () {
    Setting::create([
        'key' => 'site_name',
        'group' => 'general',
        'value' => 'General Site',
    ]);

    $response = $this->get('/settings/general');

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Settings/Group')
        ->where('group', 'general')
        ->has('settings')
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

    $response->assertInertia(fn (Assert $page) => $page
        ->has('settings.general')
        ->has('settings.seo')
    );
});
