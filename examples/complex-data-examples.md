# إدارة البيانات المعقدة (Complex Data Management)

## 📋 نظرة عامة

تدعم الحزمة نوعين من البيانات:

### 1. **الإعدادات البسيطة (Simple Settings)**
- قيم فردية (نص، رقم، نعم/لا)
- مثال: `site_name`, `timezone`, `items_per_page`

### 2. **البيانات المعقدة (Complex Entries)**
- بيانات متعددة السجلات
- مثال: الشركاء، العملاء، فريق العمل، الخدمات، إلخ

---

## 🎯 استخدام البيانات المعقدة

### إضافة شريك جديد

```php
use Salehye\Settings\Models\Setting;

// إضافة شريك
$partner = Setting::create([
    'group' => 'partners',
    'key' => 'partner_' . time(), // مفتاح فريد
    'type' => 'default',
    'is_active' => true,
    'is_featured' => false,
    'order' => 1,
    'extra_data' => [
        'name' => 'شركة التقنية المتقدمة',
        'logo' => '/uploads/partners/logo.png',
        'website' => 'https://example.com',
        'description' => 'شريك استراتيجي',
    ],
    'translations' => [
        'ar' => ['name' => 'شركة التقنية المتقدمة'],
        'en' => ['name' => 'Advanced Technology Co.'],
    ],
]);
```

### جلب جميع الشركاء

```php
// باستخدام Scope
$partners = Setting::partners()->get();

// أو يدوياً
$partners = Setting::group('partners')
    ->active()
    ->ordered()
    ->get();

// جلب الشركاء المميزين فقط
$featuredPartners = Setting::partners()->featured()->get();
```

### إضافة عميل

```php
$customer = Setting::create([
    'group' => 'customers',
    'key' => 'customer_' . $id,
    'type' => 'vip', // يمكن استخدام type للتصنيف
    'is_active' => true,
    'order' => 1,
    'extra_data' => [
        'name' => 'أحمد محمد',
        'company' => 'شركة النجاح',
        'email' => 'ahmed@example.com',
        'phone' => '+966500000000',
        'notes' => 'عميل مميز',
    ],
]);
```

### جلب العملاء

```php
// جميع العملاء
$customers = Setting::customers()->get();

// عملاء VIP
$vipCustomers = Setting::group('customers')
    ->type('vip')
    ->active()
    ->get();
```

### إضافة عضو في فريق العمل

```php
$teamMember = Setting::create([
    'group' => 'team',
    'key' => 'team_' . $id,
    'type' => 'management', // الإدارة
    'is_active' => true,
    'is_featured' => true,
    'order' => 1,
    'extra_data' => [
        'name' => 'محمد أحمد',
        'job_title' => 'مدير تنفيذي',
        'avatar' => '/uploads/team/mohamed.jpg',
        'bio' => 'خبرة 15 سنة في...',
        'email' => 'mohamed@company.com',
    ],
    'translations' => [
        'ar' => [
            'name' => 'محمد أحمد',
            'job_title' => 'مدير تنفيذي',
            'bio' => 'خبرة 15 سنة في...',
        ],
        'en' => [
            'name' => 'Mohamed Ahmed',
            'job_title' => 'CEO',
            'bio' => '15 years of experience in...',
        ],
    ],
]);
```

### جلب فريق العمل

```php
// جميع الأعضاء
$team = Setting::team()->get();

// الإدارة فقط
$management = Setting::group('team')
    ->type('management')
    ->active()
    ->get();

// الأعضاء المميزين
$featuredTeam = Setting::team()->featured()->get();
```

### إضافة خدمة

```php
$service = Setting::create([
    'group' => 'services',
    'key' => 'service_' . $id,
    'type' => 'premium',
    'is_active' => true,
    'is_featured' => true,
    'order' => 1,
    'extra_data' => [
        'name' => 'تطوير المواقع',
        'description' => 'نقدم خدمات تطوير مواقع متكاملة',
        'price' => 5000,
        'currency' => 'SAR',
        'duration' => '3 أسابيع',
    ],
    'translations' => [
        'ar' => [
            'name' => 'تطوير المواقع',
            'description' => 'نقدم خدمات تطوير مواقع متكاملة',
        ],
        'en' => [
            'name' => 'Web Development',
            'description' => 'We provide complete web development services',
        ],
    ],
]);
```

### إضافة رأي عميل

```php
$testimonial = Setting::create([
    'group' => 'testimonials',
    'key' => 'testimonial_' . $id,
    'is_active' => true,
    'is_featured' => true,
    'order' => 1,
    'extra_data' => [
        'client_name' => 'خالد علي',
        'client_title' => 'مدير شركة...',
        'avatar' => '/uploads/testimonials/khaled.jpg',
        'rating' => 5,
        'testimonial' => 'خدمة ممتازة وأنصح بالتعامل معهم...',
    ],
]);
```

### إضافة سؤال شائع

```php
$faq = Setting::create([
    'group' => 'faqs',
    'key' => 'faq_' . $id,
    'type' => 'general', // category
    'is_active' => true,
    'order' => 1,
    'extra_data' => [
        'question' => 'كيف يمكنني الطلب؟',
        'answer' => 'يمكنك الطلب من خلال...',
        'category' => 'general',
    ],
    'translations' => [
        'ar' => [
            'question' => 'كيف يمكنني الطلب؟',
            'answer' => 'يمكنك الطلب من خلال...',
        ],
        'en' => [
            'question' => 'How can I order?',
            'answer' => 'You can order through...',
        ],
    ],
]);
```

---

## 🔍 عمليات البحث المتقدمة

```php
// جلب الشركاء النشطين فقط
$partners = Setting::group('partners')
    ->where('is_active', true)
    ->orderBy('order')
    ->get();

// جلب الخدمات المميزة
$featuredServices = Setting::group('services')
    ->where('is_featured', true)
    ->active()
    ->ordered()
    ->get();

// جلب آراء العملاء حسب التقييم
$testimonials = Setting::group('testimonials')
    ->active()
    ->whereJsonExtract('extra_data', '$.rating')
    ->orderByDesc('extra_data->rating')
    ->get();

// البحث في العملاء
$customers = Setting::group('customers')
    ->whereJsonContains('extra_data', ['company' => 'شركة النجاح'])
    ->get();
```

---

## 📊 الإحصائيات

```php
// عدد الشركاء
$partnersCount = Setting::group('partners')->active()->count();

// عدد العملاء
$customersCount = Setting::group('customers')->active()->count();

// عدد أعضاء الفريق
$teamCount = Setting::group('team')->active()->count();

// عدد الخدمات
$servicesCount = Setting::group('services')->active()->count();

// متوسط تقييم العملاء
$averageRating = Setting::group('testimonials')
    ->active()
    ->get()
    ->avg(fn($t) => $t->getExtraField('rating', 0));
```

---

## 🎨 الاستخدام في Blade

```blade
{{-- عرض الشركاء --}}
@foreach(\Salehye\Settings\Models\Setting::partners()->get() as $partner)
    <div class="partner">
        <img src="{{ $partner->getExtraField('logo') }}" alt="{{ $partner->getExtraField('name') }}">
        <h3>{{ $partner->getExtraField('name') }}</h3>
        @if($partner->getExtraField('website'))
            <a href="{{ $partner->getExtraField('website') }}" target="_blank">Visit</a>
        @endif
    </div>
@endforeach

{{-- عرض فريق العمل --}}
@foreach(\Salehye\Settings\Models\Setting::team()->get() as $member)
    <div class="team-member">
        <img src="{{ $member->getExtraField('avatar') }}" alt="{{ $member->getExtraField('name') }}">
        <h3>{{ $member->getExtraField('name') }}</h3>
        <p>{{ $member->getExtraField('job_title') }}</p>
        <p>{{ $member->getExtraField('bio') }}</p>
        <a href="mailto:{{ $member->getExtraField('email') }}">Contact</a>
    </div>
@endforeach

{{-- عرض الخدمات --}}
@foreach(\Salehye\Settings\Models\Setting::services()->featured()->get() as $service)
    <div class="service featured">
        <h3>{{ $service->getExtraField('name') }}</h3>
        <p>{{ $service->getExtraField('description') }}</p>
        <span class="price">{{ $service->getExtraField('price') }} {{ $service->getExtraField('currency', 'SAR') }}</span>
    </div>
@endforeach

{{-- عرض آراء العملاء --}}
@foreach(\Salehye\Settings\Models\Setting::testimonials()->featured()->get() as $testimonial)
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

{{-- عرض الأسئلة الشائعة --}}
@foreach(\Salehye\Settings\Models\Setting::faqs()->get() as $faq)
    <div class="faq">
        <h3>{{ $faq->getExtraField('question') }}</h3>
        <p>{{ $faq->getExtraField('answer') }}</p>
    </div>
@endforeach
```

---

## 🌐 تعدد اللغات

```php
// الحصول على البيانات باللغة الحالية
$partner = Setting::partners()->first();
$name = $partner->getEntryData()['name']; // سيستخدم اللغة الحالية

// الحصول على البيانات بلغة محددة
$nameAr = $partner->getEntryData('ar')['name'];
$nameEn = $partner->getEntryData('en')['name'];
```

---

## 🔧 التحديث والحذف

```php
// تحديث شريك
$partner = Setting::partners()->first();
$partner->update([
    'extra_data' => [
        'name' => 'الاسم الجديد',
        'website' => 'https://newwebsite.com',
    ],
]);

// حذف شريك
$partner->delete();

// حذف جميع الشركاء
Setting::group('partners')->delete();
```

---

**ملاحظة:** جميع العمليات تدعم الـ caching التلقائي للأداء العالي! ⚡
