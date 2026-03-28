<?php

namespace Salehye\Settings\Concerns;

use Salehye\Settings\Facades\Settings;

trait HasSettings
{
    /**
     * Get a setting value.
     */
    protected function getSetting(string $key, mixed $default = null): mixed
    {
        return Settings::get($key, $default);
    }

    /**
     * Set a setting value.
     */
    protected function setSetting(string $key, mixed $value): bool
    {
        return Settings::set($key, $value);
    }

    /**
     * Get multiple settings.
     *
     * @param array<int, string> $keys
     * @return array<string, mixed>
     */
    protected function getSettings(array $keys): array
    {
        return Settings::getMany($keys);
    }

    /**
     * Set multiple settings.
     *
     * @param array<string, mixed> $settings
     * @return array<string, bool>
     */
    protected function setSettings(array $settings): array
    {
        return Settings::setMany($settings);
    }

    /**
     * Get all settings from a group.
     *
     * @return array<string, mixed>
     */
    protected function getSettingsGroup(string $group): array
    {
        return Settings::group($group);
    }

    /**
     * Get all public settings.
     *
     * @return array<string, mixed>
     */
    protected function getPublicSettings(): array
    {
        return Settings::public();
    }

    /**
     * Get all settings.
     *
     * @return array<string, mixed>
     */
    protected function getAllSettings(): array
    {
        return Settings::all();
    }

    /**
     * Check if a setting exists.
     */
    protected function hasSetting(string $key): bool
    {
        return Settings::has($key);
    }

    /**
     * Delete a setting.
     */
    protected function deleteSetting(string $key): bool
    {
        return Settings::delete($key);
    }

    /**
     * Get setting label.
     */
    protected function getSettingLabel(string $key, ?string $locale = null): string
    {
        return Settings::label($key, $locale);
    }

    /**
     * Clear settings cache.
     */
    protected function clearSettingsCache(): void
    {
        Settings::clearCache();
    }

    /**
     * Reload settings from database.
     */
    protected function reloadSettings(): void
    {
        Settings::reload();
    }
}
