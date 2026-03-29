# InertiaJS Components Documentation

## 📦 تثبيت المكونات

### للمشاريع التي تستخدم Vue 3:

```bash
npm install
```

ثم استخدم المكونات من المسار:
```typescript
import { useSettings } from 'salehye/settings/resources/js/vue/composables/useSettings';
import SettingsGroup from 'salehye/settings/resources/js/vue/components/SettingsGroup.vue';
import FormField from 'salehye/settings/resources/js/vue/components/FormField.vue';
```

### للمشاريع التي تستخدم React:

```bash
npm install
```

ثم استخدم المكونات من المسار:
```typescript
import { useSettings } from 'salehye/settings/resources/js/react/composables/useSettings';
import SettingsGroup from 'salehye/settings/resources/js/react/components/SettingsGroup';
import FormField from 'salehye/settings/resources/js/react/components/FormField';
```

---

## 🎯 الاستخدام الأساسي

### Vue 3 Example

```vue
<script setup lang="ts">
import { useSettings } from 'salehye/settings/resources/js/vue/composables/useSettings';
import SettingsGroup from 'salehye/settings/resources/js/vue/components/SettingsGroup.vue';

const { get, set, loading, error } = useSettings();

// Get a setting
const siteName = get('site_name');

// Set a setting
await set('site_name', 'My New Site');
</script>

<template>
    <div>
        <h1>{{ get('site_name') }}</h1>
        
        <SettingsGroup
            :group="groupConfig"
            :values="formValues"
            @update="saveSettings"
        />
    </div>
</template>
```

### React Example

```tsx
import React from 'react';
import { useSettings } from 'salehye/settings/resources/js/react/composables/useSettings';
import SettingsGroup from 'salehye/settings/resources/js/react/components/SettingsGroup';

const MyComponent: React.FC = () => {
    const { get, set, loading, error } = useSettings();

    // Get a setting
    const siteName = get('site_name');

    // Set a setting
    await set('site_name', 'My New Site');

    return (
        <div>
            <h1>{siteName}</h1>
            
            <SettingsGroup
                group={groupConfig}
                values={formValues}
                onUpdate={setFormValues}
                onSave={saveSettings}
            />
        </div>
    );
};
```

---

## 📋 المكونات المتوفرة

### 1. FormField

مكون لإدخال البيانات يدعم جميع أنواع الحقول.

#### Props:

| الاسم | النوع | الافتراضي | الوصف |
|-------|-------|----------|-------|
| `value` | any | `''` | قيمة الحقل |
| `onChange` | function | - | دالة التحديث (React) |
| `modelValue` | any | `''` | قيمة الحقل (Vue) |
| `type` | string | `'text'` | نوع الحقل |
| `label` | string | - | تسمية الحقل |
| `description` | string | `''` | وصف الحقل |
| `placeholder` | string | `''` | نص مؤقت |
| `rules` | string[] | `[]` | قواعد التحقق |
| `error` | string | `''` | رسالة الخطأ |
| `disabled` | boolean | `false` | تعطيل الحقل |
| `required` | boolean | `false` | مطلوب |
| `options` | object | `{}` | خيارات (للـ select) |
| `min` | number/string | `null` | الحد الأدنى |
| `max` | number/string | `null` | الحد الأقصى |
| `step` | number | `1` | الخطوة |
| `rows` | number | `3` | عدد الأسطر (textarea) |
| `accept` | string | `''` | أنواع الملفات المقبولة |
| `icon` | string | `''` | أيقونة |
| `prepend` | string | `''` | نص قبل الحقل |
| `append` | string | `''` | نص بعد الحقل |

#### الأنواع المدعومة:

- `text` - نص عادي
- `email` - بريد إلكتروني
- `tel` - هاتف
- `url` - رابط
- `password` - كلمة مرور
- `number` - رقم
- `textarea` - نص متعدد الأسطر
- `select` - قائمة منسدلة
- `boolean` / `checkbox` - مربع اختيار
- `tags` - وسوم
- `image` / `file` - رفع ملفات
- `color` - منتقي الألوان
- `date` - تاريخ
- `time` - وقت
- `datetime` - تاريخ ووقت

#### مثال Vue:

```vue
<template>
    <FormField
        v-model="form.site_name"
        type="text"
        label="اسم الموقع"
        description="الاسم الذي يظهر في الموقع"
        placeholder="أدخل اسم الموقع"
        :rules="['required', 'max:255']"
        :error="errors.site_name"
        icon="fas fa-globe"
    />
</template>
```

#### مثال React:

```tsx
<FormField
    value={form.site_name}
    onChange={(value) => setForm({ ...form, site_name: value })}
    type="text"
    label="اسم الموقع"
    description="الاسم الذي يظهر في الموقع"
    placeholder="أدخل اسم الموقع"
    rules={['required', 'max:255']}
    error={errors.site_name}
    icon="fas fa-globe"
/>
```

---

### 2. SettingsGroup

مكون لعرض مجموعة من الحقول الإعدادات.

#### Props:

| الاسم | النوع | الافتراضي | الوصف |
|-------|-------|----------|-------|
| `group` | object | - | تكوين المجموعة |
| `values` | object | `{}` | القيم الحالية |
| `errors` | object | `{}` | الأخطاء |
| `readonly` | boolean | `false` | للقراءة فقط |
| `onUpdate` | function | - | تحديث القيم (Vue) |
| `onSave` | function | - | حفظ التغييرات |

#### مثال Vue:

```vue
<template>
    <SettingsGroup
        :group="generalGroup"
        :values="formValues"
        :errors="errors"
        @update:model-value="formValues = $event"
        @update="saveSettings"
    />
</template>

<script setup>
const generalGroup = {
    label: { ar: 'الإعدادات العامة', en: 'General Settings' },
    icon: 'fas fa-cog',
    fields: {
        site_name: {
            type: 'text',
            label: { ar: 'اسم الموقع', en: 'Site Name' },
            rules: ['required'],
        },
        timezone: {
            type: 'select',
            label: { ar: 'المنطقة الزمنية', en: 'Timezone' },
            options: {
                'Asia/Riyadh': 'الرياض',
                'UTC': 'UTC',
            },
        },
    },
};
</script>
```

#### مثال React:

```tsx
<SettingsGroup
    group={generalGroup}
    values={formValues}
    errors={errors}
    onUpdate={setFormValues}
    onSave={saveSettings}
/>

const generalGroup = {
    label: { ar: 'الإعدادات العامة', en: 'General Settings' },
    icon: 'fas fa-cog',
    fields: {
        site_name: {
            type: 'text',
            label: { ar: 'اسم الموقع', en: 'Site Name' },
            rules: ['required'],
        },
        timezone: {
            type: 'select',
            label: { ar: 'المنطقة الزمنية', en: 'Timezone' },
            options: {
                'Asia/Riyadh': 'الرياض',
                'UTC': 'UTC',
            },
        },
    },
};
```

---

### 3. useSettings Hook/Composable

إدارة الإعدادات بسهولة.

#### Methods:

| الاسم | المعاملات | الإرجاع | الوصف |
|-------|----------|--------|-------|
| `get` | key, default | any | الحصول على قيمة |
| `getAll` | - | object | كل الإعدادات |
| `getGroup` | groupName | object | إعدادات المجموعة |
| `set` | key, value | Promise<boolean> | تعيين قيمة |
| `setMany` | settings | Promise<object> | تعيين قيم متعددة |
| `refresh` | - | Promise<object> | تحديث الإعدادات |

#### مثال Vue:

```vue
<script setup lang="ts">
import { useSettings } from 'salehye/settings/resources/js/vue/composables/useSettings';

const { loading, error, success, get, set, setMany } = useSettings();

// Get
const siteName = get('site_name', 'Default Name');

// Set
await set('site_name', 'New Name');

// Set many
await setMany({
    'general.site_name': 'My Site',
    'general.timezone': 'Asia/Riyadh',
});
</script>
```

#### مثال React:

```tsx
import { useSettings } from 'salehye/settings/resources/js/react/composables/useSettings';

function MyComponent() {
    const { loading, error, success, get, set, setMany } = useSettings();

    // Get
    const siteName = get('site_name', 'Default Name');

    // Set
    await set('site_name', 'New Name');

    // Set many
    await setMany({
        'general.site_name': 'My Site',
        'general.timezone': 'Asia/Riyadh',
    });

    return <div>...</div>;
}
```

---

## 📄 صفحة الإعدادات الكاملة

### Vue 3 Page:

```vue
<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import SettingsGroup from '../components/SettingsGroup.vue';
import { useSettings } from '../composables/useSettings';

const { setMany, loading, error, success } = useSettings();
const page = usePage();

const activeTab = ref('general');
const formValues = ref({});

const saveGroup = async (values) => {
    const result = await setMany(values);
    if (result.success) {
        router.reload();
    }
};
</script>

<template>
    <div class="settings-page">
        <h1>Settings</h1>
        
        <div v-if="error" class="alert alert-danger">{{ error }}</div>
        <div v-if="success" class="alert alert-success">{{ success }}</div>

        <SettingsGroup
            :group="currentGroup"
            :values="formValues"
            @update="saveGroup"
        />
    </div>
</template>
```

### React Page:

```tsx
import React, { useState, useEffect } from 'react';
import { router, usePage } from '@inertiajs/react';
import SettingsGroup from '../components/SettingsGroup';
import { useSettings } from '../composables/useSettings';

const SettingsPage: React.FC = () => {
    const { setMany, loading, error, success } = useSettings();
    const { props } = usePage();
    const [activeTab, setActiveTab] = useState('general');
    const [formValues, setFormValues] = useState({});

    const saveGroup = async (values: any) => {
        const result = await setMany(values);
        if (result.success) {
            router.reload();
        }
    };

    return (
        <div className="settings-page">
            <h1>Settings</h1>
            
            {error && <div className="alert alert-danger">{error}</div>}
            {success && <div className="alert alert-success">{success}</div>}

            <SettingsGroup
                group={currentGroup}
                values={formValues}
                onUpdate={setFormValues}
                onSave={saveGroup}
            />
        </div>
    );
};
```

---

## 🎨 التخصيص

### Bootstrap 5 (الافتراضي)

المكونات تستخدم Bootstrap 5 بشكل افتراضي.

### Tailwind CSS

لتستخدم Tailwind، قم بتعديل المكونات:

```vue
<!-- FormField.vue -->
<template>
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            {{ label }}
        </label>
        <input
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        />
    </div>
</template>
```

---

## 🔧 TypeScript Types

```typescript
import type {
    SettingField,
    SettingGroup,
    SettingsConfig,
    SettingEntry,
    SettingsPageProps,
    ApiResponse,
    SettingsApi,
} from 'salehye/settings/resources/js/types/settings';
```

---

## 📚 أمثلة إضافية

راجع الملفات التالية لأمثلة كاملة:

- `resources/js/vue/pages/Settings/Index.vue`
- `resources/js/react/pages/Settings/Index.tsx`
- `examples/complex-data-examples.md`
- `examples/blade-examples.md`

---

**تم التطوير بواسطة Salehye ❤️**
