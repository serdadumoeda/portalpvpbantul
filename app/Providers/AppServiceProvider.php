<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
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
        Paginator::useBootstrapFive();
        View::composer('layouts.app', function ($view) {
            $footerSettings = \App\Models\SiteSetting::pluck('value', 'key');
            $view->with('footerSettings', $footerSettings);
        });

        View::composer('layouts.admin', function ($view) {
            $view->with('groupActive', fn ($patterns) => collect((array) $patterns)->contains(fn ($pattern) => request()->routeIs($pattern)));
        });
    }
}
