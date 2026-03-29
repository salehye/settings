import React, { useState, useEffect, ChangeEvent } from 'react';

export interface FormFieldProps {
    value: any;
    onChange: (value: any) => void;
    type?: string;
    label: string;
    description?: string;
    placeholder?: string;
    rules?: string[];
    error?: string;
    disabled?: boolean;
    required?: boolean;
    options?: Record<string, string | { ar: string; en: string }>;
    min?: number | string;
    max?: number | string;
    step?: number | string;
    rows?: number;
    accept?: string;
    icon?: string;
    prepend?: string;
    append?: string;
    searchable?: boolean;
    monospace?: boolean;
}

export const FormField: React.FC<FormFieldProps> = ({
    value,
    onChange,
    type = 'text',
    label,
    description = '',
    placeholder = '',
    rules = [],
    error = '',
    disabled = false,
    required = false,
    options = {},
    min,
    max,
    step = 1,
    rows = 3,
    accept = '',
    icon = '',
    prepend = '',
    append = '',
    searchable = false,
    monospace = false,
}) => {
    const [localValue, setLocalValue] = useState(value);

    useEffect(() => {
        setLocalValue(value);
    }, [value]);

    const handleChange = (newValue: any) => {
        setLocalValue(newValue);
        onChange(newValue);
    };

    const isRequired = rules.includes('required') || required;

    const getOptionLabel = (option: string | { ar: string; en: string }, val: string): string => {
        if (typeof option === 'object') {
            return option.ar || option.en || val;
        }
        return option;
    };

    const renderInput = () => {
        const baseProps = {
            disabled,
            className: `form-control ${error ? 'is-invalid' : ''}`,
        };

        switch (type) {
            case 'text':
            case 'email':
            case 'tel':
            case 'url':
            case 'password':
                return (
                    <div className="input-group">
                        {prepend && <span className="input-group-text">{prepend}</span>}
                        <input
                            type={type}
                            value={localValue || ''}
                            onChange={(e) => handleChange(e.target.value)}
                            placeholder={placeholder}
                            min={min as string | number}
                            max={max as string | number}
                            step={step as number}
                            {...baseProps}
                        />
                        {append && <span className="input-group-text">{append}</span>}
                    </div>
                );

            case 'number':
                return (
                    <input
                        type="number"
                        value={localValue || ''}
                        onChange={(e) => handleChange(parseFloat(e.target.value))}
                        placeholder={placeholder}
                        min={min as number}
                        max={max as number}
                        step={step as number}
                        {...baseProps}
                    />
                );

            case 'textarea':
                return (
                    <textarea
                        value={localValue || ''}
                        onChange={(e) => handleChange(e.target.value)}
                        placeholder={placeholder}
                        disabled={disabled}
                        rows={rows}
                        className={`form-control ${monospace ? 'font-monospace' : ''} ${error ? 'is-invalid' : ''}`}
                    />
                );

            case 'select':
                return (
                    <select
                        value={localValue || ''}
                        onChange={(e) => handleChange(e.target.value)}
                        disabled={disabled}
                        className={`form-select ${error ? 'is-invalid' : ''}`}
                    >
                        <option value="">{placeholder || 'Select...'}</option>
                        {Object.entries(options).map(([optionValue, optionLabel]) => (
                            <option key={optionValue} value={optionValue}>
                                {getOptionLabel(optionLabel, optionValue)}
                            </option>
                        ))}
                    </select>
                );

            case 'boolean':
            case 'checkbox':
                return (
                    <div className="form-check">
                        <input
                            type="checkbox"
                            checked={!!localValue}
                            onChange={(e) => handleChange(e.target.checked)}
                            disabled={disabled}
                            className="form-check-input"
                        />
                        <label className="form-check-label">{label}</label>
                    </div>
                );

            case 'tags':
                return (
                    <>
                        <input
                            type="text"
                            value={Array.isArray(localValue) ? localValue.join(', ') : localValue}
                            onChange={(e) => handleChange(e.target.value.split(',').map((s: string) => s.trim()))}
                            placeholder={placeholder}
                            disabled={disabled}
                            {...baseProps}
                        />
                        <small className="text-muted">Separate tags with commas</small>
                    </>
                );

            case 'image':
            case 'file':
                return (
                    <>
                        <input
                            type="file"
                            accept={accept || (type === 'image' ? 'image/*' : '')}
                            disabled={disabled}
                            className={`form-control ${error ? 'is-invalid' : ''}`}
                            onChange={(e: ChangeEvent<HTMLInputElement>) => {
                                const file = e.target.files?.[0];
                                if (file) handleChange(file);
                            }}
                        />
                        <small className="text-muted">{accept || 'Image files only'}</small>
                    </>
                );

            case 'color':
                return (
                    <input
                        type="color"
                        value={localValue || '#000000'}
                        onChange={(e) => handleChange(e.target.value)}
                        disabled={disabled}
                        className="form-control form-control-color"
                    />
                );

            case 'date':
                return (
                    <input
                        type="date"
                        value={localValue || ''}
                        onChange={(e) => handleChange(e.target.value)}
                        disabled={disabled}
                        {...baseProps}
                    />
                );

            case 'time':
                return (
                    <input
                        type="time"
                        value={localValue || ''}
                        onChange={(e) => handleChange(e.target.value)}
                        disabled={disabled}
                        {...baseProps}
                    />
                );

            case 'datetime':
                return (
                    <input
                        type="datetime-local"
                        value={localValue || ''}
                        onChange={(e) => handleChange(e.target.value)}
                        disabled={disabled}
                        {...baseProps}
                    />
                );

            default:
                return (
                    <input
                        type="text"
                        value={localValue || ''}
                        onChange={(e) => handleChange(e.target.value)}
                        placeholder={placeholder}
                        disabled={disabled}
                        {...baseProps}
                    />
                );
        }
    };

    return (
        <div className="form-group mb-4">
            {label && (
                <label className="form-label d-block mb-2">
                    {icon && <i className={`${icon} me-2`}></i>}
                    {label}
                    {isRequired && <span className="text-danger"> *</span>}
                </label>
            )}

            {description && <p className="text-muted small mb-2">{description}</p>}

            {renderInput()}

            {error && (
                <div className="invalid-feedback d-block">
                    {error}
                </div>
            )}
        </div>
    );
};

export default FormField;
