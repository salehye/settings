<?php

namespace Salehye\Settings\View;

use Illuminate\View\Composer;
use Salehye\Settings\Services\SettingsService;

class SettingsViewComposer implements Composer
{
    /**
     * Create a new view composer instance.
     */
    public function __construct(
        protected SettingsService $settingsService
    ) {}

    /**
     * Bind data to the view.
     */
    public function compose($view): void
    {
        $view->with('settings', $this->settingsService->getPublic());
        $view->with('allSettings', $this->settingsService->getAll());
    }
}
