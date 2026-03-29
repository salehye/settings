<script setup lang="ts">
import { ref, watch, type PropType } from 'vue';

type FieldType = 'text' | 'email' | 'tel' | 'url' | 'password' | 'number' | 'textarea' | 'select' | 'boolean' | 'checkbox' | 'tags' | 'image' | 'file' | 'color' | 'date' | 'time' | 'datetime' | 'repeater';

interface FieldOption {
    [key: string]: string | { ar: string; en: string };
}

const props = defineProps({
    modelValue: {
        type: [String, Number, Boolean, Array, Object] as PropType<any>,
        default: '',
    },
    type: {
        type: String as PropType<FieldType>,
        default: 'text',
    },
    label: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: '',
    },
    rules: {
        type: Array as PropType<string[]>,
        default: () => [],
    },
    error: {
        type: String,
        default: '',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    required: {
        type: Boolean,
        default: false,
    },
    options: {
        type: [Array, Object] as PropType<FieldOption | string[]>,
        default: () => [],
    },
    min: {
        type: [Number, String],
        default: null,
    },
    max: {
        type: [Number, String],
        default: null,
    },
    step: {
        type: [Number, String],
        default: 1,
    },
    rows: {
        type: Number,
        default: 3,
    },
    accept: {
        type: String,
        default: '',
    },
    icon: {
        type: String,
        default: '',
    },
    prepend: {
        type: String,
        default: '',
    },
    append: {
        type: String,
        default: '',
    },
    searchable: {
        type: Boolean,
        default: false,
    },
    monospace: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: any): void;
    (e: 'fileChange', file: File): void;
}>();

const localValue = ref<any>(props.modelValue);

watch(() => props.modelValue, (newValue) => {
    localValue.value = newValue;
});

watch(localValue, (newValue) => {
    emit('update:modelValue', newValue);
});

const isRequired = props.rules?.includes('required') || props.required;

const getOptionLabel = (option: string | { ar: string; en: string }, value: string): string => {
    if (typeof option === 'object') {
        return option.ar || option.en || value;
    }
    return option;
};
</script>

<template>
    <div class="form-group mb-4">
        <!-- Label -->
        <label v-if="label" class="form-label d-block mb-2">
            <span v-if="icon" :class="icon" class="me-2"></span>
            {{ label }}
            <span v-if="isRequired" class="text-danger">*</span>
        </label>

        <!-- Description -->
        <p v-if="description" class="text-muted small mb-2">{{ description }}</p>

        <!-- Text Input -->
        <template v-if="['text', 'email', 'tel', 'url', 'password'].includes(type)">
            <div class="input-group">
                <span v-if="prepend" class="input-group-text">{{ prepend }}</span>
                <input :type="type" v-model="localValue" :placeholder="placeholder" :disabled="disabled" :min="min"
                    :max="max" :step="step" class="form-control" :class="{ 'is-invalid': error }" />
                <span v-if="append" class="input-group-text">{{ append }}</span>
            </div>
        </template>

        <!-- Number Input -->
        <template v-else-if="type === 'number'">
            <input type="number" v-model="localValue" :placeholder="placeholder" :disabled="disabled" :min="min"
                :max="max" :step="step" class="form-control" :class="{ 'is-invalid': error }" />
        </template>

        <!-- Textarea -->
        <template v-else-if="type === 'textarea'">
            <textarea v-model="localValue" :placeholder="placeholder" :disabled="disabled" :rows="rows"
                class="form-control" :class="{ 'is-invalid': error, 'font-monospace': monospace }"></textarea>
        </template>

        <!-- Select -->
        <template v-else-if="type === 'select'">
            <select v-model="localValue" :disabled="disabled" class="form-select" :class="{ 'is-invalid': error }">
                <option value="">{{ placeholder || 'Select...' }}</option>
                <option v-for="(optionLabel, optionValue) in options" :key="optionValue" :value="optionValue">
                    {{ getOptionLabel(optionLabel as any, optionValue as string) }}
                </option>
            </select>
        </template>

        <!-- Boolean/Checkbox -->
        <template v-else-if="['boolean', 'checkbox'].includes(type)">
            <div class="form-check">
                <input type="checkbox" v-model="localValue" :disabled="disabled" class="form-check-input"
                    :class="{ 'is-invalid': error }" />
                <label class="form-check-label">{{ label }}</label>
            </div>
        </template>

        <!-- Tags Input -->
        <template v-else-if="type === 'tags'">
            <input type="text" v-model="localValue" :placeholder="placeholder" :disabled="disabled" class="form-control"
                :class="{ 'is-invalid': error }" />
            <small class="text-muted">Separate tags with commas</small>
        </template>

        <!-- Image/File Upload -->
        <template v-else-if="['image', 'file'].includes(type)">
            <input type="file" :accept="accept || (type === 'image' ? 'image/*' : '')" :disabled="disabled"
                class="form-control" :class="{ 'is-invalid': error }"
                @change="emit('fileChange', ($event.target as HTMLInputElement).files?.[0])" />
            <small v-if="accept || type === 'image'" class="text-muted">
                {{ accept || 'Image files only' }}
            </small>
        </template>

        <!-- Color Picker -->
        <template v-else-if="type === 'color'">
            <input type="color" v-model="localValue" :disabled="disabled" class="form-control form-control-color"
                :class="{ 'is-invalid': error }" />
        </template>

        <!-- Date -->
        <template v-else-if="type === 'date'">
            <input type="date" v-model="localValue" :disabled="disabled" class="form-control"
                :class="{ 'is-invalid': error }" />
        </template>

        <!-- Time -->
        <template v-else-if="type === 'time'">
            <input type="time" v-model="localValue" :disabled="disabled" class="form-control"
                :class="{ 'is-invalid': error }" />
        </template>

        <!-- DateTime -->
        <template v-else-if="type === 'datetime'">
            <input type="datetime-local" v-model="localValue" :disabled="disabled" class="form-control"
                :class="{ 'is-invalid': error }" />
        </template>

        <!-- Error Message -->
        <div v-if="error" class="invalid-feedback d-block">
            {{ error }}
        </div>
    </div>
</template>

<style scoped>
.font-monospace {
    font-family: 'Courier New', Courier, monospace;
}
</style>
