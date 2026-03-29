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

if (!function_exists('setting_label')) {
    /**
     * Get the label for a setting.
     *
     * @param string $key The setting key
     * @param string|null $locale The locale (defaults to current app locale)
     * @return string
     */
    function setting_label(string $key, ?string $locale = null): string
    {
        return Settings::label($key, $locale);
    }
}

if (!function_exists('setting_type')) {
    /**
     * Get the type of a setting.
     *
     * @param string $key The setting key
     * @return string|null
     */
    function setting_type(string $key): ?string
    {
        return Settings::type($key);
    }
}

if (!function_exists('setting_default')) {
    /**
     * Get the default value for a setting from config.
     *
     * @param string $key The setting key
     * @return mixed
     */
    function setting_default(string $key): mixed
    {
        foreach (config('settings.fields', []) as $groupData) {
            if (isset($groupData['fields'][$key])) {
                return $groupData['fields'][$key]['default'] ?? null;
            }
        }
        return null;
    }
}

if (!function_exists('setting_rules')) {
    /**
     * Get the validation rules for a setting.
     *
     * @param string $key The setting key
     * @return array<int, string>
     */
    function setting_rules(string $key): array
    {
        foreach (config('settings.fields', []) as $groupData) {
            if (isset($groupData['fields'][$key])) {
                return $groupData['fields'][$key]['rules'] ?? [];
            }
        }
        return [];
    }
}

if (!function_exists('setting_description')) {
    /**
     * Get the description for a setting.
     *
     * @param string $key The setting key
     * @param string|null $locale The locale (defaults to current app locale)
     * @return string
     */
    function setting_description(string $key, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        foreach (config('settings.fields', []) as $groupData) {
            if (isset($groupData['fields'][$key])) {
                $description = $groupData['fields'][$key]['description'] ?? '';

                if (is_array($description)) {
                    return $description[$locale] ?? $description['en'] ?? $description['ar'] ?? '';
                }

                return (string) $description;
            }
        }
        return '';
    }
}

if (!function_exists('setting_is_translatable')) {
    /**
     * Check if a setting is translatable.
     *
     * @param string $key The setting key
     * @return bool
     */
    function setting_is_translatable(string $key): bool
    {
        foreach (config('settings.fields', []) as $groupData) {
            if (isset($groupData['fields'][$key])) {
                return $groupData['fields'][$key]['is_translatable'] ?? false;
            }
        }
        return false;
    }
}

if (!function_exists('setting_is_system')) {
    /**
     * Check if a setting is a system setting.
     *
     * @param string $key The setting key
     * @return bool
     */
    function setting_is_system(string $key): bool
    {
        foreach (config('settings.fields', []) as $groupData) {
            if (isset($groupData['fields'][$key])) {
                return $groupData['fields'][$key]['is_system'] ?? false;
            }
        }
        return false;
    }
}

if (!function_exists('setting_is_sensitive')) {
    /**
     * Check if a setting is sensitive.
     *
     * @param string $key The setting key
     * @return bool
     */
    function setting_is_sensitive(string $key): bool
    {
        foreach (config('settings.fields', []) as $groupData) {
            if (isset($groupData['fields'][$key])) {
                return $groupData['fields'][$key]['is_sensitive'] ?? false;
            }
        }
        return false;
    }
}

if (!function_exists('settings_group_label')) {
    /**
     * Get the label for a settings group.
     *
     * @param string $group The group name
     * @param string|null $locale The locale (defaults to current app locale)
     * @return string
     */
    function settings_group_label(string $group, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $groupConfig = config("settings.fields.{$group}");

        if ($groupConfig === null) {
            return $group;
        }

        $label = $groupConfig['label'] ?? $group;

        if (is_array($label)) {
            return $label[$locale] ?? $label['en'] ?? $label['ar'] ?? $group;
        }

        return (string) $label;
    }
}

if (!function_exists('settings_group_icon')) {
    /**
     * Get the icon for a settings group.
     *
     * @param string $group The group name
     * @return string|null
     */
    function settings_group_icon(string $group): ?string
    {
        $groupConfig = config("settings.fields.{$group}");
        return $groupConfig['icon'] ?? null;
    }
}

if (!function_exists('settings_group_fields')) {
    /**
     * Get all field definitions for a settings group.
     *
     * @param string $group The group name
     * @return array<string, mixed>
     */
    function settings_group_fields(string $group): array
    {
        $groupConfig = config("settings.fields.{$group}");
        return $groupConfig['fields'] ?? [];
    }
}

if (!function_exists('setting_field')) {
    /**
     * Get field definition for a setting.
     *
     * @param string $key The setting key
     * @return array<string, mixed>|null
     */
    function setting_field(string $key): ?array
    {
        foreach (config('settings.fields', []) as $groupData) {
            if (isset($groupData['fields'][$key])) {
                return $groupData['fields'][$key];
            }
        }
        return null;
    }
}

if (!function_exists('upload_setting_image')) {
    /**
     * Upload an image for a setting.
     *
     * @param string $key The setting key
     * @param \Illuminate\Http\UploadedFile $file The uploaded file
     * @param string|null $directory Optional directory
     * @param string|null $disk Optional disk
     * @return array<string, mixed>|null
     */
    function upload_setting_image(
        string $key,
        \Illuminate\Http\UploadedFile $file,
        ?string $directory = null,
        ?string $disk = null
    ): ?array {
        return \Salehye\Settings\Facades\Settings::uploadImage($key, $file, $directory, $disk);
    }
}

if (!function_exists('upload_setting_base64_image')) {
    /**
     * Upload a base64 encoded image for a setting.
     *
     * @param string $key The setting key
     * @param string $base64Image The base64 encoded image
     * @param string|null $filename Optional filename
     * @param string|null $directory Optional directory
     * @param string|null $disk Optional disk
     * @return array<string, mixed>|null
     */
    function upload_setting_base64_image(
        string $key,
        string $base64Image,
        ?string $filename = null,
        ?string $directory = null,
        ?string $disk = null
    ): ?array {
        return \Salehye\Settings\Facades\Settings::uploadBase64Image($key, $base64Image, $filename, $directory, $disk);
    }
}

if (!function_exists('delete_setting_image')) {
    /**
     * Delete an image setting.
     *
     * @param string $key The setting key
     * @return bool
     */
    function delete_setting_image(string $key): bool
    {
        return \Salehye\Settings\Facades\Settings::deleteImage($key);
    }
}

if (!function_exists('setting_image_url')) {
    /**
     * Get the image URL for a setting.
     *
     * @param string $key The setting key
     * @param string|null $default Default URL if not found
     * @return string|null
     */
    function setting_image_url(string $key, ?string $default = null): ?string
    {
        return \Salehye\Settings\Facades\Settings::getImageUrl($key, $default);
    }
}

if (!function_exists('setting_is_image')) {
    /**
     * Check if a setting is an image type.
     *
     * @param string $key The setting key
     * @return bool
     */
    function setting_is_image(string $key): bool
    {
        $setting = \Salehye\Settings\Models\Setting::where('key', $key)->first();
        return $setting ? $setting->isImage() : false;
    }
}

if (!function_exists('setting_image_data')) {
    /**
     * Get the image data for a setting.
     *
     * @param string $key The setting key
     * @return array<string, mixed>|null
     */
    function setting_image_data(string $key): ?array
    {
        $setting = \Salehye\Settings\Models\Setting::where('key', $key)->first();
        return $setting ? $setting->getImageData() : null;
    }
}
