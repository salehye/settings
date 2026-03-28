<?php

use Salehye\Settings\Facades\Settings;

/*
|--------------------------------------------------------------------------
| Blade Directives
|--------------------------------------------------------------------------
*/

// @settings('key') - Get a setting
Blade::directive('settings', function ($expression) {
    return "<?php echo Settings::get({$expression}); ?>";
});

// @settingsOr('key', 'default') - Get a setting or default
Blade::directive('settingsOr', function ($expression) {
    return "<?php echo Settings::get({$expression}); ?>";
});

// @ifSettings('key') - Check if setting exists
Blade::directive('ifSettings', function ($expression) {
    return "<?php if (Settings::has({$expression})): ?>";
});

// @endIfSettings
Blade::directive('endIfSettings', function () {
    return '<?php endif; ?>';
});

// @settingsGroup('group') - Get settings from a group
Blade::directive('settingsGroup', function ($expression) {
    return "<?php print_r(Settings::group({$expression})); ?>";
});

// @jsonSettings('key') - Get setting as JSON
Blade::directive('jsonSettings', function ($expression) {
    return "<?php echo json_encode(Settings::get({$expression})); ?>";
});

// @setting('key') - Alias for @settings
Blade::directive('setting', function ($expression) {
    return "<?php echo Settings::get({$expression}); ?>";
});
