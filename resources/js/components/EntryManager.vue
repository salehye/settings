<script setup lang="ts">
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import FormField from './FormField.vue';

interface EntryField {
    type: string;
    label: string | { ar: string; en: string };
    required?: boolean;
    options?: Record<string, string>;
    [key: string]: any;
}

interface Entry {
    id: number;
    key: string;
    group: string;
    type: string;
    value: any;
    extra_data: Record<string, any>;
    translations?: Record<string, any>;
    is_active: boolean;
    is_featured: boolean;
    order: number;
    created_at: string;
    updated_at: string;
}

interface GroupConfig {
    label: string | { ar: string; en: string };
    icon?: string;
    entry_fields: Record<string, EntryField>;
}

const props = defineProps<{
    group: string;
    config: GroupConfig;
    entries: Entry[];
}>();

const emit = defineEmits<{
    (e: 'refresh'): void;
}>();

const showModal = ref(false);
const editingEntry = ref<Entry | null>(null);
const formValues = ref<Record<string, any>>({});
const formErrors = ref<Record<string, string>>({});
const deletingEntry = ref<Entry | null>(null);
const loading = ref(false);

const displayedEntries = computed(() => {
    return props.entries
        .filter(entry => entry.group === props.group)
        .sort((a, b) => a.order - b.order);
});

const openCreateModal = () => {
    editingEntry.value = null;
    formValues.value = {};
    formErrors.value = {};
    showModal.value = true;
};

const openEditModal = (entry: Entry) => {
    editingEntry.value = entry;
    formValues.value = { ...entry.extra_data };
    formErrors.value = {};
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingEntry.value = null;
    formValues.value = {};
    formErrors.value = {};
};

const saveEntry = async () => {
    loading.value = true;
    formErrors.value = {};

    try {
        const url = editingEntry.value
            ? `/api/settings/entries/${editingEntry.value.id}`
            : '/api/settings/entries';
        
        const method = editingEntry.value ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content,
            },
            body: JSON.stringify({
                group: props.group,
                extra_data: formValues.value,
                is_active: formValues.value.is_active ?? true,
                is_featured: formValues.value.is_featured ?? false,
            }),
        });

        if (!response.ok) {
            const data = await response.json();
            if (data.errors) {
                formErrors.value = data.errors;
            }
            throw new Error('Failed to save entry');
        }

        closeModal();
        emit('refresh');
    } catch (error: any) {
        console.error(error);
    } finally {
        loading.value = false;
    }
};

const confirmDelete = (entry: Entry) => {
    deletingEntry.value = entry;
};

const cancelDelete = () => {
    deletingEntry.value = null;
};

const deleteEntry = async () => {
    if (!deletingEntry.value) return;

    loading.value = true;

    try {
        const response = await fetch(`/api/settings/entries/${deletingEntry.value.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content,
            },
        });

        if (!response.ok) {
            throw new Error('Failed to delete entry');
        }

        deletingEntry.value = null;
        emit('refresh');
    } catch (error: any) {
        console.error(error);
    } finally {
        loading.value = false;
    }
};

const toggleActive = async (entry: Entry) => {
    try {
        const response = await fetch(`/api/settings/entries/${entry.id}/toggle-active`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content,
            },
        });

        if (response.ok) {
            emit('refresh');
        }
    } catch (error) {
        console.error(error);
    }
};

const toggleFeatured = async (entry: Entry) => {
    try {
        const response = await fetch(`/api/settings/entries/${entry.id}/toggle-featured`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content,
            },
        });

        if (response.ok) {
            emit('refresh');
        }
    } catch (error) {
        console.error(error);
    }
};

const getLabel = (label: string | { ar: string; en: string }): string => {
    if (typeof label === 'object') {
        return label.ar || label.en || '';
    }
    return label;
};

const getGroupName = () => {
    return getLabel(props.config.label);
};
</script>

<template>
    <div class="entry-manager">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i :class="config.icon" class="me-2"></i>
                {{ getGroupName() }}
            </h2>
            <button class="btn btn-primary" @click="openCreateModal">
                <i class="fas fa-plus me-2"></i>
                Add New
            </button>
        </div>

        <!-- Entries Table -->
        <div class="card">
            <div class="card-body">
                <div v-if="displayedEntries.length === 0" class="text-center text-muted py-5">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p>No entries found</p>
                </div>

                <div v-else class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px;">Order</th>
                                <th>Name</th>
                                <th v-if="config.entry_fields.job_title">Job Title</th>
                                <th v-if="config.entry_fields.email">Email</th>
                                <th v-if="config.entry_fields.company">Company</th>
                                <th style="width: 100px;">Active</th>
                                <th style="width: 100px;">Featured</th>
                                <th style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="entry in displayedEntries" :key="entry.id">
                                <td>{{ entry.order }}</td>
                                <td>
                                    <strong>{{ entry.extra_data.name }}</strong>
                                </td>
                                <td v-if="config.entry_fields.job_title">
                                    {{ entry.extra_data.job_title || '-' }}
                                </td>
                                <td v-if="config.entry_fields.email">
                                    {{ entry.extra_data.email || '-' }}
                                </td>
                                <td v-if="config.entry_fields.company">
                                    {{ entry.extra_data.company || '-' }}
                                </td>
                                <td>
                                    <button
                                        class="btn btn-sm"
                                        :class="entry.is_active ? 'btn-success' : 'btn-secondary'"
                                        @click="toggleActive(entry)"
                                    >
                                        <i :class="entry.is_active ? 'fas fa-check' : 'fas fa-times'"></i>
                                    </button>
                                </td>
                                <td>
                                    <button
                                        class="btn btn-sm"
                                        :class="entry.is_featured ? 'btn-warning' : 'btn-secondary'"
                                        @click="toggleFeatured(entry)"
                                    >
                                        <i :class="entry.is_featured ? 'fas fa-star' : 'far fa-star'"></i>
                                    </button>
                                </td>
                                <td>
                                    <button
                                        class="btn btn-sm btn-outline-primary me-1"
                                        @click="openEditModal(entry)"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button
                                        class="btn btn-sm btn-outline-danger"
                                        @click="confirmDelete(entry)"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div
            v-if="showModal"
            class="modal fade show d-block"
            tabindex="-1"
            @click.self="closeModal"
        >
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ editingEntry ? 'Edit' : 'Add New' }} {{ getGroupName() }}
                        </h5>
                        <button type="button" class="btn-close" @click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <div v-for="(field, key) in config.entry_fields" :key="key">
                            <FormField
                                v-model="formValues[key]"
                                :type="field.type"
                                :label="getLabel(field.label)"
                                :required="field.required"
                                :options="field.options"
                                :error="formErrors[key]"
                            />
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <FormField
                                    v-model="formValues.is_active"
                                    type="checkbox"
                                    label="Active"
                                />
                            </div>
                            <div class="col-md-6">
                                <FormField
                                    v-model="formValues.is_featured"
                                    type="checkbox"
                                    label="Featured"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">
                            Cancel
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            :disabled="loading"
                            @click="saveEntry"
                        >
                            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                            {{ loading ? 'Saving...' : 'Save' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div
            v-if="deletingEntry"
            class="modal fade show d-block"
            tabindex="-1"
            @click.self="cancelDelete"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="btn-close btn-close-white" @click="cancelDelete"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete <strong>{{ deletingEntry.extra_data.name }}</strong>?</p>
                        <p class="text-muted small">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cancelDelete">
                            Cancel
                        </button>
                        <button
                            type="button"
                            class="btn btn-danger"
                            :disabled="loading"
                            @click="deleteEntry"
                        >
                            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                            {{ loading ? 'Deleting...' : 'Delete' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backdrop for modals -->
        <div v-if="showModal || deletingEntry" class="modal-backdrop fade show"></div>
    </div>
</template>

<style scoped>
.entry-manager {
    background-color: #f8f9fa;
    min-height: 100vh;
    padding: 2rem;
}

.card {
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.table th {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
    text-transform: uppercase;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.modal-content {
    border-radius: 0.5rem;
    border: none;
}

.modal-header {
    border-bottom: 1px solid #e9ecef;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
}
</style>
