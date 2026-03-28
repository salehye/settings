<?php

use Salehye\Settings\Facades\Settings;

if (!function_exists('settings')) {
    /**
     * Get or set settings.
     *
     * @param string|null $key The setting key
     * @param mixed $default The default value
     * @return mixed|\Salehye\Settings\SettingsManager
     */
    function settings(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return app(Settings::class);
        }

        return Settings::get($key, $default);
    }
}

if (!function_exists('setting')) {
    /**
     * Get a setting value.
     * Alias for settings() helper.
     *
     * @param string $key The setting key
     * @param mixed $default The default value
     * @return mixed
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return Settings::get($key, $default);
    }
}

if (!function_exists('set_setting')) {
    /**
     * Set a setting value.
     *
     * @param string $key The setting key
     * @param mixed $value The value to set
     * @return bool
     */
    function set_setting(string $key, mixed $value): bool
    {
        return Settings::set($key, $value);
    }
}

if (!function_exists('settings_group')) {
    /**
     * Get all settings from a group.
     *
     * @param string $group The group name
     * @return array<string, mixed>
     */
    function settings_group(string $group): array
    {
        return Settings::group($group);
    }
}

if (!function_exists('settings_public')) {
    /**
     * Get all public settings.
     *
     * @return array<string, mixed>
     */
    function settings_public(): array
    {
        return Settings::public();
    }
}

if (!function_exists('settings_all')) {
    /**
     * Get all settings.
     *
     * @return array<string, mixed>
     */
    function settings_all(): array
    {
        return Settings::all();
    }
}
