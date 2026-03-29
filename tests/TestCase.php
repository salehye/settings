<?php

namespace Salehye\Settings\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\View;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Salehye\Settings\Http\Middleware\ShareSettingsMiddleware;
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
            \Inertia\ServiceProvider::class,
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

    /**
     * Define database migrations.
     */
    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Define routes.
     */
    protected function defineRoutes($router): void
    {
        // API routes for settings
        $router->prefix('settings')->group(function ($router) {
            $router->get('/', [\Salehye\Settings\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
            $router->get('/{group}', [\Salehye\Settings\Http\Controllers\SettingsController::class, 'show'])->name('settings.show');
            $router->put('/', [\Salehye\Settings\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
            $router->get('/api/public', [\Salehye\Settings\Http\Controllers\SettingsController::class, 'public'])->name('settings.public');
        });

        // Web route for middleware testing - doesn't pass settings directly
        // Note: Inertia middleware is applied automatically by Inertia\ServiceProvider
        $router->middleware(['web', ShareSettingsMiddleware::class])->group(function ($router) {
            $router->get('/', function () {
                return \Inertia\Inertia::render('App');
            });
        });
    }

    /**
     * Get application time zone.
     */
    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        // Ensure Inertia middleware is registered
        $router = $app['router'];
        $router->pushMiddlewareToGroup('web', \Inertia\Middleware::class);
    }
}
