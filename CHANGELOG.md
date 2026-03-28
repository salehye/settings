# Changelog

All notable changes to `salehye/settings` will be documented in this file.

## [1.0.0] - 2026-03-28

### Added
- Initial release
- Database-driven settings with `is_public` flag
- Helper functions: `settings()`, `setting()`, `set_setting()`, `settings_group()`, `settings_public()`, `settings_all()`
- `HasSettings` trait for easy use in controllers
- Facade with magic methods for flexible access
- InertiaJS middleware for auto-sharing public settings
- Multilingual support (Arabic & English)
- Built-in caching system
- Comprehensive test suite with Pest PHP
- Config definitions (optional) for metadata
- Type casting support
- Validation support (optional)
- SettingsController for InertiaJS frontend
- Factory for testing
- Full documentation

### Features
- 5 different usage methods for maximum flexibility
- Magic methods for intuitive access (`Settings::$site_name`)
- Invoke syntax (`Settings('key')`)
- Repository pattern for clean architecture
- Service layer for business logic
- Soft deletes support
- Group-based organization
- Composite database indexes for performance

---

## Version Guidelines

This project follows [Semantic Versioning](https://semver.org/).

### Format
- **[Added]** for new features.
- **[Changed]** for changes in existing functionality.
- **[Deprecated]** for soon-to-be removed features.
- **[Removed]** for now removed features.
- **[Fixed]** for any bug fixes.
- **[Security]** in case of vulnerabilities.
