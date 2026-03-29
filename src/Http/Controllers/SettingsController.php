<?php

namespace Salehye\Settings\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Salehye\Settings\Services\SettingsService;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected SettingsService $settingsService
    ) {
    }

    /**
     * Display all settings grouped by their group.
     */
    public function index(): Response
    {
        $allSettings = $this->settingsService->getAll();
        $fieldsConfig = config('settings.fields', []);

        // Get all settings from database with their metadata
        $settingsFromDb = \Salehye\Settings\Models\Setting::all();

        $groupedSettings = $settingsFromDb
            ->map(fn($setting) => [
                'key' => $setting->key,
                'value' => $setting->value ?? $this->getDefaultFromConfig($setting->key),
                'type' => $setting->getDefinition()['type'] ?? $this->inferType($setting->value),
                'group' => $setting->group,
                'is_public' => $setting->is_public,
                'label' => $setting->getLabel(),
                'rules' => $setting->getDefinition()['rules'] ?? [],
            ])
            ->groupBy('group')
            ->toArray();

        return Inertia::render('Settings/Index', [
            'settings' => $groupedSettings,
            'groups' => $this->getGroupsMetadata(),
        ]);
    }

    /**
     * Display a specific settings group.
     */
    public function show(string $group): Response
    {
        $settings = \Salehye\Settings\Models\Setting::where('group', $group)->get();

        $settingsData = $settings
            ->map(fn($setting) => [
                'key' => $setting->key,
                'value' => $setting->value ?? $this->getDefaultFromConfig($setting->key),
                'type' => $setting->getDefinition()['type'] ?? $this->inferType($setting->value),
                'label' => $setting->getLabel(),
                'rules' => $setting->getDefinition()['rules'] ?? [],
                'is_public' => $setting->is_public,
            ])
            ->toArray();

        return Inertia::render('Settings/Group', [
            'group' => $group,
            'settings' => $settingsData,
            'groupMeta' => config("settings.fields.{$group}"),
        ]);
    }

    /**
     * Get default value from config for a key.
     */
    protected function getDefaultFromConfig(string $key): mixed
    {
        foreach (config('settings.fields', []) as $groupData) {
            if (isset($groupData['fields'][$key])) {
                return $groupData['fields'][$key]['default'] ?? null;
            }
        }
        return null;
    }

    /**
     * Get groups metadata with labels.
     */
    protected function getGroupsMetadata(): array
    {
        $locale = app()->getLocale();
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

    /**
     * Update multiple settings.
     *
     * @param array<string, mixed> $settings
     */
    public function update(Request $request): JsonResponse
    {
        $settings = $request->input('settings', []);
        $results = $this->settingsService->setMany($settings);

        $failed = collect($results)
            ->filter(fn(bool $success) => !$success)
            ->keys()
            ->toArray();

        if (!empty($failed)) {
            return response()->json([
                'success' => false,
                'message' => 'Some settings failed to update',
                'failed' => $failed,
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully',
        ]);
    }

    /**
     * Get public settings for API/Inertia consumption.
     */
    public function public(): JsonResponse
    {
        return response()->json([
            'settings' => $this->settingsService->getPublic(),
        ]);
    }

    /**
     * Infer the type of a value.
     */
    protected function inferType(mixed $value): string
    {
        if (is_bool($value)) {
            return 'boolean';
        }
        if (is_int($value)) {
            return 'integer';
        }
        if (is_float($value)) {
            return 'float';
        }
        if (is_array($value)) {
            return 'json';
        }
        if (strlen((string) $value) > 255) {
            return 'text';
        }
        return 'string';
    }
}
