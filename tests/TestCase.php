<?php

namespace Salehye\Settings\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Salehye\Settings\SettingsServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Get package providers.
     */
    protected function getPackageProviders($app): array
    {
        return [
            SettingsServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        $app['config']->set('cache.default', 'array');
        $app['config']->set('settings.cache.enabled', false);
    }
}
