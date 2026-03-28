# Upgrade Guide

## From v1.0.0 to v1.1.0

No breaking changes.

### New Features

- Blade directives support
- Blade component support
- View composer for automatic sharing

### Migration Steps

1. Update the package:
   ```bash
   composer require salehye/settings:^1.1.0
   ```

2. Publish new assets (optional):
   ```bash
   php artisan vendor:publish --provider="Salehye\Settings\SettingsServiceProvider" --tag=settings-views
   ```

3. Start using Blade features:
   ```blade
   @settings('site_name')
   <x-setting key="timezone" />
   ```

## From v0.x to v1.0.0

### Breaking Changes

- Namespace changed from `Saleh\Settings` to `Salehye\Settings`

### Migration Steps

1. Update namespace in your code:
   ```php
   // Before
   use Saleh\Settings\Facades\Settings;
   
   // After
   use Salehye\Settings\Facades\Settings;
   ```

2. Update composer.json:
   ```bash
   composer require salehye/settings
   composer remove saleh/settings
   ```

3. Clear cache:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```
