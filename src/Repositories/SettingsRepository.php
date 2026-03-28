<?php

namespace Salehye\Settings\Repositories;

use Illuminate\Support\Facades\Cache;
use Salehye\Settings\Contracts\SettingsRepositoryInterface;
use Salehye\Settings\Models\Setting;

class SettingsRepository implements SettingsRepositoryInterface
{
    /**
     * The cache key for settings.
     */
    protected string $cacheKey;

    /**
     * The cache TTL in seconds.
     */
    protected int $cacheTtl;

    /**
     * Whether caching is enabled.
     */
    protected bool $cacheEnabled;

    /**
     * In-memory cache for settings.
     *
     * @var array<string, mixed>|null
     */
    protected ?array $settingsCache = null;

    /**
     * Create a new settings repository instance.
     */
    public function __construct()
    {
        $this->cacheEnabled = config('settings.cache.enabled', true);
        $this->cacheTtl = config('settings.cache.ttl', 3600);
        $this->cacheKey = config('settings.cache.key', 'settings_cache');
    }

    /**
     * Get a setting value by key.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $settings = $this->getAll();

        if (array_key_exists($key, $settings)) {
            return $settings[$key];
        }

        // Fall back to config default
        return config("settings.definitions.{$key}.default") ?? $default;
    }

    /**
     * Set a setting value.
     */
    public function set(string $key, mixed $value): bool
    {
        $setting = Setting::firstOrNew(['key' => $key]);

        // Get metadata from definition or use defaults
        $definition = config("settings.definitions.{$key}");
        $setting->group = $definition['group'] ?? 'general';

        // Only set is_public from config if not already set in database
        if ($setting->is_public === null && isset($definition['is_public'])) {
            $setting->is_public = $definition['is_public'];
        }

        $setting->setTypedValue($value);
        $saved = $setting->save();

        if ($saved) {
            $this->clearCache();
        }

        return $saved;
    }

    /**
     * Get multiple settings by keys.
     *
     * @param array<int, string> $keys
     * @return array<string, mixed>
     */
    public function getMany(array $keys): array
    {
        $settings = $this->getAll();

        return collect($keys)
            ->mapWithKeys(fn(string $key) => [
                $key => $settings[$key] ?? config("settings.definitions.{$key}.default"),
            ])
            ->toArray();
    }

    /**
     * Set multiple settings.
     *
     * @param array<string, mixed> $settings
     */
    public function setMany(array $settings): bool
    {
        $saved = true;

        foreach ($settings as $key => $value) {
            if (!$this->set($key, $value)) {
                $saved = false;
            }
        }

        return $saved;
    }

    /**
     * Get all settings from a group.
     *
     * @return array<string, mixed>
     */
    public function getByGroup(string $group): array
    {
        $allSettings = $this->getAll();

        // Get keys for this group from database
        $groupKeys = Setting::where('group', $group)->pluck('key')->toArray();

        return collect($allSettings)
            ->only($groupKeys)
            ->toArray();
    }

    /**
     * Get all public settings.
     *
     * @return array<string, mixed>
     */
    public function getPublic(): array
    {
        // Use database is_public column directly
        return Setting::public()->get()->pluck('value', 'key')->toArray();
    }

    /**
     * Get all settings.
     *
     * @return array<string, mixed>
     */
    public function getAll(): array
    {
        if ($this->settingsCache !== null) {
            return $this->settingsCache;
        }

        if ($this->cacheEnabled) {
            $cached = Cache::remember(
                $this->cacheKey,
                $this->cacheTtl,
                fn() => $this->loadFromDatabase()
            );

            return $cached;
        }

        return $this->loadFromDatabase();
    }

    /**
     * Check if a setting exists.
     */
    public function has(string $key): bool
    {
        $settings = $this->getAll();
        return array_key_exists($key, $settings) || Setting::where('key', $key)->exists();
    }

    /**
     * Delete a setting.
     */
    public function delete(string $key): bool
    {
        $deleted = Setting::where('key', $key)->delete();

        if ($deleted) {
            $this->clearCache();
        }

        return $deleted;
    }

    /**
     * Clear the cache.
     */
    public function clearCache(): void
    {
        $this->settingsCache = null;

        if ($this->cacheEnabled) {
            Cache::forget($this->cacheKey);
        }
    }

    /**
     * Reload settings from database.
     */
    public function reload(): void
    {
        $this->clearCache();
        $this->getAll();
    }

    /**
     * Load settings from database.
     *
     * @return array<string, mixed>
     */
    protected function loadFromDatabase(): array
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $this->settingsCache = $settings;

        return $settings;
    }
}
