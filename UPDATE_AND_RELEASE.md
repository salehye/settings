# 🔄 دليل تحديث ورفع الباكج بعد التعديلات

## 📋 حالة الباكج: **جاهز للتحديث والنشر**

---

## 🎯 خطوات التحديث (5 دقائق)

### 1️⃣ التحقق من التعديلات

```bash
cd /home/saleh/saleh/Package/settings

# عرض الملفات المعدلة
git status

# عرض التغييرات
git diff
```

### 2️⃣ إضافة التعديلات

```bash
# إضافة جميع الملفات المعدلة
git add .

# أو إضافة ملفات محددة
git add src/ config/ resources/js/
```

### 3️⃣ إنشاء Commit

```bash
git commit -m "feat: Add InertiaJS components and complex data support

New Features:
- Vue 3 and React components for settings management
- Complex data support (partners, customers, team, services, etc.)
- Multilingual labels with ['ar' => '...', 'en' => '...']
- Enhanced SettingsService with form data methods
- EntryManager component for complex data
- TypeScript definitions for all components

Improvements:
- Updated Setting model with extra_data support
- Added 6 new complex data groups
- Improved performance with better indexing
- Enhanced documentation (17+ files)

Breaking Changes:
- Config structure updated for multilingual support
- New database columns added (extra_data, meta, etc.)

Version: 2.0.0"
```

### 4️⃣ تحديث الإصدار (Version Bump)

تحديث `composer.json`:

```json
{
    "version": "2.0.0"
}
```

أو استخدام Semantic Versioning:

```bash
# للتغييرات البسيطة
composer version patch  # 2.0.0 -> 2.0.1

# للميزات الجديدة
composer version minor  # 2.0.0 -> 2.1.0

# للتغييرات الكبيرة
composer version major  # 2.0.0 -> 3.0.0
```

### 5️⃣ إنشاء Tag جديد

```bash
# إنشاء tag
git tag -a 2.0.0 -m "Release version 2.0.0 - InertiaJS & Complex Data Support"

# عرض معلومات الـ tag
git show 2.0.0
```

### 6️⃣ الدفع إلى GitHub

```bash
# دفع الـ commits
git push origin main

# دفع الـ tags
git push origin --tags

# أو دفع كل شيء
git push --all origin
```

### 7️⃣ تحديث Packagist

**تلقائي** (إذا كان webhook مُعد):
- Packagist سيكتشف الـ tag الجديد تلقائياً
- سيتم تحديث الباكج خلال دقائق

**يدوي**:
1. اذهب إلى: https://packagist.org/packages/salehye/settings
2. انقر **Update**
3. انتظر حتى يتم التحليل

---

## 📝 أنواع Commits الموصى بها

### 🎉 ميزة جديدة (Feature)
```bash
git commit -m "feat: Add new feature description

Detailed description of the feature
and why it was added"
```

### 🐛 إصلاح خطأ (Bug Fix)
```bash
git commit -m "fix: Fix bug in SettingsService

Fixed issue where settings were not cached properly
Resolves #123"
```

### 📚 توثيق (Documentation)
```bash
git commit -m "docs: Update README with new examples

Added examples for complex data usage
Updated installation instructions"
```

### ⚡ تحسين أداء (Performance)
```bash
git commit -m "perf: Improve caching performance

Reduced database queries by 50%
Added composite indexes"
```

### 🎨 تنسيق كود (Style)
```bash
git commit -m "style: Format code with Laravel Pint

Applied PSR-12 coding standards
No functional changes"
```

### ♻️ إعادة هيكلة (Refactor)
```bash
git commit -m "refactor: Extract SettingsService from Manager

Separated business logic from facade
Improved testability"
```

### 🧪 إضافة اختبار (Test)
```bash
git commit -m "test: Add tests for SettingsRepository

Added unit tests for all repository methods
Coverage increased to 95%"
```

---

## 🔄 سير العمل الموصى به

### للتحديثات البسيطة

```bash
# 1. سحب آخر التغييرات
git pull origin main

# 2. إجراء التعديلات
# ... edit files ...

# 3. إضافة واختبار
git add .
./vendor/bin/pest

# 4. Commit و Push
git commit -m "fix: Quick fix for issue #123"
git push origin main
```

### للتحديثات الكبيرة

```bash
# 1. إنشاء فرع جديد
git checkout -b feature/new-feature

# 2. إجراء التعديلات
# ... work on feature ...

# 3. Commits متعددة
git add .
git commit -m "feat: Add first part of feature"

git add .
git commit -m "feat: Complete feature implementation"

# 4. دمج مع main
git checkout main
git pull origin main
git merge feature/new-feature

# 5. دفع
git push origin main
git tag -a 2.1.0 -m "Release v2.1.0"
git push origin --tags

# 6. حذف الفرع
git branch -d feature/new-feature
```

---

## 📊 تحديث CHANGELOG.md

```markdown
# Changelog

## [2.0.0] - 2026-03-29

### ✨ Added
- InertiaJS components for Vue 3 and React
- Complex data support (partners, customers, team, services, testimonials, FAQs)
- Multilingual labels with ['ar' => '...', 'en' => '...'] structure
- EntryManager component for managing complex entries
- TypeScript definitions for all components
- 6 new complex data groups in config

### 🚀 Improved
- Setting model now supports extra_data column
- SettingsService enhanced with form data methods
- Database schema extended for better flexibility
- Documentation expanded to 17+ files

### ⚡ Performance
- Added composite indexes for faster queries
- Improved caching mechanism
- Optimized database queries

### 🐛 Fixed
- Fixed issue with multilingual labels
- Fixed caching invalidation problem

### ⚠️ Changed
- Config structure updated for multilingual support
- Database migration added for new columns

### 📦 Dependencies
- Updated to Laravel 12.x
- Updated to InertiaJS 2.x

---

## [1.0.0] - 2026-03-28

### Initial Release
...
```

---

## 🎯 نشر التحديثات

### GitHub Release

1. اذهب إلى: https://github.com/salehye/settings/releases
2. انقر **Draft a new release**
3. اختر الـ tag: `2.0.0`
4. اكتب العنوان: `Version 2.0.0 - InertiaJS Support`
5. أضف المحتوى من CHANGELOG
6. انقر **Publish release**

### Packagist Update

```bash
# التحقق من وجود الـ tag
git tag -l

# إذا لم يتم الدفع
git push origin 2.0.0

# أو دفع كل الـ tags
git push origin --tags
```

### Laravel News

1. اذهب إلى: https://laravel-news.com/packages
2. أضف الباكج
3. انتظر المراجعة

---

## 🔧 أدوات مساعدة

### سكريبت التحديث التلقائي

```bash
#!/bin/bash

# release.sh

VERSION=$1

if [ -z "$VERSION" ]; then
    echo "Usage: ./release.sh <version>"
    echo "Example: ./release.sh 2.0.0"
    exit 1
fi

echo "🚀 Releasing version $VERSION..."

# Update composer.json
echo "Updating composer.json..."
sed -i "s/\"version\": \"[^\"]*\"/\"version\": \"$VERSION\"/" composer.json

# Update CHANGELOG
echo "Updating CHANGELOG..."
# Add your changelog update logic here

# Commit
git add composer.json CHANGELOG.md
git commit -m "Release version $VERSION"

# Tag
git tag -a $VERSION -m "Release version $VERSION"

# Push
git push origin main
git push origin --tags

echo "✅ Released version $VERSION successfully!"
```

الاستخدام:
```bash
chmod +x release.sh
./release.sh 2.0.0
```

---

## ✅ قائمة التحقق قبل الـ Push

### قبل الـ Commit
- [ ] جميع الملفات المعدلة تمت إضافتها
- [ ] الاختبارات تمر بنجاح
- [ ] الكود منسق (phpfmt/pint)
- [ ] لا توجد أخطاء PHP
- [ ] CHANGELOG محدث
- [ ] version في composer.json محدث

### قبل الـ Push
- [ ] Commit message واضح ووصفي
- [ ] Tag تم إنشاؤه
- [ ] Branch صحيح (main/master)
- [ ] لا توجد conflicts

### بعد الـ Push
- [ ] GitHub Actions اكتملت بنجاح
- [ ] Packagist تم تحديثه
- [ ] Release تم إنشاؤه على GitHub
- [ ] التوثيق محدث

---

## 📢 الإعلان عن التحديث

### Twitter/X
```
🎉 Update: Salehye Settings v2.0.0 is here!

New in this version:
✨ InertiaJS components (Vue & React)
✨ Complex data support
✨ Multilingual labels
✨ Better performance

Update now:
composer require salehye/settings:^2.0.0

#Laravel #PHP #WebDev
```

### LinkedIn
```
Excited to announce Salehye Settings Package v2.0.0!

This update brings major improvements:
- InertiaJS components for Vue 3 and React
- Complex data management (partners, customers, team, etc.)
- Enhanced multilingual support
- Performance improvements

Try it now: composer require salehye/settings

#Laravel #PHP #OpenSource #WebDevelopment
```

---

## 🎯 ملخص الخطوات السريعة

```bash
# 1. تعديل الملفات
# ... edit ...

# 2. إضافة
git add .

# 3. اختبار
./vendor/bin/pest

# 4. Commit
git commit -m "feat: New features"

# 5. Tag
git tag -a 2.0.0 -m "Release v2.0.0"

# 6. Push
git push origin main && git push origin --tags

# 7. تحديث Packagist (تلقائي)
```

**الوقت الإجمالي: 2-3 دقائق** ⏱️

---

## 🔗 روابط مفيدة

- [Semantic Versioning](https://semver.org/)
- [Keep a Changelog](https://keepachangelog.com/)
- [GitHub Releases](https://docs.github.com/en/repositories/releasing-projects-on-github)
- [Packagist Documentation](https://packagist.org/apidoc)

---

**جاهز للتحديث والنشر! 🚀**
