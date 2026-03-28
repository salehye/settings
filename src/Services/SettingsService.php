<?php

namespace Salehye\Settings\Services;

use Illuminate\Support\Facades\Validator;
use Salehye\Settings\Contracts\SettingsRepositoryInterface;

class SettingsService
{
    /**
     * Create a new settings service instance.
     */
    public function __construct(
        protected SettingsRepositoryInterface $repository
    ) {
    }

    /**
     * Get a setting value by key.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->repository->get($key, $default);
    }

    /**
     * Set a setting value.
     * Optionally validates if rules are defined in config.
     */
    public function set(string $key, mixed $value, bool $validate = false): bool
    {
        if ($validate) {
            $this->validate($key, $value);
        }

        return $this->repository->set($key, $value);
    }

    /**
     * Get multiple settings by keys.
     *
     * @param array<int, string> $keys
     * @return array<string, mixed>
     */
    public function getMany(array $keys): array
    {
        return $this->repository->getMany($keys);
    }

    /**
     * Set multiple settings.
     * Optionally validates if rules are defined in config.
     *
     * @param array<string, mixed> $settings
     * @param bool $validate
     * @return array<string, bool>
     */
    public function setMany(array $settings, bool $validate = false): array
    {
        $results = [];

        foreach ($settings as $key => $value) {
            try {
                $results[$key] = $this->set($key, $value, $validate);
            } catch (\Illuminate\Validation\ValidationException) {
                $results[$key] = false;
            }
        }

        return $results;
    }

    /**
     * Get all settings from a group.
     *
     * @return array<string, mixed>
     */
    public function getByGroup(string $group): array
    {
        return $this->repository->getByGroup($group);
    }

    /**
     * Get all public settings for frontend sharing.
     *
     * @return array<string, mixed>
     */
    public function getPublic(): array
    {
        return $this->repository->getPublic();
    }

    /**
     * Get all settings.
     *
     * @return array<string, mixed>
     */
    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    /**
     * Check if a setting exists.
     */
    public function has(string $key): bool
    {
        return $this->repository->has($key);
    }

    /**
     * Delete a setting.
     */
    public function delete(string $key): bool
    {
        return $this->repository->delete($key);
    }

    /**
     * Validate a setting value against its rules.
     * Only validates if rules are defined in config.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate(string $key, mixed $value): void
    {
        $definition = config("settings.definitions.{$key}");

        if ($definition === null || !isset($definition['rules'])) {
            return;
        }

        $validator = Validator::make(
            [$key => $value],
            [$key => $definition['rules']],
            [],
            [$key => $definition['translations']['en'] ?? $key]
        );

        $validator->validate();
    }

    /**
     * Get the type of a setting.
     */
    public function getType(string $key): ?string
    {
        $definition = config("settings.definitions.{$key}");
        return $definition['type'] ?? null;
    }

    /**
     * Get the group of a setting.
     */
    public function getGroup(string $key): ?string
    {
        $definition = config("settings.definitions.{$key}");
        return $definition['group'] ?? null;
    }

    /**
     * Get the label of a setting.
     */
    public function getLabel(string $key, ?string $locale = null): string
    {
        $definition = config("settings.definitions.{$key}");

        if ($definition === null || !isset($definition['translations'])) {
            return $key;
        }

        $locale ??= app()->getLocale();

        return $definition['translations'][$locale]
            ?? $definition['translations']['en']
            ?? $key;
    }

    /**
     * Clear the cache.
     */
    public function clearCache(): void
    {
        $this->repository->clearCache();
    }

    /**
     * Reload settings from database.
     */
    public function reload(): void
    {
        $this->repository->reload();
    }
}
