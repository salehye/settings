<?php

namespace Salehye\Settings\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Salehye\Settings\Services\SettingsService;

class ShareSettingsMiddleware
{
    /**
     * Create a new middleware instance.
     */
    public function __construct(
        protected SettingsService $settingsService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     */
    public function handle(Request $request, Closure $next): \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
    {
        if ($this->shouldShare()) {
            Inertia::share(
                $this->getShareKey(),
                $this->settingsService->getPublic()
            );
        }

        return $next($request);
    }

    /**
     * Determine if settings should be shared.
     */
    protected function shouldShare(): bool
    {
        return config('settings.inertia.share_public', true);
    }

    /**
     * Get the key used to share settings.
     */
    protected function getShareKey(): string
    {
        return config('settings.inertia.key_name', 'settings');
    }
}
