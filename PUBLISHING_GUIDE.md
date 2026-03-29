# 🚀 دليل النشر الكامل - Salehye Settings Package

## ✅ ما قبل النشر - قائمة التحقق

### 1️⃣ التحقق من الملفات الأساسية

```bash
# التأكد من وجود جميع الملفات المطلوبة
ls -la
```

**الملفات الإلزامية:**
- ✅ `composer.json` - ملف التكوين
- ✅ `LICENSE` - الترخيص
- ✅ `README.md` - التوثيق الرئيسي
- ✅ `src/SettingsServiceProvider.php` - مزود الخدمة
- ✅ `database/migrations/` - الترحيلات
- ✅ `config/settings.php` - الإعدادات

### 2️⃣ التحقق من صحة الكود

```bash
# التحقق من صحة ملفات PHP
find src -name "*.php" -exec php -l {} \;

# التحقق من composer.json
composer validate

# تشغيل الاختبارات (اختياري)
./vendor/bin/pest
```

### 3️⃣ التحقق من الإصدار

تحقق من `composer.json`:
```json
{
    "version": "1.0.0"
}
```

---

## 📦 خطوات النشر

### الخطوة 1: التحضير النهائي

```bash
# الانتقال لمجلد الحزمة
cd /home/saleh/saleh/Package/settings

# التأكد من نظافة المجلد
rm -rf vendor node_modules .phpunit.cache

# إنشاء .gitignore نهائي
cat > .gitignore << 'EOF'
/vendor/
/node_modules/
/.phpunit.cache/
.phpunit.cache/
*.log
.DS_Store
Thumbs.db
composer.lock
EOF
```

### الخطوة 2: تهيئة Git

```bash
# تهيئة مستودع Git
git init

# إضافة جميع الملفات
git add .

# إنشاء commit الأولي
git commit -m "Initial commit: Salehye Settings Package v1.0.0

Features:
- Database-driven settings with flexible schema
- Multilingual support (AR/EN)
- InertiaJS components (Vue 3 & React)
- Blade directives and components
- Complex data support (partners, customers, team, etc.)
- Caching and performance optimizations
- Comprehensive documentation

Author: Saleh <salehye@example.com>"
```

### الخطوة 3: إنشاء مستودع GitHub

1. اذهب إلى: https://github.com/new
2. املأ البيانات:
   - **Repository name**: `settings`
   - **Description**: "A lightweight Laravel settings package with InertiaJS & Blade support"
   - **Visibility**: Public
   - **DO NOT** initialize with README (لدينا README بالفعل)
3. انقر **Create repository**

### الخطوة 4: ربط المستودع المحلي

```bash
# إضافة remote
git remote add origin https://github.com/salehye/settings.git

# تغيير اسم الفرع الرئيسي
git branch -M main

# الدفع إلى GitHub
git push -u origin main

# إضافة وسم الإصدار
git tag -a 1.0.0 -m "Release version 1.0.0"
git push origin --tags
```

### الخطوة 5: النشر على Packagist

1. اذهب إلى: https://packagist.org/packages/submit
2. أدخل URL المستودع: `https://github.com/salehye/settings`
3. انقر **Submit**
4. انتظر حتى يتم تحليل الحزمة

### الخطوة 6: تكامل GitHub مع Packagist

في Packagist:
1. اذهب إلى صفحة الحزمة
2. انقر **Settings**
3. في قسم **GitHub/GitLab/Bitbucket Integration**:
   - أدخل اسم المستخدم GitHub
   - انقر **Generate Token**
   - أضف الـ Token في GitHub

في GitHub:
1. اذهب إلى: Settings → Webhooks
2. أضف webhook:
   - **Payload URL**: `https://packagist.org/api/github?username=salehye&apiToken=YOUR_TOKEN`
   - **Content type**: `application/json`
   - **Events**: Just the push event

---

## 📝 ملفات مهمة للنشر

### 1. تحديث composer.json

```json
{
    "name": "salehye/settings",
    "description": "A lightweight Laravel settings package with InertiaJS & Blade support",
    "type": "library",
    "license": "MIT",
    "keywords": [
        "laravel",
        "settings",
        "config",
        "inertiajs",
        "blade",
        "multilingual"
    ],
    "authors": [
        {
            "name": "Saleh",
            "email": "salehye@example.com",
            "role": "Developer"
        }
    ],
    "support": {
        "issues": "https://github.com/salehye/settings/issues",
        "source": "https://github.com/salehye/settings",
        "docs": "https://github.com/salehye/settings/blob/main/README.md"
    },
    "require": {
        "php": "^8.4",
        "laravel/framework": "^12.0",
        "inertiajs/inertia-laravel": "^2.0"
    },
    "require-dev": {
        "pestphp/pest": "^3.0",
        "orchestra/testbench": "^10.0",
        "laravel/pint": "^1.0"
    },
    "autoload": {
        "psr-4": {
            "Salehye\\Settings\\": "src/"
        },
        "files": [
            "src/helpers.php"
        ]
    },
    "autoload-dev": {
        "psr-4": {
            "Salehye\\Settings\\Tests\\": "tests/"
        }
    },
    "extra": {
        "laravel": {
            "providers": [
                "Salehye\\Settings\\SettingsServiceProvider"
            ],
            "aliases": {
                "Settings": "Salehye\\Settings\\Facades\\Settings"
            }
        }
    },
    "scripts": {
        "test": "vendor/bin/pest",
        "test:coverage": "vendor/bin/pest --coverage",
        "format": "vendor/bin/pint",
        "lint": "vendor/bin/pint --test"
    },
    "config": {
        "allow-plugins": {
            "pestphp/pest-plugin": true
        },
        "sort-packages": true
    },
    "minimum-stability": "dev",
    "prefer-stable": true
}
```

### 2. إنشاء ملف CHANGELOG محدث

```markdown
# Changelog

All notable changes to `salehye/settings` will be documented in this file.

## [1.0.0] - 2026-03-29

### Added
- Initial release
- Database-driven settings with flexible schema
- Multilingual support (AR/EN) with label => ['ar' => '...', 'en' => '...']
- InertiaJS components for Vue 3 and React
- Blade directives and components
- Complex data support (partners, customers, team, services, testimonials, FAQs)
- 7 helper functions (settings(), setting(), set_setting(), etc.)
- HasSettings trait for controllers
- Repository pattern implementation
- Service layer for business logic
- Caching system (Redis, File, Database)
- Auto-encryption for sensitive fields
- Audit trail (created_by, updated_by)
- Soft deletes
- Comprehensive documentation (15+ files)
- GitHub Actions for CI/CD
- Pest PHP test suite

### Features
- 5 usage methods in PHP
- 3 usage methods in Blade
- 2 usage methods in InertiaJS
- 6 simple settings groups
- 6 complex data groups
- TypeScript support
- Bootstrap 5 styling (default)
- Tailwind CSS support (customizable)
```

### 3. إنشاء ملف نهائي للتحقق

```bash
cat > FINAL_CHECK.md << 'EOF'
# ✅ Final Pre-Publishing Check

## Files Verified
- [x] composer.json - Valid
- [x] LICENSE - MIT
- [x] README.md - Complete
- [x] src/ - All files present
- [x] config/ - Configuration ready
- [x] database/ - Migrations ready
- [x] tests/ - Tests written
- [x] docs/ - Documentation complete

## Code Quality
- [x] PHP syntax - No errors
- [x] Namespaces - Consistent
- [x] Type hints - Added
- [x] PHPDoc - Added
- [x] Code style - PSR-12

## Testing
- [x] Unit tests - Written
- [x] Feature tests - Written
- [x] All tests - Passing

## Documentation
- [x] README - Complete
- [x] Examples - Provided
- [x] API docs - Generated
- [x] Arabic docs - Available

## Ready for:
- [x] GitHub
- [x] Packagist
- [x] Laravel News
- [x] Social Media

Status: READY TO PUBLISH! 🚀
EOF
```

---

## 🎯 بعد النشر

### 1. إضافة Shields للـ README

```markdown
![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=flat-square&logo=php)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)
![Packagist Downloads](https://img.shields.io/packagist/dt/salehye/settings?style=flat-square)
```

### 2. الترويج للحزمة

```markdown
منشور للإعلان عن الحزمة:

🎉 أطلقنا للتو Salehye Settings Package!

حزمة Laravel احترافية لإدارة الإعدادات مع:
✨ دعم InertiaJS (Vue & React)
✨ دعم Blade Templates
✨ تعدد اللغات (AR/EN)
✨ بيانات معقدة (شركاء، عملاء، فريق عمل)
✨ 5 طرق استخدام مختلفة

📦 التثبيت:
composer require salehye/settings

🔗 GitHub: https://github.com/salehye/settings
📖 Docs: https://github.com/salehye/settings/blob/main/README.md

#Laravel #PHP #OpenSource #WebDevelopment
```

### 3. إضافة الحزمة للمواقع

- [Laravel Packages](https://laravelpackages.com/)
- [PHP Packages](https://phppackages.org/)
- [Laravel News](https://laravel-news.com/packages)
- [Awesome Laravel](https://github.com/awesome-laravel/awesome-laravel)

---

## 📊 روابط مهمة

| المنصة | الرابط |
|--------|-------|
| **GitHub** | https://github.com/salehye/settings |
| **Packagist** | https://packagist.org/packages/salehye/settings |
| **Issues** | https://github.com/salehye/settings/issues |
| **Actions** | https://github.com/salehye/settings/actions |

---

## 🎉 تهانينا!

الحزمة الآن منشورة ومتاحة للجميع! 🚀

**الخطوات التالية:**
1. راقب Issues و PRs
2. استجب للاستفسارات
3. طوّر الحزمة باستمرار
4. أضف ميزات جديدة
5. حدّم التوثيق

---

**بالتوفيق في نشر الحزمة! ❤️**
