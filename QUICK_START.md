# 🎯 دليل الاستخدام السريع - Salehye Settings

## 📦 التثبيت السريع

```bash
composer require salehye/settings
php artisan vendor:publish --provider="Salehye\Settings\SettingsServiceProvider"
php artisan migrate
```

---

## 🚀 الاستخدام في 3 خطوات

### 1️⃣ في Backend (PHP)

```php
use Salehye\Settings\Facades\Settings;

// تعيين إعداد
Settings::set('site_name', 'موقعي');

// الحصول على إعداد
$siteName = Settings::get('site_name');

// تعيين قيم متعددة
Settings::setMany([
    'general.site_name' => 'موقعي',
    'general.timezone' => 'Asia/Riyadh',
]);
```

### 2️⃣ في Blade Templates

```blade
{{-- الحصول على قيمة —}}
<h1>@settings('general', 'site_name')</H1>

{{-- عرض الشركاء —}}
@foreach(Setting::partners()->get() as $partner)
    <img src="{{ $partner->getExtraField('logo') }}">
    <h3>{{ $partner->getExtraField('name') }}</h3>
@endforeach
```

### 3️⃣ في InertiaJS (Vue/React)

```vue
<!-- Vue 3 -->
<script setup>
import { useSettings } from 'salehye/settings/resources/js/vue/composables/useSettings';

const { get, set } = useSettings();
const siteName = get('site_name');
</script>
```

```tsx
// React
import { useSettings } from 'salehye/settings/resources/js/react/composables/useSettings';

const { get, set } = useSettings();
const siteName = get('site_name');
```

---

## 📊 أنواع البيانات

### 1. الإعدادات البسيطة

```php
// قيمة واحدة
Setting::create([
    'group' => 'general',
    'key' => 'site_name',
    'value' => 'موقعي',
]);
```

### 2. البيانات المعقدة (Entries)

```php
// شركاء، عملاء، فريق عمل، إلخ
Setting::create([
    'group' => 'partners',
    'key' => 'partner_1',
    'extra_data' => [
        'name' => 'شركة التقنية',
        'logo' => '/uploads/logo.png',
        'website' => 'https://example.com',
    ],
]);
```

---

## 🎨 مكونات InertiaJS الجاهزة

### للمشاريع Vue 3:

```vue
<template>
    <SettingsGroup
        :group="groupConfig"
        :values="formValues"
        @update="saveSettings"
    />
</template>

<script setup>
import SettingsGroup from 'salehye/settings/resources/js/vue/components/SettingsGroup.vue';
</script>
```

### للمشاريع React:

```tsx
<SettingsGroup
    group={groupConfig}
    values={formValues}
    onSave={saveSettings}
/>

import SettingsGroup from 'salehye/settings/resources/js/react/components/SettingsGroup';
```

---

## 📋 المجموعات الجاهزة

### الإعدادات البسيطة:
- ✅ `general` - عامة
- ✅ `contact` - الاتصال
- ✅ `social` - التواصل الاجتماعي
- ✅ `seo` - تحسين محركات البحث
- ✅ `mail` - البريد
- ✅ `analytics` - التحليلات
- ✅ `payment` - الدفع

### البيانات المعقدة:
- ✅ `partners` - الشركاء
- ✅ `customers` - العملاء
- ✅ `team` - فريق العمل
- ✅ `services` - الخدمات
- ✅ `testimonials` - آراء العملاء
- ✅ `faqs` - الأسئلة الشائعة

---

## 🔧 أمثلة عملية

### إضافة شريك:

```php
Setting::create([
    'group' => 'partners',
    'key' => 'partner_1',
    'is_active' => true,
    'extra_data' => [
        'name' => 'شركة التقنية',
        'logo' => '/uploads/logo.png',
        'website' => 'https://example.com',
    ],
]);
```

### جلب الشركاء:

```php
// في Controller
$partners = Setting::partners()->get();

// في Blade
@foreach(Setting::partners()->active()->get() as $partner)
    <div class="partner">
        <img src="{{ $partner->getExtraField('logo') }}">
        <h3>{{ $partner->getExtraField('name') }}</h3>
    </div>
@endforeach
```

### في InertiaJS:

```vue
<script setup>
const partners = computed(() => 
    Setting.partners().active().get()
);
</script>

<template>
    <div v-for="partner in partners" :key="partner.id">
        <img :src="partner.extra_data.logo">
        <h3>{{ partner.extra_data.name }}</h3>
    </div>
</template>
```

---

## 📚 التوثيق الكامل

| الملف | الوصف |
|-------|-------|
| [README.md](README.md) | التوثيق الرئيسي |
| [README_ADVANCED.md](README_ADVANCED.md) | البيانات المعقدة |
| [INERTIA_COMPONENTS.md](INERTIA_COMPONENTS.md) | مكونات InertiaJS |
| [README_INERTIA_AR.md](README_INERTIA_AR.md) | دليل InertiaJS بالعربي |
| [examples/complex-data-examples.md](examples/complex-data-examples.md) | أمثلة البيانات المعقدة |
| [examples/blade-examples.md](examples/blade-examples.md) | أمثلة Blade |

---

## 🎯 الروابط السريعة

```php
// الحصول على جميع الإعدادات
Settings::all();

// الحصول على إعدادات مجموعة
Settings::group('general');

// الحصول على الإعدادات العامة فقط
Settings::public();

// حذف إعداد
Settings::delete('key');

// مسح الكاش
Settings::clearCache();
```

---

## ⚡ المميزات الرئيسية

| الميزة | الوصف |
|--------|-------|
| 🗄️ **قاعدة بيانات** | جدول واحد مرن |
| 🌐 **تعدد اللغات** | دعم كامل AR/EN |
| ⚡ **Caching** | أداء عالي |
| 🎨 **InertiaJS** | مكونات Vue & React |
| 🔧 **Blade** | دعم كامل لـ Blade |
| 📦 **جاهز** | 13+ مجموعة إعدادات |
| 🧪 **اختبارات** | تغطية شاملة |

---

**تم التطوير بواسطة Salehye ❤️**
