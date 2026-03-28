<?php

namespace Salehye\Settings;

use Salehye\Settings\Contracts\SettingsRepositoryInterface;
use Salehye\Settings\Services\SettingsService;

class SettingsManager
{
    /**
     * The settings service instance.
     */
    protected SettingsService $service;

    /**
     * The settings repository instance.
     */
    protected SettingsRepositoryInterface $repository;

    /**
     * Create a new settings manager instance.
     */
    public function __construct()
    {
        $this->repository = app(SettingsRepositoryInterface::class);
        $this->service = new SettingsService($this->repository);
    }

    /**
     * Get a setting value by key.
     * Supports dot notation for nested values.
     *
     * @param string $key The setting key
     * @param mixed $default The default value if not found
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->service->get($key, $default);
    }

    /**
     * Set a setting value.
     *
     * @param string $key The setting key
     * @param mixed $value The value to set
     */
    public function set(string $key, mixed $value): bool
    {
        return $this->service->set($key, $value);
    }

    /**
     * Get multiple settings by keys.
     *
     * @param array<int, string> $keys
     * @return array<string, mixed>
     */
    public function getMany(array $keys): array
    {
        return $this->service->getMany($keys);
    }

    /**
     * Set multiple settings.
     *
     * @param array<string, mixed> $settings
     * @return array<string, bool>
     */
    public function setMany(array $settings): array
    {
        return $this->service->setMany($settings);
    }

    /**
     * Get all settings from a group.
     *
     * @param string $group The group name
     * @return array<string, mixed>
     */
    public function group(string $group): array
    {
        return $this->service->getByGroup($group);
    }

    /**
     * Get all public settings.
     *
     * @return array<string, mixed>
     */
    public function public(): array
    {
        return $this->service->getPublic();
    }

    /**
     * Get all settings.
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->service->getAll();
    }

    /**
     * Check if a setting exists.
     *
     * @param string $key The setting key
     */
    public function has(string $key): bool
    {
        return $this->service->has($key);
    }

    /**
     * Delete a setting.
     *
     * @param string $key The setting key
     */
    public function delete(string $key): bool
    {
        return $this->service->delete($key);
    }

    /**
     * Get the setting type.
     *
     * @param string $key The setting key
     */
    public function type(string $key): ?string
    {
        return $this->service->getType($key);
    }

    /**
     * Get the setting label.
     *
     * @param string $key The setting key
     * @param string|null $locale The locale
     */
    public function label(string $key, ?string $locale = null): string
    {
        return $this->service->getLabel($key, $locale);
    }

    /**
     * Clear the cache.
     */
    public function clearCache(): void
    {
        $this->service->clearCache();
    }

    /**
     * Reload settings from database.
     */
    public function reload(): void
    {
        $this->service->reload();
    }

    /**
     * Get the underlying service instance.
     */
    public function service(): SettingsService
    {
        return $this->service;
    }

    /**
     * Get the underlying repository instance.
     */
    public function repository(): SettingsRepositoryInterface
    {
        return $this->repository;
    }

    /**
     * Magic method for getting settings directly.
     * Usage: Settings::$siteName
     */
    public function __get(string $key): mixed
    {
        return $this->get($key);
    }

    /**
     * Magic method for checking settings directly.
     * Usage: isset(Settings::$siteName)
     */
    public function __isset(string $key): bool
    {
        return $this->has($key);
    }

    /**
     * Invoke as function for quick access.
     * Usage: Settings()('site_name') or Settings()('site_name', 'default')
     */
    public function __invoke(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->all();
        }

        return $this->get($key, $default);
    }
}
