<?php

namespace Salehye\Settings\Contracts;

interface SettingsRepositoryInterface
{
    /**
     * Get a setting value by key.
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Set a setting value.
     */
    public function set(string $key, mixed $value): bool;

    /**
     * Get multiple settings by keys.
     *
     * @param array<int, string> $keys
     * @return array<string, mixed>
     */
    public function getMany(array $keys): array;

    /**
     * Set multiple settings.
     *
     * @param array<string, mixed> $settings
     */
    public function setMany(array $settings): bool;

    /**
     * Get all settings from a group.
     *
     * @return array<string, mixed>
     */
    public function getByGroup(string $group): array;

    /**
     * Get all public settings.
     *
     * @return array<string, mixed>
     */
    public function getPublic(): array;

    /**
     * Get all settings.
     *
     * @return array<string, mixed>
     */
    public function getAll(): array;

    /**
     * Check if a setting exists.
     */
    public function has(string $key): bool;

    /**
     * Delete a setting.
     */
    public function delete(string $key): bool;

    /**
     * Clear the cache.
     */
    public function clearCache(): void;

    /**
     * Reload settings from database.
     */
    public function reload(): void;
}
