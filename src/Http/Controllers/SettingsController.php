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
     * Upload an image for a setting.
     */
    public function uploadImage(Request $request, string $key): JsonResponse
    {
        $request->validate([
            'image' => 'required|image',
        ]);

        try {
            $file = $request->file('image');
            $directory = $request->input('directory');
            $disk = $request->input('disk');

            $imageData = $this->settingsService->uploadImage(
                $key,
                $file,
                $directory,
                $disk
            );

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data' => $imageData,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Image validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload a base64 encoded image.
     */
    public function uploadBase64Image(Request $request, string $key): JsonResponse
    {
        $request->validate([
            'image' => 'required|string',
            'filename' => 'nullable|string',
        ]);

        try {
            $imageData = $this->settingsService->uploadBase64Image(
                $key,
                $request->input('image'),
                $request->input('filename'),
                $request->input('directory'),
                $request->input('disk')
            );

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data' => $imageData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete an image setting.
     */
    public function deleteImage(string $key): JsonResponse
    {
        try {
            $deleted = $this->settingsService->deleteImage($key);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'No image found to delete',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get the image URL for a setting.
     */
    public function getImageUrl(string $key): JsonResponse
    {
        $url = $this->settingsService->getImageUrl($key);

        if (!$url) {
            return response()->json([
                'success' => false,
                'message' => 'No image found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'url' => $url,
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
