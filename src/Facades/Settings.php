<?php

namespace Salehye\Settings\Facades;

use Illuminate\Support\Facades\Facade;
use Salehye\Settings\SettingsManager;

/**
 * @method static mixed get(string $key, mixed $default = null)
 * @method static bool set(string $key, mixed $value)
 * @method static array getMany(array $keys)
 * @method static array setMany(array $settings)
 * @method static array group(string $group)
 * @method static array public()
 * @method static array all()
 * @method static bool has(string $key)
 * @method static bool delete(string $key)
 * @method static string|null type(string $key)
 * @method static string label(string $key, ?string $locale = null)
 * @method static void clearCache()
 * @method static void reload()
 * @method static mixed __get(string $key)
 * @method static bool __isset(string $key)
 * @method static mixed __invoke(?string $key = null, mixed $default = null)
 *
 * @see \Salehye\Settings\SettingsManager
 */
class Settings extends Facade
{
    /**
     * Get the registered name of the component in the container.
     */
    protected static function getFacadeAccessor(): string
    {
        return SettingsManager::class;
    }
}
