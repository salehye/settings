# Salehye Settings Package

> **A lightweight, flexible Laravel settings package with InertiaJS & Blade support, multilingual capabilities, and database-driven configuration.**

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=flat-square&logo=php)
![InertiaJS](https://img.shields.io/badge/InertiaJS-2.x-9553E9?style=flat-square)
![Blade](https://img.shields.io/badge/Blade-Ready-F5503C?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)
![Tests](https://img.shields.io/github/actions/workflow/status/salehye/settings/tests.yml?branch=main&label=tests&style=flat-square)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/salehye/settings.svg?style=flat-square)](https://packagist.org/packages/salehye/settings)
[![Total Downloads](https://img.shields.io/packagist/dt/salehye/settings.svg?style=flat-square)](https://packagist.org/packages/salehye/settings)

## ✨ Features

- 🗄️ **Database-Driven** - Store settings in database with `is_public` flag
- 🌐 **Multilingual** - Support for translated labels and content
- ⚡ **Caching** - Built-in caching for optimal performance
- 🎨 **InertiaJS Ready** - Auto-share public settings with frontend
- 🔧 **Flexible** - Works with or without predefined definitions
- 🧪 **Tested** - Comprehensive Pest PHP test suite
- 📦 **SOLID** - Clean architecture following Laravel best practices

## 📦 Installation

### 1. Require the package

```bash
composer require salehye/settings
```

### 2. Publish assets

```bash
# Publish config file
php artisan vendor:publish --provider="Salehye\Settings\SettingsServiceProvider" --tag="settings-config"

# Publish migrations
php artisan vendor:publish --provider="Salehye\Settings\SettingsServiceProvider" --tag="settings-migrations"

# Publish all
php artisan vendor:publish --provider="Salehye\Settings\SettingsServiceProvider"
```

### 3. Run migrations

```bash
php artisan migrate
```

### 4. Register Middleware (Optional)

Add the middleware to share settings with Inertia automatically:

**Laravel 11.x/12.x** (`bootstrap/app.php`):

```php
use Salehye\Settings\Http\Middleware\ShareSettingsMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            ShareSettingsMiddleware::class,
        ]);
    })
    ->withProviders();
```

## 🚀 Usage Methods

### Method 1: Helper Functions (Recommended) ⭐

```php
// Get a setting
$siteName = settings('site_name');
$siteName = setting('site_name'); // Alias

// Set a setting
set_setting('site_name', 'My Website');

// Get group
$general = settings_group('general');

// Get public settings
$public = settings_public();

// Get all settings
$all = settings_all();

// With default value
$timezone = settings('timezone', 'UTC');
```

### Method 2: Facade

```php
use Salehye\Settings\Facades\Settings;

// Get
$value = Settings::get('site_name');
$value = Settings::get('site_name', 'Default');

// Set
Settings::set('site_name', 'My Website');

// Multiple
$settings = Settings::getMany(['site_name', 'timezone']);
Settings::setMany([
    'site_name' => 'My Site',
    'timezone' => 'Asia/Riyadh',
]);

// Group
$general = Settings::group('general');

// Public
$public = Settings::public();

// All
$all = Settings::all();

// Check
if (Settings::has('site_name')) {
    // ...
}

// Delete
Settings::delete('site_name');

// Cache
Settings::clearCache();
Settings::reload();
```

### Method 3: Magic Methods

```php
use Salehye\Settings\Facades\Settings;

// Magic getter
$siteName = Settings::$site_name;

// Magic isset
if (isset(Settings::$site_name)) {
    // ...
}

// Invoke as function
$value = Settings('site_name');
$value = Settings('site_name', 'default');
$all = Settings(); // Returns all settings
```

### Method 4: HasSettings Trait (In Controllers)

```php
use Salehye\Settings\Concerns\HasSettings;

class MyController extends Controller
{
    use HasSettings;

    public function index()
    {
        $siteName = $this->getSetting('site_name');
        $this->setSetting('timezone', 'UTC');

        $settings = $this->getSettings(['site_name', 'timezone']);
        $general = $this->getSettingsGroup('general');
        $public = $this->getPublicSettings();
        $all = $this->getAllSettings();

        if ($this->hasSetting('site_name')) {
            // ...
        }

        $this->deleteSetting('old_setting');
        $this->clearSettingsCache();
    }
}
```

### Method 5: Dependency Injection

```php
use Salehye\Settings\SettingsManager;
use Salehye\Settings\Contracts\SettingsRepositoryInterface;

class MyService
{
    public function __construct(
        protected SettingsManager $settings,
        // OR
        protected SettingsRepositoryInterface $repository
    ) {}

    public function doSomething(): void
    {
        $value = $this->settings->get('site_name');
        // OR
        $value = $this->repository->get('site_name');
    }
}
```

## 🎨 Blade Templates Integration

### View Composer (Automatic)

Public settings are **automatically shared** with all Blade views:

```blade
{{-- In any Blade template --}}
<h1>{{ $settings['site_name'] ?? 'Default' }}</h1>

{{-- Access any public setting --}}
<p>{{ $settings['contact_email'] }}</p>
```

### Blade Directives

```blade
{{-- Get a setting --}}
@settings('site_name')
@setting('site_name')  {{-- Alias --}}

{{-- Get with default --}}
@settingsOr('timezone', 'UTC')

{{-- Conditional rendering --}}
@ifSettings('maintenance_mode')
    <div>Maintenance Mode Active</div>
@endIfSettings

{{-- Get as JSON --}}
<script>
    const keywords = @jsonSettings('seo_keywords');
</script>

{{-- Get group settings --}}
<script>
    const general = @settingsGroup('general');
</script>
```

### Blade Component

```blade
{{-- Simple usage --}}
<x-setting key="site_name" />

{{-- With default --}}
<x-setting key="timezone" :default="'UTC'" />
```

### Publish Component Views

```bash
php artisan vendor:publish --tag=settings-views
```

Then customize in `resources/views/vendor/settings/components/setting.blade.php`

## 📊 Database Schema

```php
Schema::create('settings', function (Blueprint $table) {
    $table->id();
    $table->string('key')->unique()->index();
    $table->string('group')->default('general')->index();
    $table->boolean('is_public')->default(false)->index();
    $table->json('value')->nullable();
    $table->timestamps();

    $table->index(['group', 'is_public']);
});
```

## 🎯 Creating Settings

### Method 1: Direct Database (Recommended)

```php
use Salehye\Settings\Models\Setting;

// Simple setting
Setting::create([
    'key' => 'site_name',
    'group' => 'general',
    'is_public' => true,
    'value' => 'My Website',
]);

// JSON setting
Setting::create([
    'key' => 'social_links',
    'group' => 'social',
    'is_public' => true,
    'value' => [
        'twitter' => 'https://twitter.com/mysite',
        'facebook' => 'https://facebook.com/mysite',
    ],
]);

// Boolean setting
Setting::create([
    'key' => 'maintenance_mode',
    'group' => 'system',
    'is_public' => false,
    'value' => false,
]);
```

### Method 2: With Config Definitions

Add to `config/settings.php`:

```php
'definitions' => [
    'site_name' => [
        'type' => 'string',
        'group' => 'general',
        'is_public' => true,
        'default' => 'My Website',
        'rules' => ['nullable', 'string', 'max:255'],
        'translations' => [
            'ar' => 'اسم الموقع',
            'en' => 'Site Name',
        ],
    ],
],
```

## 🌐 InertiaJS Integration

### Auto-Share Public Settings

After registering `ShareSettingsMiddleware`, all public settings are automatically shared:

```javascript
// In your Vue/React component
import { usePage } from "@inertiajs/vue3"; // or '@inertiajs/react'

const page = usePage();
const settings = page.props.settings;

console.log(settings.site_name); // Access public settings
```

### Settings Controller

The package provides a controller for settings management:

```php
// routes/web.php
use Salehye\Settings\Http\Controllers\SettingsController;

Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::get('/settings/{group}', [SettingsController::class, 'show']);
    Route::put('/settings', [SettingsController::class, 'update']);
});
```

### Inertia Frontend Example (Vue 3)

```vue
<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
  settings: {
    site_name: "My Website",
    site_description: "",
  },
});

const submit = () => {
  form.put("/settings", {
    onSuccess: () => console.log("Settings updated!"),
  });
};
</script>

<template>
  <form @submit.prevent="submit">
    <input v-model="form.settings.site_name" />
    <button :disabled="form.processing">Save</button>
  </form>
</template>
```

## 🔧 Configuration

### Cache Settings

```php
// config/settings.php
'cache' => [
    'enabled' => true,
    'ttl' => 3600, // 1 hour
    'key' => 'settings_cache',
],
```

### Inertia Settings

```php
// config/settings.php
'inertia' => [
    'share_public' => true,      // Auto-share public settings
    'key_name' => 'settings',    // Key name in Inertia props
],
```

## 🧙‍♂️ Advanced Usage

### Type Casting

The package automatically handles type casting based on config definitions:

```php
// Boolean
Setting::create([
    'key' => 'maintenance_mode',
    'value' => true, // Stored as boolean
]);

// JSON/Array
Setting::create([
    'key' => 'features',
    'value' => ['dark_mode' => true, 'notifications' => false],
]);

// Integer
Setting::create([
    'key' => 'posts_per_page',
    'value' => 10,
]);
```

### Custom Repository

```php
use Salehye\Settings\Contracts\SettingsRepositoryInterface;

class MyService
{
    public function __construct(
        protected SettingsRepositoryInterface $settings
    ) {}

    public function doSomething(): void
    {
        $value = $this->settings->get('my_setting');
    }
}
```

### Clear Cache

```php
// Clear cache after bulk updates
Settings::clearCache();

// Reload from database
Settings::reload();
```

## 🧪 Testing

```bash
# Run tests
composer test

# Run tests with coverage
composer test:coverage
```

## 📝 Example: Complete Setup

### 1. Create Settings Seeder

```php
// database/seeders/SettingsSeeder.php
use Salehye\Settings\Models\Setting;

public function run(): void
{
    $settings = [
        ['key' => 'site_name', 'group' => 'general', 'is_public' => true, 'value' => 'My Website'],
        ['key' => 'site_description', 'group' => 'general', 'is_public' => true, 'value' => 'Welcome to my site'],
        ['key' => 'timezone', 'group' => 'general', 'is_public' => false, 'value' => 'Asia/Riyadh'],
        ['key' => 'maintenance_mode', 'group' => 'system', 'is_public' => false, 'value' => false],
        ['key' => 'contact_email', 'group' => 'contact', 'is_public' => true, 'value' => 'info@example.com'],
        ['key' => 'social_twitter', 'group' => 'social', 'is_public' => true, 'value' => ''],
        ['key' => 'social_facebook', 'group' => 'social', 'is_public' => true, 'value' => ''],
    ];

    foreach ($settings as $setting) {
        Setting::firstOrCreate(['key' => $setting['key']], $setting);
    }
}
```

### 2. Run Seeder

```bash
php artisan db:seed --class=SettingsSeeder
```

### 3. Use in Application

```php
// Backend
$siteName = Settings::get('site_name');

// Frontend (Inertia)
const { site_name } = usePage().props.settings;
```

## 📦 Package Structure

```
salehye/settings/
├── config/
│   └── settings.php
├── database/
│   ├── factories/
│   │   └── SettingFactory.php
│   └── migrations/
│       └── create_settings_table.php
├── src/
│   ├── Contracts/
│   │   └── SettingsRepositoryInterface.php
│   ├── Facades/
│   │   └── Settings.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── SettingsController.php
│   │   └── Middleware/
│   │       └── ShareSettingsMiddleware.php
│   ├── Models/
│   │   └── Setting.php
│   ├── Repositories/
│   │   └── SettingsRepository.php
│   ├── Services/
│   │   └── SettingsService.php
│   ├── SettingsManager.php
│   └── SettingsServiceProvider.php
└── tests/
```

## 🧪 Testing

```bash
# Run tests
composer test

# Run tests with coverage
composer test:coverage

# Run linter
composer lint

# Format code
composer format
```

## 🤝 Contributing

Contributions are welcome! Please see our [Contributing Guide](CONTRIBUTING.md) for details.

### Quick Guide

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -am 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Create a Pull Request

### Requirements

- Follow PSR-12 coding standards
- Add tests for new features
- Update documentation as needed
- Ensure all tests pass

## 📄 License

This package is open-sourced software licensed under the [MIT License](LICENSE).

## 🚀 Roadmap

- [ ] Add settings import/export functionality
- [ ] Add settings versioning
- [ ] Add real-time settings sync
- [ ] Add settings presets
- [ ] Add multi-environment support

## 🙏 Support

If you find this package helpful, please consider giving it a ⭐️ on GitHub!

### Support the Development

- [GitHub Sponsors](https://github.com/sponsors/salehye)
- [Buy Me a Coffee](#)
- [PayPal](#)

---

**Built with ❤️ for the Laravel Community**

## 📞 Contact

- **Email**: salehye@example.com
- **GitHub**: [@salehye](https://github.com/salehye)
- **Twitter**: [@salehye](#)

## 🔗 Links

- [Packagist](https://packagist.org/packages/salehye/settings)
- [GitHub](https://github.com/salehye/settings)
- [Laravel News](https://laravel-news.com/)
- [Laravel Docs](https://laravel.com/docs)
