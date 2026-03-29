# 🎨 مكونات InertiaJS للوحة التحكم

## 📦 التثبيت

### للمشاريع التي تستخدم Vue 3:

```bash
npm install
```

استيراد المكونات:
```typescript
import { useSettings } from 'salehye/settings/resources/js/vue/composables/useSettings';
import SettingsGroup from 'salehye/settings/resources/js/vue/components/SettingsGroup.vue';
```

### للمشاريع التي تستخدم React:

```bash
npm install
```

استيراد المكونات:
```typescript
import { useSettings } from 'salehye/settings/resources/js/react/composables/useSettings';
import SettingsGroup from 'salehye/settings/resources/js/react/components/SettingsGroup';
```

---

## 🚀 البدء السريع

### 1. إنشاء صفحة الإعدادات

#### Vue 3:

```vue
<script setup lang="ts">
import { useSettings } from 'salehye/settings/resources/js/vue/composables/useSettings';
import SettingsGroup from 'salehye/settings/resources/js/vue/components/SettingsGroup.vue';

const { setMany } = useSettings();

const saveSettings = async (values) => {
    await setMany(values);
};
</script>

<template>
    <SettingsGroup
        :group="groupConfig"
        :values="formValues"
        @update="saveSettings"
    />
</template>
```

#### React:

```tsx
import { useSettings } from 'salehye/settings/resources/js/react/composables/useSettings';
import SettingsGroup from 'salehye/settings/resources/js/react/components/SettingsGroup';

function SettingsPage() {
    const { setMany } = useSettings();

    const saveSettings = async (values) => {
        await setMany(values);
    };

    return (
        <SettingsGroup
            group={groupConfig}
            values={formValues}
            onSave={saveSettings}
        />
    );
}
```

---

## 📋 المكونات المتوفرة

### 1. FormField ⭐

مكون متعدد الاستخدامات لجميع أنواع الحقول:

```vue
<!-- Vue -->
<FormField
    v-model="form.site_name"
    type="text"
    label="اسم الموقع"
    :rules="['required']"
    icon="fas fa-globe"
/>
```

```tsx
// React
<FormField
    value={form.site_name}
    onChange={(v) => setForm({...form, site_name: v})}
    type="text"
    label="اسم الموقع"
    rules={['required']}
    icon="fas fa-globe"
/>
```

#### الأنواع المدعومة:

| النوع | الوصف | مثال |
|-------|-------|------|
| `text` | نص عادي | اسم الموقع |
| `email` | بريد إلكتروني | البريد الإلكتروني |
| `number` | رقم | عدد العناصر |
| `textarea` | نص طويل | الوصف |
| `select` | قائمة منسدلة | المنطقة الزمنية |
| `boolean` | نعم/لا | تفعيل التعليقات |
| `image` | رفع صورة | الشعار |
| `date` | تاريخ | تاريخ البدء |
| `time` | وقت | وقت البدء |
| `color` | لون | لون الموقع |

---

### 2. SettingsGroup 📝

مجموعة إعدادات كاملة:

```vue
<!-- Vue -->
<SettingsGroup
    :group="{
        label: { ar: 'الإعدادات العامة', en: 'General' },
        icon: 'fas fa-cog',
        fields: {
            site_name: {
                type: 'text',
                label: { ar: 'اسم الموقع', en: 'Site Name' },
                rules: ['required'],
            },
        },
    }"
    :values="formValues"
    @update="save"
/>
```

```tsx
// React
<SettingsGroup
    group={{
        label: { ar: 'الإعدادات العامة', en: 'General' },
        icon: 'fas fa-cog',
        fields: {
            site_name: {
                type: 'text',
                label: { ar: 'اسم الموقع', en: 'Site Name' },
                rules: ['required'],
            },
        },
    }}
    values={formValues}
    onSave={save}
/>
```

---

### 3. useSettings 🎯

إدارة الإعدادات:

```typescript
const {
    loading,      // حالة التحميل
    error,        // الخطأ
    success,      // رسالة النجاح
    get,          // الحصول على قيمة
    getAll,       // كل الإعدادات
    getGroup,     // مجموعة واحدة
    set,          // تعيين قيمة
    setMany,      // تعيين قيم متعددة
    refresh,      // تحديث
} = useSettings();
```

#### أمثلة:

```typescript
// الحصول على قيمة
const siteName = useSettings().get('site_name', 'الافتراضي');

// تعيين قيمة
await useSettings().set('site_name', 'موقعي');

// تعيين قيم متعددة
await useSettings().setMany({
    'general.site_name': 'موقعي',
    'general.timezone': 'Asia/Riyadh',
});

// الحصول على مجموعة
const general = useSettings().getGroup('general');
```

---

## 📄 مثال كامل - صفحة الإعدادات

### Vue 3:

```vue
<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useSettings } from 'salehye/settings/resources/js/vue/composables/useSettings';
import SettingsGroup from 'salehye/settings/resources/js/vue/components/SettingsGroup.vue';

const { setMany, loading, error, success } = useSettings();

const activeTab = ref('general');

const groups = {
    general: {
        label: { ar: 'عام', en: 'General' },
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
    },
};

const saveSettings = async (values: any) => {
    const result = await setMany(values);
    if (result.success) {
        router.reload();
    }
};
</script>

<template>
    <div class="container py-4">
        <h1><i class="fas fa-cog"></i> الإعدادات</h1>

        <div v-if="error" class="alert alert-danger">{{ error }}</div>
        <div v-if="success" class="alert alert-success">{{ success }}</div>

        <div class="row mt-4">
            <div class="col-md-3">
                <div class="list-group">
                    <button
                        v-for="(group, key) in groups"
                        :key="key"
                        class="list-group-item list-group-item-action"
                        :class="{ active: activeTab === key }"
                        @click="activeTab = key"
                    >
                        <i :class="group.icon" class="me-2"></i>
                        {{ typeof group.label === 'object' ? group.label.ar : group.label }}
                    </button>
                </div>
            </div>

            <div class="col-md-9">
                <SettingsGroup
                    :group="groups[activeTab]"
                    :values="{}"
                    :loading="loading"
                    @update="saveSettings"
                />
            </div>
        </div>
    </div>
</template>
```

### React:

```tsx
import React, { useState } from 'react';
import { router } from '@inertiajs/react';
import { useSettings } from 'salehye/settings/resources/js/react/composables/useSettings';
import SettingsGroup from 'salehye/settings/resources/js/react/components/SettingsGroup';

const SettingsPage: React.FC = () => {
    const { setMany, loading, error, success } = useSettings();
    const [activeTab, setActiveTab] = useState('general');

    const groups = {
        general: {
            label: { ar: 'عام', en: 'General' },
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
        },
    };

    const saveSettings = async (values: any) => {
        const result = await setMany(values);
        if (result.success) {
            router.reload();
        }
    };

    return (
        <div className="container py-4">
            <h1><i className="fas fa-cog"></i> الإعدادات</h1>

            {error && <div className="alert alert-danger">{error}</div>}
            {success && <div className="alert alert-success">{success}</div>}

            <div className="row mt-4">
                <div className="col-md-3">
                    <div className="list-group">
                        {Object.entries(groups).map(([key, group]) => (
                            <button
                                key={key}
                                className={`list-group-item list-group-item-action ${
                                    activeTab === key ? 'active' : ''
                                }`}
                                onClick={() => setActiveTab(key)}
                            >
                                <i className={`${group.icon} me-2`}></i>
                                {typeof group.label === 'object' ? group.label.ar : group.label}
                            </button>
                        ))}
                    </div>
                </div>

                <div className="col-md-9">
                    <SettingsGroup
                        group={groups[activeTab]}
                        values={{}}
                        loading={loading}
                        onSave={saveSettings}
                    />
                </div>
            </div>
        </div>
    );
};
```

---

## 🎨 التخصيص

### Bootstrap 5 (الافتراضي)

المكونات تستخدم Bootstrap 5.

### Tailwind CSS

عدّل المكونات لاستخدام Tailwind:

```vue
<template>
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">
            {{ label }}
        </label>
        <input
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
        />
    </div>
</template>
```

---

## 📚 روابط مفيدة

- [توثيق كامل](INERTIA_COMPONENTS.md)
- [أمثلة البيانات المعقدة](examples/complex-data-examples.md)
- [أمثلة Blade](examples/blade-examples.md)

---

**تم التطوير بواسطة Salehye ❤️**
