<?php

namespace Salehye\Settings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Salehye\Settings\Database\Factories\SettingFactory;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'group',
        'key',
        'type',
        'value',
        'translations',
        'meta',
        'extra_data',
        'is_public',
        'is_active',
        'is_featured',
        'order',
        'published_at',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'integer',
            'value' => 'array',
            'translations' => 'array',
            'meta' => 'array',
            'extra_data' => 'array',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): SettingFactory
    {
        return SettingFactory::new();
    }

    /**
     * Get the setting value with proper type casting.
     */
    public function getTypedValue(): mixed
    {
        $definition = $this->getDefinition();

        if ($definition === null) {
            return $this->value;
        }

        $type = $definition['type'] ?? 'string';

        return match ($type) {
            'boolean' => (bool) $this->value,
            'integer' => (int) $this->value,
            'float', 'number' => (float) $this->value,
            'json', 'array', 'repeater', 'tags' => (array) $this->value,
            default => (string) $this->value,
        };
    }

    /**
     * Set the setting value with proper type casting.
     */
    public function setTypedValue(mixed $value): void
    {
        $definition = $this->getDefinition();

        if ($definition === null) {
            $this->value = $value;
            return;
        }

        $type = $definition['type'] ?? 'string';

        $this->value = match ($type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'float', 'number' => (float) $value,
            'json', 'array', 'repeater', 'tags' => (array) $value,
            default => (string) $value,
        };
    }

    /**
     * Get the setting definition from config.
     *
     * @return array<string, mixed>|null
     */
    public function getDefinition(): ?array
    {
        return self::getFieldDefinition($this->group, $this->key);
    }

    /**
     * Get field definition from config by group and key.
     *
     * @return array<string, mixed>|null
     */
    public static function getFieldDefinition(string $group, string $key): ?array
    {
        $groupConfig = config("settings.fields.{$group}");

        if ($groupConfig === null) {
            return null;
        }

        return $groupConfig['fields'][$key] ?? null;
    }

    /**
     * Get all field definitions for a group.
     *
     * @return array<string, mixed>
     */
    public static function getGroupFields(string $group): array
    {
        $groupConfig = config("settings.fields.{$group}");
        return $groupConfig['fields'] ?? [];
    }

    /**
     * Get the field type.
     */
    public function getType(): string
    {
        $definition = $this->getDefinition();
        return $definition['type'] ?? 'text';
    }

    /**
     * Get the label for the setting.
     */
    public function getLabel(?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $definition = $this->getDefinition();

        if ($definition === null || !isset($definition['label'])) {
            return $this->key;
        }

        $label = $definition['label'];

        // If label is array (multilingual)
        if (is_array($label)) {
            return $label[$locale] ?? $label['en'] ?? $label['ar'] ?? $this->key;
        }

        // If label is string (single language)
        return (string) $label;
    }

    /**
     * Get the description for the setting.
     */
    public function getDescription(?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $definition = $this->getDefinition();

        if ($definition === null || !isset($definition['description'])) {
            return '';
        }

        $description = $definition['description'];

        if (is_array($description)) {
            return $description[$locale] ?? $description['en'] ?? $description['ar'] ?? '';
        }

        return (string) $description;
    }

    /**
     * Get the placeholder for the setting.
     */
    public function getPlaceholder(?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $definition = $this->getDefinition();

        if ($definition === null || !isset($definition['placeholder'])) {
            return '';
        }

        $placeholder = $definition['placeholder'];

        if (is_array($placeholder)) {
            return $placeholder[$locale] ?? $placeholder['en'] ?? $placeholder['ar'] ?? '';
        }

        return (string) $placeholder;
    }

    /**
     * Get the validation rules for the setting.
     *
     * @return array<int, string>
     */
    public function getRules(): array
    {
        $definition = $this->getDefinition();
        return $definition['rules'] ?? [];
    }

    /**
     * Get the default value for the setting.
     */
    public function getDefaultValue(): mixed
    {
        $definition = $this->getDefinition();
        return $definition['default'] ?? null;
    }

    /**
     * Check if the setting is translatable.
     */
    public function isTranslatable(): bool
    {
        $definition = $this->getDefinition();
        return $definition['is_translatable'] ?? false;
    }

    /**
     * Check if the setting is a system setting.
     */
    public function isSystem(): bool
    {
        $definition = $this->getDefinition();
        return $definition['is_system'] ?? false;
    }

    /**
     * Check if the setting is sensitive.
     */
    public function isSensitive(): bool
    {
        $definition = $this->getDefinition();
        return $definition['is_sensitive'] ?? false;
    }

    /**
     * Check if the setting should be encrypted.
     */
    public function isEncrypted(): bool
    {
        $definition = $this->getDefinition();
        return $definition['encrypted'] ?? false;
    }

    /**
     * Check if the setting is public.
     */
    public function isPublic(): bool
    {
        // Use database value if available
        if ($this->is_public !== null) {
            return $this->is_public;
        }

        // System settings are never public
        if ($this->isSystem()) {
            return false;
        }

        return true;
    }

    /**
     * Get the UI options for the setting.
     *
     * @return array<string, mixed>
     */
    public function getUiOptions(): array
    {
        $definition = $this->getDefinition();

        if ($definition === null) {
            return [];
        }

        // Extract UI-related options
        $uiKeys = [
            'icon',
            'placeholder',
            'rows',
            'cols',
            'min',
            'max',
            'step',
            'accepted',
            'dimensions',
            'max_size',
            'options',
            'counter',
            'pattern',
            'searchable',
            'monospace',
            'warning',
            'requires_restart'
        ];

        return collect($definition)
            ->only($uiKeys)
            ->toArray();
    }

    /**
     * Get the repeater fields if this is a repeater type.
     *
     * @return array<string, mixed>|null
     */
    public function getRepeaterFields(): ?array
    {
        $definition = $this->getDefinition();

        if (($definition['type'] ?? '') !== 'repeater') {
            return null;
        }

        return $definition['repeater_fields'] ?? null;
    }

    /**
     * Get extra data (for complex entries like partners, customers, team, etc.).
     *
     * @return array<string, mixed>
     */
    public function getExtraData(): array
    {
        return $this->extra_data ?? [];
    }

    /**
     * Set extra data.
     *
     * @param array<string, mixed> $data
     */
    public function setExtraData(array $data): self
    {
        $this->extra_data = $data;
        return $this;
    }

    /**
     * Get a specific extra data field.
     */
    public function getExtraField(string $key, mixed $default = null): mixed
    {
        return $this->extra_data[$key] ?? $default;
    }

    /**
     * Set a specific extra data field.
     */
    public function setExtraField(string $key, mixed $value): self
    {
        $this->extra_data = array_merge($this->extra_data ?? [], [$key => $value]);
        return $this;
    }

    /**
     * Check if this is an entry (has extra data).
     */
    public function isEntry(): bool
    {
        return !empty($this->extra_data);
    }

    /**
     * Get the entry data with multilingual support.
     *
     * @return array<string, mixed>
     */
    public function getEntryData(?string $locale = null): array
    {
        $locale ??= app()->getLocale();
        $data = $this->extra_data ?? [];

        // If there are translations, merge them based on locale
        if (!empty($this->translations) && is_array($this->translations)) {
            $translations = $this->translations[$locale] ?? $this->translations['en'] ?? [];
            $data = array_merge($data, $translations);
        }

        return $data;
    }

    /**
     * Check if this setting is an image type.
     */
    public function isImage(): bool
    {
        $definition = $this->getDefinition();
        return in_array($definition['type'] ?? '', ['image', 'file']);
    }

    /**
     * Get the image URL.
     */
    public function getImageUrl(): ?string
    {
        if (!$this->isImage()) {
            return null;
        }

        $value = $this->value;

        if (is_string($value)) {
            return $value;
        }

        if (is_array($value)) {
            return $value['path'] ?? null;
        }

        return null;
    }

    /**
     * Get the image metadata.
     *
     * @return array<string, mixed>|null
     */
    public function getImageData(): ?array
    {
        if (!$this->isImage()) {
            return null;
        }

        $value = $this->value;

        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            return [
                'path' => $value,
                'filename' => basename($value),
            ];
        }

        return null;
    }

    /**
     * Update the image with a new uploaded file.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string|null  $directory
     * @param  string|null  $disk
     * @return $this
     */
    public function updateImage(
        \Illuminate\Http\UploadedFile $file,
        ?string $directory = null,
        ?string $disk = null
    ): self {
        $imageHandler = app(\Salehye\Settings\Services\ImageHandler::class);

        // Delete old image if exists
        $this->deleteImage();

        // Upload new image
        $directory ??= "settings/{$this->group}";
        $imageData = $imageHandler->upload($file, $directory, $disk);

        $this->value = $imageData;
        $this->save();

        return $this;
    }

    /**
     * Delete the image file.
     */
    public function deleteImage(): bool
    {
        if (!$this->isImage()) {
            return false;
        }

        $imageHandler = app(\Salehye\Settings\Services\ImageHandler::class);
        $deleted = $imageHandler->delete($this->value);

        if ($deleted) {
            $this->value = null;
            $this->save();
        }

        return $deleted;
    }

    /**
     * Resize the image.
     *
     * @param  int  $width
     * @param  int  $height
     * @return $this
     */
    public function resizeImage(int $width, int $height): self
    {
        if (!$this->isImage()) {
            return $this;
        }

        $imageHandler = app(\Salehye\Settings\Services\ImageHandler::class);
        $imageData = $this->getImageData();

        if ($imageData && isset($imageData['directory'], $imageData['filename'], $imageData['disk'])) {
            $path = $imageData['directory'] . '/' . $imageData['filename'];
            $resizedPath = $imageHandler->resize($path, $width, $height, $imageData['disk']);

            $this->value = array_merge($imageData, [
                'path' => \Illuminate\Support\Facades\Storage::disk($imageData['disk'])->url($resizedPath),
                'filename' => basename($resizedPath),
                'width' => $width,
                'height' => $height,
            ]);

            $this->save();
        }

        return $this;
    }

    /**
     * Scope a query to get all partners.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     */
    public function scopePartners(
        \Illuminate\Database\Eloquent\Builder $query
    ): \Illuminate\Database\Eloquent\Builder {
        return $query->group('partners')->active()->ordered();
    }

    /**
     * Scope a query to get all customers.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     */
    public function scopeCustomers(
        \Illuminate\Database\Eloquent\Builder $query
    ): \Illuminate\Database\Eloquent\Builder {
        return $query->group('customers')->active()->ordered();
    }

    /**
     * Scope a query to get all team members.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     */
    public function scopeTeam(
        \Illuminate\Database\Eloquent\Builder $query
    ): \Illuminate\Database\Eloquent\Builder {
        return $query->group('team')->active()->ordered();
    }

    /**
     * Scope a query to get all services.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     */
    public function scopeServices(
        \Illuminate\Database\Eloquent\Builder $query
    ): \Illuminate\Database\Eloquent\Builder {
        return $query->group('services')->active()->ordered();
    }

    /**
     * Scope a query to get all testimonials.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     */
    public function scopeTestimonials(
        \Illuminate\Database\Eloquent\Builder $query
    ): \Illuminate\Database\Eloquent\Builder {
        return $query->group('testimonials')->active()->ordered();
    }

    /**
     * Scope a query to get all FAQs.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     */
    public function scopeFaqs(
        \Illuminate\Database\Eloquent\Builder $query
    ): \Illuminate\Database\Eloquent\Builder {
        return $query->group('faqs')->active()->ordered();
    }

    /**
     * Scope a query to get all products.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     */
    public function scopeProducts(
        \Illuminate\Database\Eloquent\Builder $query
    ): \Illuminate\Database\Eloquent\Builder {
        return $query->group('products')->active()->ordered();
    }

    /**
     * Scope a query to only include settings from a specific group.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     */
    public function scopeGroup(
        \Illuminate\Database\Eloquent\Builder $query,
        string $group
    ): \Illuminate\Database\Eloquent\Builder {
        return $query->where('group', $group);
    }

    /**
     * Scope a query to only include public settings.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     */
    public function scopePublic(
        \Illuminate\Database\Eloquent\Builder $query
    ): \Illuminate\Database\Eloquent\Builder {
        return $query->where('is_public', true)
            ->where('is_active', true);
    }

    /**
     * Scope a query to only include active settings.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     */
    public function scopeActive(
        \Illuminate\Database\Eloquent\Builder $query
    ): \Illuminate\Database\Eloquent\Builder {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured settings.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     */
    public function scopeFeatured(
        \Illuminate\Database\Eloquent\Builder $query
    ): \Illuminate\Database\Eloquent\Builder {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include settings by type.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     */
    public function scopeType(
        \Illuminate\Database\Eloquent\Builder $query,
        string $type
    ): \Illuminate\Database\Eloquent\Builder {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to order settings.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     */
    public function scopeOrdered(
        \Illuminate\Database\Eloquent\Builder $query
    ): \Illuminate\Database\Eloquent\Builder {
        return $query->orderBy('order')->orderBy('created_at');
    }

    /**
     * Scope a query to only include published settings.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     */
    public function scopePublished(
        \Illuminate\Database\Eloquent\Builder $query
    ): \Illuminate\Database\Eloquent\Builder {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include entries (complex data like partners, customers, etc.).
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     */
    public function scopeEntries(
        \Illuminate\Database\Eloquent\Builder $query
    ): \Illuminate\Database\Eloquent\Builder {
        return $query->whereNotNull('extra_data');
    }

    /**
     * Get entries by group and type (for complex data like partners, customers, team, etc.).
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     */
    public function scopeGroupAndType(
        \Illuminate\Database\Eloquent\Builder $query,
        string $group,
        ?string $type = null
    ): \Illuminate\Database\Eloquent\Builder {
        $query->where('group', $group);

        if ($type !== null) {
            $query->where('type', $type);
        }

        return $query;
    }

    /**
     * Scope a query to only include settings by keys.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     * @param array<int, string> $keys
     */
    public function scopeKeys(
        \Illuminate\Database\Eloquent\Builder $query,
        array $keys
    ): \Illuminate\Database\Eloquent\Builder {
        return $query->whereIn('key', $keys);
    }

    /**
     * Scope a query to only include system settings.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     */
    public function scopeSystem(
        \Illuminate\Database\Eloquent\Builder $query
    ): \Illuminate\Database\Eloquent\Builder {
        $systemKeys = collect(config('settings.fields', []))
            ->flatMap(
                fn(array $group) => collect($group['fields'] ?? [])
                    ->filter(fn(array $field) => $field['is_system'] ?? false)
                    ->keys()
            )
            ->toArray();

        return $query->whereIn('key', $systemKeys);
    }

    /**
     * Scope a query to only include sensitive settings.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     */
    public function scopeSensitive(
        \Illuminate\Database\Eloquent\Builder $query
    ): \Illuminate\Database\Eloquent\Builder {
        $sensitiveKeys = collect(config('settings.fields', []))
            ->flatMap(
                fn(array $group) => collect($group['fields'] ?? [])
                    ->filter(fn(array $field) => $field['is_sensitive'] ?? false)
                    ->keys()
            )
            ->toArray();

        return $query->whereIn('key', $sensitiveKeys);
    }
}
