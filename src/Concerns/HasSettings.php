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
     * Get setting type.
     */
    protected function getSettingType(string $key): ?string
    {
        return Settings::type($key);
    }

    /**
     * Get setting default value from config.
     */
    protected function getSettingDefault(string $key): mixed
    {
        return setting_default($key);
    }

    /**
     * Get setting validation rules.
     *
     * @return array<int, string>
     */
    protected function getSettingRules(string $key): array
    {
        return setting_rules($key);
    }

    /**
     * Get setting description.
     */
    protected function getSettingDescription(string $key, ?string $locale = null): string
    {
        return setting_description($key, $locale);
    }

    /**
     * Check if a setting is translatable.
     */
    protected function settingIsTranslatable(string $key): bool
    {
        return setting_is_translatable($key);
    }

    /**
     * Check if a setting is a system setting.
     */
    protected function settingIsSystem(string $key): bool
    {
        return setting_is_system($key);
    }

    /**
     * Check if a setting is sensitive.
     */
    protected function settingIsSensitive(string $key): bool
    {
        return setting_is_sensitive($key);
    }

    /**
     * Get settings group label.
     */
    protected function getSettingsGroupLabel(string $group, ?string $locale = null): string
    {
        return settings_group_label($group, $locale);
    }

    /**
     * Get settings group icon.
     */
    protected function getSettingsGroupIcon(string $group): ?string
    {
        return settings_group_icon($group);
    }

    /**
     * Get all fields for a settings group.
     *
     * @return array<string, mixed>
     */
    protected function getSettingsGroupFields(string $group): array
    {
        return settings_group_fields($group);
    }

    /**
     * Get field definition for a setting.
     *
     * @return array<string, mixed>|null
     */
    protected function getSettingField(string $key): ?array
    {
        return setting_field($key);
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
