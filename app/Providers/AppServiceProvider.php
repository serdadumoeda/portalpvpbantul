<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        //
        \View::composer('layouts.app', function ($view) {
            $footerSettings = \App\Models\SiteSetting::pluck('value', 'key');
            $view->with('footerSettings', $footerSettings);
        });
    }
}
