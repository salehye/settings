<?php

namespace Salehye\Settings\View\Components;

use Illuminate\View\Component;
use Salehye\Settings\Facades\Settings;
use Salehye\Settings\Models\Setting as SettingModel;

class Setting extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $key,
        public mixed $default = null,
        public ?string $locale = null,
        public bool $withLabel = false,
        public bool $withDescription = false,
        public bool $withRules = false,
        public bool $withField = false
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $this->locale ??= app()->getLocale();

        $data = [
            'value' => Settings::get($this->key, $this->default),
            'key' => $this->key,
            'default' => $this->default,
        ];

        if ($this->withLabel) {
            $data['label'] = Settings::label($this->key, $this->locale);
        }

        if ($this->withDescription) {
            $data['description'] = setting_description($this->key, $this->locale);
        }

        if ($this->withRules) {
            $data['rules'] = setting_rules($this->key);
        }

        if ($this->withField) {
            $data['field'] = setting_field($this->key);
        }

        // Add metadata
        $data['type'] = Settings::type($this->key);
        $data['isTranslatable'] = setting_is_translatable($this->key);
        $data['isSystem'] = setting_is_system($this->key);
        $data['isSensitive'] = setting_is_sensitive($this->key);

        return view('settings::components.setting', $data);
    }

    /**
     * Get the setting value directly.
     */
    public function getValue(): mixed
    {
        return Settings::get($this->key, $this->default);
    }

    /**
     * Get the setting label.
     */
    public function getLabel(): string
    {
        return Settings::label($this->key, $this->locale);
    }

    /**
     * Get the setting description.
     */
    public function getDescription(): string
    {
        return setting_description($this->key, $this->locale);
    }

    /**
     * Get the setting type.
     */
    public function getType(): ?string
    {
        return Settings::type($this->key);
    }

    /**
     * Check if the setting is translatable.
     */
    public function isTranslatable(): bool
    {
        return setting_is_translatable($this->key);
    }

    /**
     * Check if the setting is a system setting.
     */
    public function isSystem(): bool
    {
        return setting_is_system($this->key);
    }

    /**
     * Check if the setting is sensitive.
     */
    public function isSensitive(): bool
    {
        return setting_is_sensitive($this->key);
    }
}
