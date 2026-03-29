import React, { useState, useEffect } from 'react';
import FormField from './FormField';

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

interface SettingsGroupProps {
    group: Group;
    values: Record<string, any>;
    errors?: Record<string, string>;
    readonly?: boolean;
    onUpdate: (values: Record<string, any>) => void;
    onSave: (values: Record<string, any>) => void;
}

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

export const SettingsGroup: React.FC<SettingsGroupProps> = ({
    group,
    values,
    errors = {},
    readonly = false,
    onUpdate,
    onSave,
}) => {
    const [localValues, setLocalValues] = useState<Record<string, any>>({ ...values });

    useEffect(() => {
        setLocalValues(values);
    }, [values]);

    const updateValue = (key: string, value: any) => {
        const newValues = { ...localValues, [key]: value };
        setLocalValues(newValues);
        onUpdate(newValues);
    };

    return (
        <div className="settings-group card mb-4">
            {(group.label || group.icon) && (
                <div className="card-header">
                    <h5 className="mb-0">
                        {group.icon && <i className={`${group.icon} me-2`}></i>}
                        {getLabel(group.label, group.key || '')}
                    </h5>
                </div>
            )}

            <div className="card-body">
                {Object.entries(group.fields).map(([key, field]) => (
                    <FormField
                        key={key}
                        value={localValues[key]}
                        onChange={(value) => updateValue(key, value)}
                        type={field.type || 'text'}
                        label={getLabel(field.label, key)}
                        description={getDescription(field.description)}
                        placeholder={getPlaceholder(field.placeholder)}
                        rules={field.rules || []}
                        error={errors?.[key]}
                        disabled={readonly || field.disabled}
                        required={field.required}
                        options={field.options}
                        min={field.min}
                        max={field.max}
                        step={field.step}
                        rows={field.rows}
                        accept={field.accepted || field.accept}
                        icon={field.icon}
                        searchable={field.searchable}
                        monospace={field.monospace}
                    />
                ))}

                {group.fields && Object.entries(group.fields).map(([key, field]) => (
                    field.warning && (
                        <div key={`warning-${key}`} className="alert alert-warning mt-2" role="alert">
                            <i className="fas fa-exclamation-triangle me-2"></i>
                            {getWarning(field.warning)}
                        </div>
                    )
                ))}
            </div>

            {!readonly && (
                <div className="card-footer">
                    <button
                        type="button"
                        className="btn btn-primary"
                        onClick={() => onSave(localValues)}
                    >
                        <i className="fas fa-save me-2"></i>
                        Save Changes
                    </button>
                </div>
            )}
        </div>
    );
};

export default SettingsGroup;
