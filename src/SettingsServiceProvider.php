<?php

namespace Salehye\Settings;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Salehye\Settings\Contracts\SettingsRepositoryInterface;
use Salehye\Settings\Repositories\SettingsRepository;
use Salehye\Settings\View\SettingsViewComposer;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/settings.php',
            'settings'
        );

        // Bind repository interface to implementation
        $this->app->bind(
            SettingsRepositoryInterface::class,
            SettingsRepository::class
        );

        // Bind SettingsManager as singleton
        $this->app->singleton(
            SettingsManager::class,
            fn($app) => new SettingsManager()
        );

        // Load helpers
        $this->loadHelpersFrom(__DIR__ . '/helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../config/settings.php' => config_path('settings.php'),
        ], 'settings-config');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'settings-migrations');

        // Publish factory
        $this->publishes([
            __DIR__ . '/../database/factories' => database_path('factories'),
        ], 'settings-factories');

        // Publish language files
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'settings');
        $this->publishes([
            __DIR__ . '/../lang' => lang_path('vendor/settings'),
        ], 'settings-lang');

        // Publish views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'settings');
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/settings'),
        ], 'settings-views');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        // Register Blade directives
        $this->registerBladeDirectives();

        // Register View Composers
        $this->registerViewComposers();

        // Register Blade components
        $this->registerBladeComponents();
    }

    /**
     * Register Blade directives.
     */
    protected function registerBladeDirectives(): void
    {
        // @settings('key') - Get a setting
        Blade::directive('settings', function ($expression) {
            return "<?php echo \\Salehye\\Settings\\Facades\\Settings::get({$expression}); ?>";
        });

        // @setting('key') - Alias for @settings
        Blade::directive('setting', function ($expression) {
            return "<?php echo \\Salehye\\Settings\\Facades\\Settings::get({$expression}); ?>";
        });

        // @settingsOr('key', 'default') - Get a setting or default
        Blade::directive('settingsOr', function ($expression) {
            return "<?php echo \\Salehye\\Settings\\Facades\\Settings::get({$expression}); ?>";
        });

        // @ifSettings('key') - Check if setting exists
        Blade::directive('ifSettings', function ($expression) {
            return "<?php if (\\Salehye\\Settings\\Facades\\Settings::has({$expression})): ?>";
        });

        // @endIfSettings
        Blade::directive('endIfSettings', function () {
            return '<?php endif; ?>';
        });

        // @jsonSettings('key') - Get setting as JSON
        Blade::directive('jsonSettings', function ($expression) {
            return "<?php echo json_encode(\\Salehye\\Settings\\Facades\\Settings::get({$expression})); ?>";
        });

        // @settingsGroup('group') - Get settings from a group as JSON
        Blade::directive('settingsGroup', function ($expression) {
            return "<?php echo json_encode(\\Salehye\\Settings\\Facades\\Settings::group({$expression})); ?>";
        });
    }

    /**
     * Register View Composers.
     */
    protected function registerViewComposers(): void
    {
        // Share public settings with all views
        $this->app->make('view')->composer(
            '*',
            SettingsViewComposer::class
        );
    }

    /**
     * Register Blade components.
     */
    protected function registerBladeComponents(): void
    {
        Blade::component('setting', \Salehye\Settings\View\Components\Setting::class);
    }

    /**
     * Load helper functions.
     */
    protected function loadHelpersFrom(string $path): void
    {
        if (file_exists($path)) {
            require_once $path;
        }
    }
}
