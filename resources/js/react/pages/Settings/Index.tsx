import React, { useState, useEffect, useMemo } from 'react';
import { router, usePage } from '@inertiajs/react';
import SettingsGroup from '../components/SettingsGroup';
import useSettings from '../composables/useSettings';

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
    label: string | { ar: string; en: string };
    icon?: string;
    order: number;
    fields: Record<string, Field & { value: any }>;
}

interface SettingsPageProps {
    groups: Record<string, Group>;
    settings: Record<string, FormData>;
}

const SettingsIndex: React.FC<SettingsPageProps> = ({ groups, settings }) => {
    const { setMany, loading, error, success } = useSettings();
    const [activeTab, setActiveTab] = useState<string>(Object.keys(settings)[0] || '');
    const [formValues, setFormValues] = useState<Record<string, any>>({});
    const [formErrors, setFormErrors] = useState<Record<string, string>>({});

    // Initialize form values from settings
    useEffect(() => {
        const initialValues: Record<string, any> = {};
        Object.entries(settings).forEach(([groupKey, groupData]) => {
            Object.entries(groupData.fields).forEach(([fieldKey, field]) => {
                initialValues[`${groupKey}.${fieldKey}`] = field.value;
            });
        });
        setFormValues(initialValues);
    }, [settings]);

    const sortedGroups = useMemo(() => {
        return Object.entries(settings)
            .sort(([, a], [, b]) => (a.order || 999) - (b.order || 999))
            .reduce((acc, [key, value]) => {
                acc[key] = value;
                return acc;
            }, {} as Record<string, FormData>);
    }, [settings]);

    const currentGroup = sortedGroups[activeTab];

    const groupFields = useMemo(() => {
        if (!currentGroup) return {};
        
        const fields: Record<string, any> = {};
        Object.entries(currentGroup.fields).forEach(([key, field]) => {
            fields[key] = {
                ...field,
                value: formValues[`${activeTab}.${key}`],
            };
        });
        return fields;
    }, [currentGroup, activeTab, formValues]);

    const saveGroup = async (values: Record<string, any>) => {
        setFormErrors({});
        
        const settingsToSave: Record<string, any> = {};
        Object.keys(values).forEach(key => {
            settingsToSave[`${activeTab}.${key}`] = values[key];
        });
        
        const result = await setMany(settingsToSave);
        
        if (result.success) {
            router.reload({ only: ['settings'] });
        } else if (result.failed) {
            const errors: Record<string, string> = {};
            result.failed.forEach(key => {
                errors[key] = 'Failed to save this field';
            });
            setFormErrors(errors);
        }
    };

    const getGroupLabel = (label: string | { ar: string; en: string }): string => {
        if (typeof label === 'object') {
            return label.ar || label.en || '';
        }
        return label;
    };

    return (
        <div className="settings-page container-fluid py-4">
            <div className="row mb-4">
                <div className="col-12">
                    <div className="d-flex justify-content-between align-items-center">
                        <h1 className="mb-0">
                            <i className="fas fa-cog me-2"></i>
                            Settings
                        </h1>
                        {loading && (
                            <button className="btn btn-primary" disabled>
                                <span className="spinner-border spinner-border-sm me-2"></span>
                                Saving...
                            </button>
                        )}
                    </div>
                </div>
            </div>

            {error && (
                <div className="alert alert-danger alert-dismissible fade show" role="alert">
                    <i className="fas fa-exclamation-circle me-2"></i>
                    {error}
                    <button type="button" className="btn-close" onClick={() => setError(null)}></button>
                </div>
            )}

            {success && (
                <div className="alert alert-success alert-dismissible fade show" role="alert">
                    <i className="fas fa-check-circle me-2"></i>
                    {success}
                    <button type="button" className="btn-close" onClick={() => setSuccess(null)}></button>
                </div>
            )}

            <div className="row">
                <div className="col-md-3 mb-4">
                    <div className="card">
                        <div className="card-header">
                            <h6 className="mb-0">
                                <i className="fas fa-list me-2"></i>
                                Groups
                            </h6>
                        </div>
                        <div className="list-group list-group-flush">
                            {Object.entries(sortedGroups).map(([key, group]) => (
                                <button
                                    key={key}
                                    type="button"
                                    className={`list-group-item list-group-item-action d-flex align-items-center justify-content-between ${
                                        activeTab === key ? 'active' : ''
                                    }`}
                                    onClick={() => setActiveTab(key)}
                                >
                                    <span>
                                        {group.icon && <i className={`${group.icon} me-2`}></i>}
                                        {getGroupLabel(group.label)}
                                    </span>
                                    <i className="fas fa-chevron-right"></i>
                                </button>
                            ))}
                        </div>
                    </div>
                </div>

                <div className="col-md-9">
                    {currentGroup && (
                        <SettingsGroup
                            group={{
                                ...currentGroup,
                                fields: groupFields,
                            }}
                            values={formValues}
                            errors={formErrors}
                            onUpdate={setFormValues}
                            onSave={saveGroup}
                        />
                    )}
                </div>
            </div>
        </div>
    );
};

export default SettingsIndex;
