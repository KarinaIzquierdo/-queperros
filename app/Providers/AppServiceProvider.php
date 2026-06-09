<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\URL;

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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('auth.partials.login-footer', function ($view) {
            try {
                $view->with('settings', \App\Models\Setting::query()->first());
            } catch (\Exception $e) {
                // If database connection fails, return null
                $view->with('settings', null);
            }
        });
    }
}
