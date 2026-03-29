<script setup>
import { ref, computed } from 'vue';

export function useSettings() {
    const loading = ref(false);
    const error = ref(null);
    const success = ref(null);

    const get = (key, defaultValue = null) => {
        const settings = window.settings || {};
        return settings[key] ?? defaultValue;
    };

    const getAll = () => {
        return window.settings || {};
    };

    const getGroup = (groupName) => {
        const settings = window.settings || {};
        const groupSettings = {};
        
        for (const [key, value] of Object.entries(settings)) {
            if (key.startsWith(`${groupName}.`)) {
                const shortKey = key.replace(`${groupName}.`, '');
                groupSettings[shortKey] = value;
            }
        }
        
        return groupSettings;
    };

    const set = async (key, value) => {
        loading.value = true;
        error.value = null;
        
        try {
            const response = await fetch('/api/settings', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify({
                    settings: { [key]: value }
                }),
            });
            
            if (!response.ok) throw new Error('Failed to update setting');
            
            const data = await response.json();
            success.value = data.message;
            
            // Update local storage
            if (window.settings) {
                window.settings[key] = value;
            }
            
            return true;
        } catch (err) {
            error.value = err.message;
            return false;
        } finally {
            loading.value = false;
        }
    };

    const setMany = async (settings) => {
        loading.value = true;
        error.value = null;
        
        try {
            const response = await fetch('/api/settings', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify({ settings }),
            });
            
            if (!response.ok) throw new Error('Failed to update settings');
            
            const data = await response.json();
            success.value = data.message;
            
            // Update local storage
            if (window.settings) {
                Object.assign(window.settings, settings);
            }
            
            return data;
        } catch (err) {
            error.value = err.message;
            return { success: false, failed: Object.keys(settings) };
        } finally {
            loading.value = false;
        }
    };

    const refresh = async () => {
        try {
            const response = await fetch('/api/settings/public');
            const data = await response.json();
            window.settings = data.settings;
            return data.settings;
        } catch (err) {
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
</script>
