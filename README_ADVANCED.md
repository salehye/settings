# Salehye Settings Package - الإصدار المتقدم

> **نظام إعدادات متكامل يدعم البيانات البسيطة والمعقدة مع تعدد اللغات**

## ✨ المميزات الجديدة

### 🎯 نوعان من البيانات:

#### 1. **الإعدادات البسيطة (Simple Settings)**
```php
// قيم فردية
Settings::set('site_name', 'موقعي');
Settings::get('timezone');
```

#### 2. **البيانات المعقدة (Complex Entries)** ⭐ جديد!
```php
// بيانات متعددة السجلات
Setting::partners()->get();      // الشركاء
Setting::customers()->get();     // العملاء
Setting::team()->get();          // فريق العمل
Setting::services()->get();      // الخدمات
Setting::testimonials()->get();  // آراء العملاء
Setting::faqs()->get();          // الأسئلة الشائعة
```

---

## 📊 هيكلية قاعدة البيانات

### جدول واحد مرن `settings`:

| العمود | الوصف |
|--------|-------|
| `id` | المفتاح الأساسي |
| `group` | المجموعة (general, partners, customers, etc.) |
| `key` | المفتاح (site_name, أو معرف فريد للبيانات المعقدة) |
| `type` | النوع للتصنيف الداخلي |
| `value` | القيمة البسيطة (JSON) |
| `translations` | الترجمات المتعددة (JSON) |
| `meta` | بيانات وصفية إضافية (JSON) |
| `extra_data` | **للبيانات المعقدة** (JSON) |
| `is_public` | هل هو عام |
| `is_active` | هل هو نشط |
| `is_featured` | هل هو مميز |
| `order` | الترتيب |
| `published_at` | تاريخ النشر |
| `created_by` | من أنشأه |
| `updated_by` | من عدله آخر مرة |

---

## 🚀 البدء السريع

### تثبيت الحزمة

```bash
composer require salehye/settings
php artisan vendor:publish --provider="Salehye\Settings\SettingsServiceProvider"
php artisan migrate
```

### الاستخدام الأساسي

```php
// الإعدادات البسيطة
Settings::set('site_name', 'موقعي');
echo Settings::get('site_name');

// البيانات المعقدة
$partners = Setting::partners()->get();
foreach($partners as $partner) {
    echo $partner->getExtraField('name');
}
```

---

## 📖 التوثيق الكامل

### الإعدادات البسيطة
- راجع [README.md](README.md) للوثائق الكاملة

### البيانات المعقدة
- راجع [examples/complex-data-examples.md](examples/complex-data-examples.md)

---

## 🎯 أمثلة سريعة

### إضافة شريك

```php
Setting::create([
    'group' => 'partners',
    'key' => 'partner_1',
    'is_active' => true,
    'order' => 1,
    'extra_data' => [
        'name' => 'شركة التقنية',
        'logo' => '/uploads/logo.png',
        'website' => 'https://example.com',
    ],
]);
```

### جلب الشركاء

```php
// في Controller
$partners = Setting::partners()->get();

// في Blade
@foreach(Setting::partners()->get() as $partner)
    <img src="{{ $partner->getExtraField('logo') }}">
    <h3>{{ $partner->getExtraField('name') }}</h3>
@endforeach
```

### إضافة عميل

```php
Setting::create([
    'group' => 'customers',
    'key' => 'customer_1',
    'extra_data' => [
        'name' => 'أحمد محمد',
        'company' => 'شركة النجاح',
        'email' => 'ahmed@example.com',
        'notes' => 'عميل مميز',
    ],
]);
```

### إضافة عضو فريق

```php
Setting::create([
    'group' => 'team',
    'key' => 'team_1',
    'is_featured' => true,
    'extra_data' => [
        'name' => 'محمد أحمد',
        'job_title' => 'مدير تنفيذي',
        'avatar' => '/uploads/mohamed.jpg',
        'bio' => 'خبرة 15 سنة...',
        'email' => 'mohamed@company.com',
    ],
    'translations' => [
        'ar' => ['name' => 'محمد أحمد', 'job_title' => 'مدير تنفيذي'],
        'en' => ['name' => 'Mohamed Ahmed', 'job_title' => 'CEO'],
    ],
]);
```

---

## 🌐 تعدد اللغات

### في الإعدادات البسيطة

```php
// Config
'site_name' => [
    'label' => [
        'ar' => 'اسم الموقع',
        'en' => 'Site Name',
    ],
],
```

### في البيانات المعقدة

```php
Setting::create([
    'group' => 'team',
    'extra_data' => [
        'name' => 'محمد',
        'job_title' => 'مدير',
    ],
    'translations' => [
        'ar' => ['name' => 'محمد', 'job_title' => 'مدير'],
        'en' => ['name' => 'Mohamed', 'job_title' => 'Manager'],
    ],
]);

// الاستخدام
$member->getEntryData('ar'); // البيانات بالعربية
$member->getEntryData('en'); // البيانات بالإنجليزية
```

---

## 🔍 البحث المتقدم

```php
// الشركاء النشطين فقط
Setting::partners()->active()->get();

// الخدمات المميزة
Setting::services()->featured()->get();

// فريق الإدارة
Setting::group('team')->type('management')->get();

// العملاء حسب الشركة
Setting::group('customers')
    ->whereJsonContains('extra_data', ['company' => 'شركة النجاح'])
    ->get();

// آراء العملاء مرتبة حسب التقييم
Setting::testimonials()
    ->orderByDesc('extra_data->rating')
    ->get();
```

---

## 📦 المجموعات المتوفرة

### الإعدادات البسيطة:
- `general` - الإعدادات العامة
- `contact` - معلومات الاتصال
- `social` - وسائل التواصل
- `seo` - تحسين محركات البحث
- `mail` - إعدادات البريد
- `analytics` - التحليلات
- `payment` - بوابات الدفع

### البيانات المعقدة (Entries):
- `partners` - الشركاء
- `customers` - العملاء
- `team` - فريق العمل
- `services` - الخدمات
- `testimonials` - آراء العملاء
- `faqs` - الأسئلة الشائعة
- `products` - المنتجات (قابلة للإضافة)

---

## 🎨 الاستخدام في Blade

```blade
{{-- الإعدادات البسيطة —}}
<h1>@settings('general', 'site_name')</H1>

{{-- الشركاء —}}
@foreach(Setting::partners()->active()->ordered()->get() as $partner)
    <div class="partner">
        <img src="{{ $partner->getExtraField('logo') }}" alt="{{ $partner->getExtraField('name') }}">
        <h3>{{ $partner->getExtraField('name') }}</h3>
        @if($partner->is_featured)
            <span class="badge">مميز</span>
        @endif
    </div>
@endforeach

{{-- فريق العمل —}}
@foreach(Setting::team()->featured()->active()->get() as $member)
    <div class="team-member">
        <img src="{{ $member->getExtraField('avatar') }}">
        <h3>{{ $member->getExtraField('name') }}</h3>
        <p>{{ $member->getExtraField('job_title') }}</p>
        <a href="mailto:{{ $member->getExtraField('email') }}">تواصل</a>
    </div>
@endforeach

{{-- الخدمات —}}
@foreach(Setting::services()->active()->ordered()->get() as $service)
    <div class="service {{ $service->is_featured ? 'featured' : '' }}">
        <h3>{{ $service->getExtraField('name') }}</h3>
        <p>{{ $service->getExtraField('description') }}</p>
        <span class="price">
            {{ $service->getExtraField('price') }} 
            {{ $service->getExtraField('currency', 'SAR') }}
        </span>
    </div>
@endforeach

{{-- آراء العملاء —}}
@foreach(Setting::testimonials()->featured()->get() as $testimonial)
    <div class="testimonial">
        <div class="rating">
            @for($i = 0; $i < $testimonial->getExtraField('rating', 0); $i++)
                ⭐
            @endfor
        </div>
        <p>{{ $testimonial->getExtraField('testimonial') }}</p>
        <cite>{{ $testimonial->getExtraField('client_name') }}</cite>
    </div>
@endforeach

{{-- الأسئلة الشائعة —}}
@foreach(Setting::faqs()->active()->ordered()->get() as $faq)
    <div class="faq">
        <h3>{{ $faq->getExtraField('question') }}</h3>
        <p>{{ $faq->getExtraField('answer') }}</p>
    </div>
@endforeach
```

---

## ⚙️ التكوين

### إضافة مجموعة جديدة

```php
// في config/settings.php
'products' => [
    'label' => [
        'ar' => 'المنتجات',
        'en' => 'Products',
    ],
    'icon' => 'fas fa-box',
    'order' => 14,
    'is_entry' => true,
    'entry_fields' => [
        'name' => [
            'type' => 'text',
            'label' => ['ar' => 'اسم المنتج', 'en' => 'Product Name'],
            'required' => true,
            'is_translatable' => true,
        ],
        'price' => [
            'type' => 'number',
            'label' => ['ar' => 'السعر', 'en' => 'Price'],
            'required' => true,
        ],
        // ... المزيد من الحقول
    ],
],
```

### إضافة Scope مخصص

```php
// في Model Setting
public function scopeProducts(
    \Illuminate\Database\Eloquent\Builder $query
): \Illuminate\Database\Eloquent\Builder {
    return $query->group('products')->active()->ordered();
}
```

---

## 🔧 التحديث والحذف

```php
// تحديث
$partner = Setting::partners()->first();
$partner->update([
    'extra_data' => [
        'name' => 'الاسم الجديد',
        'website' => 'https://new.com',
    ],
]);

// حذف
$partner->delete();

// حذف مجموعة كاملة
Setting::group('partners')->delete();
```

---

## 📊 الإحصائيات

```php
// عدد الشركاء
$partnersCount = Setting::partners()->count();

// عدد العملاء
$customersCount = Setting::customers()->count();

// عدد أعضاء الفريق
$teamCount = Setting::team()->count();

// متوسط التقييم
$avgRating = Setting::testimonials()
    ->get()
    ->avg(fn($t) => $t->getExtraField('rating', 0));
```

---

## 🎯 أفضل الممارسات

### ✅ افعل:
- استخدم `extra_data` للبيانات المعقدة
- استخدم `translations` للنصوص المتعددة اللغات
- استخدم `is_featured` لتمييز العناصر المهمة
- استخدم `order` للترتيب اليدوي
- استخدم `is_active` لتفعيل/إلغاء العناصر

### ❌ لا تفعل:
- لا تستخدم `value` للبيانات المعقدة
- لا تخزن ملفات في `extra_data` (احفظ المسار فقط)
- لا تنسى إضافة `index` للاستعلامات المتكررة

---

## 📚 روابط مفيدة

- [البيانات المعقدة - أمثلة مفصلة](examples/complex-data-examples.md)
- [أمثلة Blade](examples/blade-examples.md)
- [دليل الاختبارات](TESTING.md)
- [دليل النشر](PUBLISHING_CHECKLIST.md)

---

## 📄 الترخيص

MIT License - راجع [LICENSE](LICENSE) للتفاصيل.

---

**تم التطوير بواسطة Salehye ❤️**
