<?php

namespace Salehye\Settings\View\Components;

use Illuminate\View\Component;
use Salehye\Settings\Facades\Settings;

class Setting extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $key,
        public mixed $default = null
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('settings::components.setting', [
            'value' => Settings::get($this->key, $this->default),
        ]);
    }
}
