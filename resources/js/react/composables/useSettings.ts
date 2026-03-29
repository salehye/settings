import { useState, useCallback } from 'react';

export interface Settings {
    [key: string]: any;
}

export interface UpdateResponse {
    success: boolean;
    message?: string;
    failed?: string[];
}

export interface UseSettingsReturn {
    loading: boolean;
    error: string | null;
    success: string | null;
    get: (key: string, defaultValue?: any) => any;
    getAll: () => Settings;
    getGroup: (groupName: string) => Settings;
    set: (key: string, value: any) => Promise<boolean>;
    setMany: (settings: Settings) => Promise<UpdateResponse>;
    refresh: () => Promise<Settings>;
}

export function useSettings(): UseSettingsReturn {
    const [loading, setLoading] = useState<boolean>(false);
    const [error, setError] = useState<string | null>(null);
    const [success, setSuccess] = useState<string | null>(null);

    const get = useCallback((key: string, defaultValue: any = null): any => {
        const settings = (window as any).settings || {};
        return settings[key] ?? defaultValue;
    }, []);

    const getAll = useCallback((): Settings => {
        return (window as any).settings || {};
    }, []);

    const getGroup = useCallback((groupName: string): Settings => {
        const settings = (window as any).settings || {};
        const groupSettings: Settings = {};
        
        for (const [key, value] of Object.entries(settings)) {
            if (key.startsWith(`${groupName}.`)) {
                const shortKey = key.replace(`${groupName}.`, '');
                groupSettings[shortKey] = value;
            }
        }
        
        return groupSettings;
    }, []);

    const set = useCallback(async (key: string, value: any): Promise<boolean> => {
        setLoading(true);
        setError(null);
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement;
            
            const response = await fetch('/api/settings', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken?.content,
                },
                body: JSON.stringify({
                    settings: { [key]: value }
                }),
            });
            
            if (!response.ok) throw new Error('Failed to update setting');
            
            const data: UpdateResponse = await response.json();
            setSuccess(data.message || 'Setting updated successfully');
            
            if ((window as any).settings) {
                (window as any).settings[key] = value;
            }
            
            return true;
        } catch (err: any) {
            setError(err.message);
            return false;
        } finally {
            setLoading(false);
        }
    }, []);

    const setMany = useCallback(async (settings: Settings): Promise<UpdateResponse> => {
        setLoading(true);
        setError(null);
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement;
            
            const response = await fetch('/api/settings', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken?.content,
                },
                body: JSON.stringify({ settings }),
            });
            
            if (!response.ok) throw new Error('Failed to update settings');
            
            const data: UpdateResponse = await response.json();
            setSuccess(data.message || 'Settings updated successfully');
            
            if ((window as any).settings) {
                Object.assign((window as any).settings, settings);
            }
            
            return data;
        } catch (err: any) {
            setError(err.message);
            return { success: false, failed: Object.keys(settings) };
        } finally {
            setLoading(false);
        }
    }, []);

    const refresh = useCallback(async (): Promise<Settings> => {
        try {
            const response = await fetch('/api/settings/public');
            const data: { settings: Settings } = await response.json();
            (window as any).settings = data.settings;
            return data.settings;
        } catch (err: any) {
            setError(err.message);
            return {};
        }
    }, []);

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

export default useSettings;
