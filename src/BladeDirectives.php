<?php

use Illuminate\Support\Facades\Blade;
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

// @settingLabel('key') - Get setting label with multilingual support
Blade::directive('settingLabel', function ($expression) {
    return "<?php echo Settings::label({$expression}); ?>";
});

// @settingLabelFor('key', 'locale') - Get setting label for specific locale
Blade::directive('settingLabelFor', function ($expression) {
    return "<?php 
        \$parts = explode(',', {$expression});
        \$key = trim(\$parts[0]);
        \$locale = isset(\$parts[1]) ? trim(\$parts[1]) : null;
        echo Settings::label(\$key, \$locale);
    ?>";
});

// @settingDescription('key') - Get setting description
Blade::directive('settingDescription', function ($expression) {
    return "<?php echo setting_description({$expression}); ?>";
});

// @settingDefault('key') - Get setting default value
Blade::directive('settingDefault', function ($expression) {
    return "<?php echo setting_default({$expression}); ?>";
});

// @settingType('key') - Get setting type
Blade::directive('settingType', function ($expression) {
    return "<?php echo setting_type({$expression}); ?>";
});

// @settingRules('key') - Get setting validation rules as JSON
Blade::directive('settingRules', function ($expression) {
    return "<?php echo json_encode(setting_rules({$expression})); ?>";
});

// @settingField('key') - Get full field definition as JSON
Blade::directive('settingField', function ($expression) {
    return "<?php echo json_encode(setting_field({$expression})); ?>";
});

// @settingsGroupLabel('group') - Get group label
Blade::directive('settingsGroupLabel', function ($expression) {
    return "<?php echo settings_group_label({$expression}); ?>";
});

// @settingsGroupIcon('group') - Get group icon
Blade::directive('settingsGroupIcon', function ($expression) {
    return "<?php echo settings_group_icon({$expression}); ?>";
});

// @foreachSettingsGroup('group') - Loop through settings in a group
Blade::directive('foreachSettingsGroup', function ($expression) {
    return "<?php foreach (settings_group({$expression}) as \$key => \$value): ?>";
});

// @endforeachSettingsGroup
Blade::directive('endforeachSettingsGroup', function () {
    return '<?php endforeach; ?>';
});

// @unlessSetting('key') - Render unless setting has value
Blade::directive('unlessSetting', function ($expression) {
    return "<?php if (!Settings::get({$expression})): ?>";
});

// @endUnlessSetting
Blade::directive('endUnlessSetting', function () {
    return '<?php endif; ?>';
});

// @settingIsTranslatable('key') - Check if setting is translatable
Blade::directive('settingIsTranslatable', function ($expression) {
    return "<?php echo setting_is_translatable({$expression}) ? 'true' : 'false'; ?>";
});

// @settingIsSystem('key') - Check if setting is system
Blade::directive('settingIsSystem', function ($expression) {
    return "<?php echo setting_is_system({$expression}) ? 'true' : 'false'; ?>";
});

// @settingIsSensitive('key') - Check if setting is sensitive
Blade::directive('settingIsSensitive', function ($expression) {
    return "<?php echo setting_is_sensitive({$expression}) ? 'true' : 'false'; ?>";
});
