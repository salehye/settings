# Salehye Settings

> Professional Laravel Settings Package

## Quick Installation

```bash
composer require salehye/settings
php artisan vendor:publish --provider="Salehye\Settings\SettingsServiceProvider"
php artisan migrate
```

## Usage

### In PHP
```php
// Get
$siteName = settings('site_name');

// Set
set_setting('site_name', 'My Site');
```

### In Blade
```blade
@settings('site_name')
<x-setting key="timezone" />
```

### In InertiaJS
```javascript
const { site_name } = usePage().props.settings;
```

## Documentation

See the [full documentation](README.md) for complete usage guide.

## Features

- ✅ Database-driven settings
- ✅ Blade templates support
- ✅ InertiaJS integration
- ✅ Multilingual (AR/EN)
- ✅ Built-in caching
- ✅ 5 usage methods
- ✅ Comprehensive tests

## Support

- 📧 Email: salehye@example.com
- 🐛 Issues: [GitHub Issues](https://github.com/salehye/settings/issues)
- 📖 Docs: [README.md](README.md)

## License

MIT License - see [LICENSE](LICENSE) for details.
