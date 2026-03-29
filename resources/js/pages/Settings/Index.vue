<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import SettingsGroup from '../components/SettingsGroup.vue';
import { useSettings } from '../composables/useSettings';

interface Field {
    type: string;
    label: string | { ar: string; en: string };
    [key: string]: any;
}

interface Group {
    label: string | { ar: string; en: string };
    icon?: string;
    order?: number;
    fields: Record<string, Field>;
}

interface FormData {
    label: string;
    icon?: string;
    order: number;
    fields: Record<string, Field & { value: any }>;
}

interface Props {
    groups: Record<string, Group>;
    settings: Record<string, FormData>;
}

const props = defineProps<Props>();

const { setMany, loading, error, success } = useSettings();
const page = usePage<Props>();

const activeTab = ref<string>(Object.keys(props.settings)[0] || '');
const formValues = ref<Record<string, any>>({});
const formErrors = ref<Record<string, string>>({});

// Initialize form values from settings
onMounted(() => {
    Object.entries(props.settings).forEach(([groupKey, groupData]) => {
        Object.entries(groupData.fields).forEach(([fieldKey, field]) => {
            formValues.value[`${groupKey}.${fieldKey}`] = field.value;
        });
    });
});

const sortedGroups = computed(() => {
    return Object.entries(props.settings)
        .sort(([, a], [, b]) => (a.order || 999) - (b.order || 999))
        .reduce((acc, [key, value]) => {
            acc[key] = value;
            return acc;
        }, {} as Record<string, FormData>);
});

const currentGroup = computed(() => {
    return sortedGroups.value[activeTab.value];
});

const groupFields = computed(() => {
    if (!currentGroup.value) return {};
    
    const fields: Record<string, any> = {};
    Object.entries(currentGroup.value.fields).forEach(([key, field]) => {
        fields[key] = {
            ...field,
            value: formValues.value[`${activeTab.value}.${key}`],
        };
    });
    return fields;
});

const saveGroup = async (values: Record<string, any>) => {
    formErrors.value = {};
    
    const settingsToSave: Record<string, any> = {};
    Object.keys(values).forEach(key => {
        settingsToSave[`${activeTab.value}.${key}`] = values[key];
    });
    
    const result = await setMany(settingsToSave);
    
    if (result.success) {
        success.value = 'Settings saved successfully!';
        router.reload({ only: ['settings'] });
    } else if (result.failed) {
        result.failed.forEach(key => {
            formErrors.value[key] = 'Failed to save this field';
        });
    }
};

const getGroupLabel = (label: string | { ar: string; en: string }): string => {
    if (typeof label === 'object') {
        return label.ar || label.en || '';
    }
    return label;
};
</script>

<template>
    <div class="settings-page container-fluid py-4">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">
                        <i class="fas fa-cog me-2"></i>
                        Settings
                    </h1>
                    <button
                        v-if="loading"
                        class="btn btn-primary"
                        disabled
                    >
                        <span class="spinner-border spinner-border-sm me-2"></span>
                        Saving...
                    </button>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ error }}
            <button type="button" class="btn-close" @click="error = null"></button>
        </div>

        <div v-if="success" class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ success }}
            <button type="button" class="btn-close" @click="success = null"></button>
        </div>

        <div class="row">
            <!-- Tabs Sidebar -->
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Groups
                        </h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <button
                            v-for="(group, key) in sortedGroups"
                            :key="key"
                            type="button"
                            class="list-group-item list-group-item-action d-flex align-items-center justify-content-between"
                            :class="{ active: activeTab === key }"
                            @click="activeTab = key"
                        >
                            <span>
                                <i v-if="group.icon" :class="group.icon" class="me-2"></i>
                                {{ getGroupLabel(group.label) }}
                            </span>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Settings Form -->
            <div class="col-md-9">
                <SettingsGroup
                    v-if="currentGroup"
                    :group="{
                        ...currentGroup,
                        fields: groupFields,
                    }"
                    :model-value="formValues"
                    :errors="formErrors"
                    @update:model-value="formValues = $event"
                    @update="saveGroup"
                />
            </div>
        </div>
    </div>
</template>

<style scoped>
.settings-page {
    min-height: calc(100vh - 60px);
    background-color: #f8f9fa;
}

.list-group-item {
    border-left: 3px solid transparent;
    transition: all 0.2s ease;
}

.list-group-item.active {
    border-left-color: #0d6efd;
    background-color: #e7f1ff;
    color: #0d6efd;
}

.list-group-item:hover {
    border-left-color: #0d6efd;
    background-color: #f8f9fa;
}

.card {
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}
</style>
