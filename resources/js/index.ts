/**
 * Salehye Settings - InertiaJS Components
 * 
 * @package salehye/settings
 * @author Saleh <salehye@example.com>
 */

// Vue 3 Exports
export { useSettings as useSettingsVue } from './vue/composables/useSettings';
export { default as FormFieldVue } from './vue/components/FormField.vue';
export { default as SettingsGroupVue } from './vue/components/SettingsGroup.vue';

// React Exports
export { useSettings as useSettingsReact } from './react/composables/useSettings';
export { default as FormFieldReact } from './react/components/FormField';
export { default as SettingsGroupReact } from './react/components/SettingsGroup';

// Types
export type {
    SettingField,
    SettingGroup,
    SettingsConfig,
    SettingEntry,
    SettingsPageProps,
    ApiResponse,
    SettingsApi,
    TranslatableText,
} from './types/settings';

// Default exports for convenience
export { useSettingsVue as useSettings };
export { FormFieldVue as FormField };
export { SettingsGroupVue as SettingsGroup };
