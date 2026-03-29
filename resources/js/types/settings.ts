export interface SettingField {
    type: string;
    label: string | TranslatableText;
    description?: string | TranslatableText;
    placeholder?: string | TranslatableText;
    rules?: string[];
    required?: boolean;
    disabled?: boolean;
    options?: Record<string, string | TranslatableText>;
    min?: number | string;
    max?: number | string;
    step?: number | string;
    rows?: number;
    accepted?: string;
    accept?: string;
    icon?: string;
    searchable?: boolean;
    monospace?: boolean;
    warning?: string | TranslatableText;
    is_translatable?: boolean;
    is_sensitive?: boolean;
    is_system?: boolean;
    encrypted?: boolean;
    default?: any;
    [key: string]: any;
}

export interface TranslatableText {
    ar: string;
    en: string;
    [key: string]: string;
}

export interface SettingGroup {
    label: string | TranslatableText;
    icon?: string;
    order?: number;
    is_entry?: boolean;
    is_system?: boolean;
    entry_fields?: Record<string, SettingField>;
    fields: Record<string, SettingField>;
}

export interface SettingsConfig {
    fields: Record<string, SettingGroup>;
    sensitive_fields: string[];
    locales: Record<string, { name: string; dir: string }>;
    default_locale: string;
    field_types: Record<string, { component: string }>;
    cache: {
        enabled: boolean;
        ttl: number;
        prefix: string;
        store?: string;
    };
    security: {
        auto_encryption: boolean;
        encryption_cipher: string;
        audit_log_enabled: boolean;
        require_permission: boolean;
        permission_name: string;
        allowed_ips: string[];
    };
    inertia: {
        share_public: boolean;
        key_name: string;
        exclude_groups?: string[];
    };
    api: {
        enabled: boolean;
        middleware: string[];
        rate_limit: number;
    };
}

export interface SettingEntry {
    id: number;
    group: string;
    key: string;
    type: string;
    value: any;
    translations?: Record<string, any>;
    meta?: Record<string, any>;
    extra_data?: Record<string, any>;
    is_public: boolean;
    is_active: boolean;
    is_featured: boolean;
    order: number;
    published_at?: string;
    created_by?: number;
    updated_by?: number;
    created_at: string;
    updated_at: string;
}

export interface SettingsPageProps {
    groups: Record<string, SettingGroup>;
    settings: Record<string, {
        label: string | TranslatableText;
        icon?: string;
        order: number;
        fields: Record<string, SettingField & { value: any }>;
    }>;
    entries?: Record<string, SettingEntry[]>;
}

export interface ApiResponse<T = any> {
    success: boolean;
    message?: string;
    data?: T;
    errors?: Record<string, string[]>;
    failed?: string[];
}

export interface SettingsApi {
    get: (key: string, defaultValue?: any) => any;
    getAll: () => Record<string, any>;
    getGroup: (groupName: string) => Record<string, any>;
    set: (key: string, value: any) => Promise<boolean>;
    setMany: (settings: Record<string, any>) => Promise<ApiResponse>;
    refresh: () => Promise<Record<string, any>>;
}
