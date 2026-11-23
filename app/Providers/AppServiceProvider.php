<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Vite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->runningInConsole()) {
            return;
        }

        $request = request();
        $host = $request->getHost();
        $forwardedHost = (string) $request->header('x-forwarded-host', '');

        if (Str::endsWith($forwardedHost, '.trycloudflare.com')) {
            // Cloudflare tunnels always terminate TLS externally; backend receives HTTP.
            // Force HTTPS regardless of x-forwarded-proto so MP back_urls / notification_url are correct.
            URL::forceRootUrl("https://{$forwardedHost}");
            URL::forceScheme('https');
        }

        if (Str::endsWith($host, '.trycloudflare.com') || Str::endsWith($forwardedHost, '.trycloudflare.com')) {
            Vite::useHotFile(storage_path('framework/vite-disabled.hot'));
        }
    }
}
