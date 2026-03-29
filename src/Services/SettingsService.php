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
     * Get the label for a field by key.
     */
    public function getLabel(string $key, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $group = $this->findGroupForKey($key);

        if ($group === null) {
            return $key;
        }

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

        $label = $this->getLabel($key);

        $validator = Validator::make(
            [$key => $value],
            [$key => $field['rules']],
            [],
            [$key => $label]
        );

        $validator->validate();
    }

    /**
     * Get the type of a setting by key.
     */
    public function getType(string $key): ?string
    {
        $group = $this->findGroupForKey($key);

        if ($group === null) {
            return null;
        }

        $field = $this->getField($group, $key);
        return $field['type'] ?? null;
    }

    /**
     * Check if a setting exists.
     */
    public function has(string $key): bool
    {
        $settings = $this->getAll();
        return array_key_exists($key, $settings) || \Salehye\Settings\Models\Setting::where('key', $key)->exists();
    }

    /**
     * Delete a setting.
     */
    public function delete(string $key): bool
    {
        $deleted = \Salehye\Settings\Models\Setting::where('key', $key)->delete();

        if ($deleted) {
            $this->clearCache();
        }

        return $deleted;
    }

    /**
     * Find the config group for a given setting key.
     */
    protected function findGroupForKey(string $key): ?string
    {
        foreach (config('settings.fields', []) as $groupName => $groupData) {
            if (isset($groupData['fields'][$key])) {
                return $groupName;
            }
        }

        return null;
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

    /**
     * Upload an image for a setting.
     *
     * @param  string  $key
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string|null  $directory
     * @param  string|null  $disk
     * @param  bool  $validate
     * @return array<string, mixed>|null
     */
    public function uploadImage(
        string $key,
        \Illuminate\Http\UploadedFile $file,
        ?string $directory = null,
        ?string $disk = null,
        bool $validate = true
    ): ?array {
        if ($validate) {
            $this->validateImage($key, $file);
        }

        $imageHandler = app(ImageHandler::class);
        $directory ??= $this->getImageDirectory($key);

        $imageData = $imageHandler->upload($file, $directory, $disk);

        $this->set($key, $imageData);

        return $imageData;
    }

    /**
     * Upload a base64 encoded image for a setting.
     *
     * @param  string  $key
     * @param  string  $base64Image
     * @param  string|null  $filename
     * @param  string|null  $directory
     * @param  string|null  $disk
     * @return array<string, mixed>|null
     */
    public function uploadBase64Image(
        string $key,
        string $base64Image,
        ?string $filename = null,
        ?string $directory = null,
        ?string $disk = null
    ): ?array {
        $imageHandler = app(ImageHandler::class);
        $filename ??= $key . '_' . time();
        $directory ??= $this->getImageDirectory($key);

        $imageData = $imageHandler->uploadBase64($base64Image, $filename, $directory, $disk);

        $this->set($key, $imageData);

        return $imageData;
    }

    /**
     * Delete an image setting.
     *
     * @param  string  $key
     * @return bool
     */
    public function deleteImage(string $key): bool
    {
        $setting = \Salehye\Settings\Models\Setting::where('key', $key)->first();

        if (!$setting || !$setting->isImage()) {
            return false;
        }

        return $setting->deleteImage();
    }

    /**
     * Get the image URL for a setting.
     *
     * @param  string  $key
     * @param  string|null  $default
     * @return string|null
     */
    public function getImageUrl(string $key, ?string $default = null): ?string
    {
        $setting = \Salehye\Settings\Models\Setting::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return $setting->getImageUrl() ?? $default;
    }

    /**
     * Validate an image file against field rules.
     *
     * @param  string  $key
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateImage(string $key, \Illuminate\Http\UploadedFile $file): void
    {
        $group = $this->findGroupForKey($key);

        if ($group === null) {
            return;
        }

        $field = $this->getField($group, $key);

        if ($field === null || !isset($field['rules'])) {
            return;
        }

        $imageHandler = app(ImageHandler::class);

        // Extract image-specific rules
        $imageRules = [];

        if (in_array('image', $field['rules'])) {
            $imageRules['allowed_types'] = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        }

        // Parse max size rule
        foreach ($field['rules'] as $rule) {
            if (str_starts_with($rule, 'max:')) {
                $imageRules['max_size'] = (int) substr($rule, 4) * 1024; // Convert to KB
            }
        }

        // Check for dimensions in field config
        if (isset($field['dimensions'])) {
            if (isset($field['dimensions']['max_width'])) {
                $imageRules['max_width'] = $field['dimensions']['max_width'];
            }
            if (isset($field['dimensions']['max_height'])) {
                $imageRules['max_height'] = $field['dimensions']['max_height'];
            }
        }

        $imageHandler->validate($file, $imageRules);
    }

    /**
     * Get the default image directory for a setting.
     *
     * @param  string  $key
     * @return string
     */
    protected function getImageDirectory(string $key): string
    {
        $group = $this->findGroupForKey($key);
        return "settings/{$group}";
    }
}
