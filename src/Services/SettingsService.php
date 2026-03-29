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
     * Get all groups with their metadata.
     *
     * @return array<string, mixed>
     */
    public function getGroups(): array
    {
        return config('settings.fields', []);
    }

    /**
     * Get a specific group's metadata.
     *
     * @return array<string, mixed>|null
     */
    public function getGroupMeta(string $group): ?array
    {
        return config("settings.fields.{$group}");
    }

    /**
     * Get field definition.
     *
     * @return array<string, mixed>|null
     */
    public function getField(string $group, string $key): ?array
    {
        return config("settings.fields.{$group}.fields.{$key}");
    }

    /**
     * Get the label for a field.
     */
    public function getLabel(string $group, string $key, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $field = $this->getField($group, $key);

        if ($field === null || !isset($field['label'])) {
            return $key;
        }

        $label = $field['label'];

        if (is_array($label)) {
            return $label[$locale] ?? $label['en'] ?? $label['ar'] ?? $key;
        }

        return (string) $label;
    }

    /**
     * Get all labels for a group.
     *
     * @return array<string, string>
     */
    public function getGroupLabels(string $group, ?string $locale = null): array
    {
        $locale ??= app()->getLocale();
        $groupMeta = $this->getGroupMeta($group);

        if ($groupMeta === null) {
            return [];
        }

        $labels = [];
        foreach ($groupMeta['fields'] ?? [] as $key => $field) {
            $label = $field['label'] ?? $key;

            if (is_array($label)) {
                $labels[$key] = $label[$locale] ?? $label['en'] ?? $label['ar'] ?? $key;
            } else {
                $labels[$key] = (string) $label;
            }
        }

        return $labels;
    }

    /**
     * Validate a setting value against its rules.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate(string $key, mixed $value, ?string $group = null): void
    {
        if ($group === null) {
            foreach (config('settings.fields', []) as $groupName => $groupData) {
                if (isset($groupData['fields'][$key])) {
                    $group = $groupName;
                    break;
                }
            }
        }

        if ($group === null) {
            return;
        }

        $field = $this->getField($group, $key);

        if ($field === null || !isset($field['rules'])) {
            return;
        }

        $label = $this->getLabel($group, $key);

        $validator = Validator::make(
            [$key => $value],
            [$key => $field['rules']],
            [],
            [$key => $label]
        );

        $validator->validate();
    }

    /**
     * Get the type of a setting.
     */
    public function getType(string $group, string $key): ?string
    {
        $field = $this->getField($group, $key);
        return $field['type'] ?? null;
    }

    /**
     * Get the validation rules for a setting.
     *
     * @return array<int, string>
     */
    public function getRules(string $group, string $key): array
    {
        $field = $this->getField($group, $key);
        return $field['rules'] ?? [];
    }

    /**
     * Check if a field is translatable.
     */
    public function isTranslatable(string $group, string $key): bool
    {
        $field = $this->getField($group, $key);
        return $field['is_translatable'] ?? false;
    }

    /**
     * Check if a field is sensitive.
     */
    public function isSensitive(string $group, string $key): bool
    {
        $field = $this->getField($group, $key);
        return $field['is_sensitive'] ?? false;
    }

    /**
     * Get the default value for a setting.
     */
    public function getDefaultValue(string $group, string $key): mixed
    {
        $field = $this->getField($group, $key);
        return $field['default'] ?? null;
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

    /**
     * Get form data for a group (for UI rendering).
     *
     * @return array<string, mixed>
     */
    public function getFormData(string $group, ?string $locale = null): array
    {
        $locale ??= app()->getLocale();
        $groupMeta = $this->getGroupMeta($group);

        if ($groupMeta === null) {
            return [];
        }

        $settings = $this->getByGroup($group);

        return [
            'label' => is_array($groupMeta['label'] ?? '')
                ? ($groupMeta['label'][$locale] ?? $groupMeta['label']['en'] ?? $group)
                : $groupMeta['label'],
            'icon' => $groupMeta['icon'] ?? null,
            'order' => $groupMeta['order'] ?? 999,
            'fields' => collect($groupMeta['fields'] ?? [])
                ->map(fn(array $field, string $key) => [
                    'key' => $key,
                    'type' => $field['type'] ?? 'text',
                    'label' => is_array($field['label'] ?? '')
                        ? ($field['label'][$locale] ?? $field['label']['en'] ?? $field['label']['ar'] ?? $key)
                        : $field['label'],
                    'description' => isset($field['description'])
                        ? (is_array($field['description'])
                            ? ($field['description'][$locale] ?? $field['description']['en'] ?? '')
                            : $field['description'])
                        : null,
                    'placeholder' => isset($field['placeholder'])
                        ? (is_array($field['placeholder'])
                            ? ($field['placeholder'][$locale] ?? $field['placeholder']['en'] ?? '')
                            : $field['placeholder'])
                        : null,
                    'rules' => $field['rules'] ?? [],
                    'value' => $settings[$key] ?? $field['default'] ?? null,
                    'is_translatable' => $field['is_translatable'] ?? false,
                    'is_sensitive' => $field['is_sensitive'] ?? false,
                    'is_system' => $field['is_system'] ?? false,
                    'options' => $this->processOptions($field['options'] ?? null, $locale),
                    'ui' => collect($field)->only([
                        'icon',
                        'rows',
                        'cols',
                        'min',
                        'max',
                        'step',
                        'accepted',
                        'dimensions',
                        'max_size',
                        'counter',
                        'pattern',
                        'searchable',
                        'monospace',
                        'warning',
                        'requires_restart',
                        'repeater_fields'
                    ])->toArray(),
                ])
                ->sortBy('order')
                ->toArray(),
        ];
    }

    /**
     * Process options for select fields (multilingual support).
     *
     * @param array<string, mixed>|null $options
     */
    protected function processOptions(?array $options, ?string $locale = null): array
    {
        if ($options === null) {
            return [];
        }

        $locale ??= app()->getLocale();

        return collect($options)
            ->map(
                fn($option) => is_array($option)
                ? ($option[$locale] ?? $option['en'] ?? $option['ar'] ?? reset($option))
                : $option
            )
            ->toArray();
    }
}
