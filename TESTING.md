# Testing Guide

## Running Tests

This package uses [Pest PHP](https://pestphp.com) for testing.

### Prerequisites

- PHP 8.4+
- Composer
- SQLite extension

### Install Dependencies

```bash
composer install
```

### Run All Tests

```bash
./vendor/bin/pest
```

### Run Specific Test File

```bash
./vendor/bin/pest tests/Unit/SettingTest.php
```

### Run Tests with Coverage

```bash
./vendor/bin/pest --coverage
```

### Run Tests with Output

```bash
./vendor/bin/pest --verbose
```

## Test Structure

### Unit Tests
Located in `tests/Unit/`:
- `SettingTest.php` - Model tests
- `SettingsManagerTest.php` - Manager tests
- `SettingsRepositoryTest.php` - Repository tests
- `SettingsHelpersTest.php` - Helper functions tests
- `HasSettingsTraitTest.php` - Trait tests
- `BladeDirectivesTest.php` - Blade tests

### Feature Tests
Located in `tests/Feature/`:
- `SettingsApiTest.php` - API endpoint tests
- `SettingsMiddlewareTest.php` - Middleware tests

## Writing Tests

### Example Unit Test

```php
<?php

namespace Salehye\Settings\Tests;

use Salehye\Settings\Models\Setting;

class SettingTest extends TestCase
{
    public function test_can_create_setting(): void
    {
        $setting = Setting::create([
            'key' => 'test_key',
            'group' => 'general',
            'is_public' => true,
            'value' => 'test_value',
        ]);

        $this->assertEquals('test_key', $setting->key);
        $this->assertEquals('test_value', $setting->value);
    }
}
```

### Example Pest Test

```php
<?php

use Salehye\Settings\Models\Setting;

it('can create a setting', function () {
    $setting = Setting::create([
        'key' => 'test_key',
        'group' => 'general',
        'is_public' => true,
        'value' => 'test_value',
    ]);

    expect($setting->key)->toBe('test_key');
    expect($setting->value)->toBe('test_value');
});
```

## Troubleshooting

### SQLite Extension

If you get SQLite errors, install the extension:

```bash
# Ubuntu/Debian
sudo apt-get install php-sqlite3

# CentOS/RHEL
sudo yum install php-pdo

# macOS (Homebrew)
brew install php
```

### Memory Issues

If tests fail due to memory, increase PHP memory limit:

```bash
php -d memory_limit=512M vendor/bin/pest
```

### Database Errors

Clear test database:

```bash
rm -rf database/database.sqlite
```

## CI/CD

Tests are automatically run on GitHub Actions on every push and pull request.

See `.github/workflows/tests.yml` for configuration.

## Code Coverage

Generate code coverage report:

```bash
./vendor/bin/pest --coverage --coverage-clover=coverage.xml
```

View HTML coverage:

```bash
./vendor/bin/pest --coverage --coverage-html=coverage
open coverage/index.html
```

## Testing with Laravel

To test the package in a Laravel application:

1. Install the package
2. Publish assets
3. Run migrations
4. Use the package normally in your Laravel tests

```php
// In your Laravel test
public function test_settings_work_in_app(): void
{
    Settings::set('site_name', 'Test Site');
    
    $this->assertEquals('Test Site', Settings::get('site_name'));
}
```
