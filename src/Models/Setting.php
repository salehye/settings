<?php

namespace Salehye\Settings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'key',
        'group',
        'is_public',
        'value',
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
            'value' => 'array',
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
        $definition = config("settings.definitions.{$this->key}");

        if ($definition === null) {
            return $this->value;
        }

        return match ($definition['type']) {
            'boolean' => (bool) $this->value,
            'integer' => (int) $this->value,
            'float' => (float) $this->value,
            'json', 'array' => (array) $this->value,
            default => (string) $this->value,
        };
    }

    /**
     * Set the setting value with proper type casting.
     */
    public function setTypedValue(mixed $value): void
    {
        $definition = config("settings.definitions.{$this->key}");

        if ($definition === null) {
            $this->value = $value;
            return;
        }

        $this->value = match ($definition['type']) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'float' => (float) $value,
            'json', 'array' => (array) $value,
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
        return config("settings.definitions.{$this->key}");
    }

    /**
     * Check if the setting is public.
     * Priority: Database > Config > Default (false)
     */
    public function isPublic(): bool
    {
        // Use database value if available
        if ($this->is_public !== null) {
            return $this->is_public;
        }

        // Fall back to config definition
        $definition = $this->getDefinition();
        return $definition['is_public'] ?? false;
    }

    /**
     * Get the translated label for the setting.
     */
    public function getLabel(?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $definition = $this->getDefinition();

        if ($definition === null || !isset($definition['translations'])) {
            return $this->key;
        }

        return $definition['translations'][$locale]
            ?? $definition['translations']['en']
            ?? $this->key;
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
        return $query->where('is_public', true);
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
}
