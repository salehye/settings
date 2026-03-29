<script setup lang="ts">
import { ref, computed, type PropType } from 'vue';
import FormField from './FormField.vue';

interface FieldLabel {
    ar: string;
    en: string;
}

interface Field {
    type: string;
    label: string | FieldLabel;
    description?: string | FieldLabel;
    placeholder?: string | FieldLabel;
    rules?: string[];
    disabled?: boolean;
    required?: boolean;
    options?: Record<string, string | FieldLabel>;
    min?: number | string;
    max?: number | string;
    step?: number | string;
    rows?: number;
    accepted?: string;
    accept?: string;
    icon?: string;
    searchable?: boolean;
    monospace?: boolean;
    warning?: string | FieldLabel;
    [key: string]: any;
}

interface Group {
    key?: string;
    label?: string | FieldLabel;
    icon?: string;
    fields: Record<string, Field>;
}

const props = defineProps({
    group: {
        type: Object as PropType<Group>,
        required: true,
    },
    modelValue: {
        type: Object as PropType<Record<string, any>>,
        default: () => ({}),
    },
    errors: {
        type: Object as PropType<Record<string, string>>,
        default: () => ({}),
    },
    readonly: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: Record<string, any>): void;
    (e: 'update', value: Record<string, any>): void;
}>();

const localValues = ref<Record<string, any>>({ ...props.modelValue });

const updateValue = (key: string, value: any) => {
    localValues.value[key] = value;
    emit('update:modelValue', { ...localValues.value });
};

const getLabel = (label: string | FieldLabel | undefined, fallback: string): string => {
    if (!label) return fallback;
    if (typeof label === 'object') {
        return label.ar || label.en || fallback;
    }
    return label;
};

const getDescription = (description: string | FieldLabel | undefined): string => {
    if (!description) return '';
    if (typeof description === 'object') {
        return description.ar || description.en || '';
    }
    return description;
};

const getPlaceholder = (placeholder: string | FieldLabel | undefined): string => {
    if (!placeholder) return '';
    if (typeof placeholder === 'object') {
        return placeholder.ar || placeholder.en || '';
    }
    return placeholder;
};

const getWarning = (warning: string | FieldLabel | undefined): string => {
    if (!warning) return '';
    if (typeof warning === 'object') {
        return warning.ar || warning.en || '';
    }
    return warning;
};
</script>

<template>
    <div class="settings-group card mb-4">
        <div v-if="group.label || group.icon" class="card-header">
            <h5 class="mb-0">
                <i v-if="group.icon" :class="group.icon" class="me-2"></i>
                {{ getLabel(group.label, group.key || '') }}
            </h5>
        </div>

        <div class="card-body">
            <div v-for="(field, key) in group.fields" :key="key">
                <FormField
                    v-model="localValues[key]"
                    :type="field.type || 'text'"
                    :label="getLabel(field.label, key)"
                    :description="getDescription(field.description)"
                    :placeholder="getPlaceholder(field.placeholder)"
                    :rules="field.rules || []"
                    :error="errors?.[key]"
                    :disabled="readonly || field.disabled"
                    :required="field.required"
                    :options="field.options"
                    :min="field.min"
                    :max="field.max"
                    :step="field.step"
                    :rows="field.rows"
                    :accept="field.accepted || field.accept"
                    :icon="field.icon"
                    :searchable="field.searchable"
                    :monospace="field.monospace"
                />

                <div v-if="field.warning" class="alert alert-warning mt-2" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ getWarning(field.warning) }}
                </div>
            </div>
        </div>

        <div v-if="!readonly" class="card-footer">
            <button type="button" class="btn btn-primary" @click="$emit('update', localValues)">
                <i class="fas fa-save me-2"></i>
                Save Changes
            </button>
        </div>
    </div>
</template>

<style scoped>
.card {
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.card-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #e9ecef;
}
</style>
