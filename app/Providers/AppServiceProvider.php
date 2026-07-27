<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Midtrans\Config;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;

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
        Carbon::setLocale('id');

        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Share settings ke seluruh view secara aman (lazy load)
        View::composer('*', function ($view) {

    static $settings;
    static $loaded = false;

    if (! $loaded) {
        try {
            $settings = WebsiteSetting::first();
        } catch (\Throwable $e) {
            $settings = null;
        }

        $loaded = true;
    }

    $view->with('settings', $settings);
});

        
    }
}