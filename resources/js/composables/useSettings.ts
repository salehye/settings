export interface Settings {
    [key: string]: any;
}

export interface SettingsResponse {
    settings: Settings;
}

export interface UpdateResponse {
    success: boolean;
    message?: string;
    failed?: string[];
}

export interface UseSettingsReturn {
    loading: Ref<boolean>;
    error: Ref<string | null>;
    success: Ref<string | null>;
    get: (key: string, defaultValue?: any) => any;
    getAll: () => Settings;
    getGroup: (groupName: string) => Settings;
    set: (key: string, value: any) => Promise<boolean>;
    setMany: (settings: Settings) => Promise<UpdateResponse>;
    refresh: () => Promise<Settings>;
}

import { ref, Ref } from 'vue';

export function useSettings(): UseSettingsReturn {
    const loading = ref<boolean>(false);
    const error = ref<string | null>(null);
    const success = ref<string | null>(null);

    const get = (key: string, defaultValue: any = null): any => {
        const settings = (window as any).settings || {};
        return settings[key] ?? defaultValue;
    };

    const getAll = (): Settings => {
        return (window as any).settings || {};
    };

    const getGroup = (groupName: string): Settings => {
        const settings = (window as any).settings || {};
        const groupSettings: Settings = {};
        
        for (const [key, value] of Object.entries(settings)) {
            if (key.startsWith(`${groupName}.`)) {
                const shortKey = key.replace(`${groupName}.`, '');
                groupSettings[shortKey] = value;
            }
        }
        
        return groupSettings;
    };

    const set = async (key: string, value: any): Promise<boolean> => {
        loading.value = true;
        error.value = null;
        
        try {
            const response = await fetch('/api/settings', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content,
                },
                body: JSON.stringify({
                    settings: { [key]: value }
                }),
            });
            
            if (!response.ok) throw new Error('Failed to update setting');
            
            const data: UpdateResponse = await response.json();
            success.value = data.message || 'Setting updated successfully';
            
            // Update local storage
            if ((window as any).settings) {
                (window as any).settings[key] = value;
            }
            
            return true;
        } catch (err: any) {
            error.value = err.message;
            return false;
        } finally {
            loading.value = false;
        }
    };

    const setMany = async (settings: Settings): Promise<UpdateResponse> => {
        loading.value = true;
        error.value = null;
        
        try {
            const response = await fetch('/api/settings', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content,
                },
                body: JSON.stringify({ settings }),
            });
            
            if (!response.ok) throw new Error('Failed to update settings');
            
            const data: UpdateResponse = await response.json();
            success.value = data.message || 'Settings updated successfully';
            
            // Update local storage
            if ((window as any).settings) {
                Object.assign((window as any).settings, settings);
            }
            
            return data;
        } catch (err: any) {
            error.value = err.message;
            return { success: false, failed: Object.keys(settings) };
        } finally {
            loading.value = false;
        }
    };

    const refresh = async (): Promise<Settings> => {
        try {
            const response = await fetch('/api/settings/public');
            const data: SettingsResponse = await response.json();
            (window as any).settings = data.settings;
            return data.settings;
        } catch (err: any) {
            error.value = err.message;
            return {};
        }
    };

    return {
        loading,
        error,
        success,
        get,
        getAll,
        getGroup,
        set,
        setMany,
        refresh,
    };
}
