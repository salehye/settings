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
    ) {
    }

    /**
     * Bind data to the view.
     */
    public function compose($view): void
    {
        $locale = app()->getLocale();

        // Share public settings for all views
        $view->with('settings', $this->settingsService->getPublic());

        // Share all settings (use with caution)
        $view->with('allSettings', $this->settingsService->getAll());

        // Share settings groups metadata
        $view->with('settingsGroups', $this->getGroupsMetadata($locale));

        // Share helper functions availability
        $view->with('settingLabel', fn(string $key) => $this->settingsService->getLabel($key, $locale));
        $view->with('settingDescription', fn(string $key) => setting_description($key, $locale));
        $view->with('settingDefault', 'setting_default');
        $view->with('settingRules', 'setting_rules');
        $view->with('settingField', 'setting_field');
        $view->with('settingsGroupLabel', fn(string $group) => settings_group_label($group, $locale));
        $view->with('settingsGroupIcon', 'settings_group_icon');
        $view->with('settingsGroupFields', 'settings_group_fields');
    }

    /**
     * Get groups metadata with labels.
     *
     * @return array<string, mixed>
     */
    protected function getGroupsMetadata(string $locale): array
    {
        $groups = [];

        foreach (config('settings.fields', []) as $groupName => $groupData) {
            $label = $groupData['label'] ?? $groupName;
            $groups[$groupName] = [
                'label' => is_array($label)
                    ? ($label[$locale] ?? $label['en'] ?? $label['ar'] ?? $groupName)
                    : $label,
                'icon' => $groupData['icon'] ?? null,
                'order' => $groupData['order'] ?? 999,
                'is_system' => $groupData['is_system'] ?? false,
            ];
        }

        return collect($groups)
            ->sortBy('order')
            ->toArray();
    }
}
