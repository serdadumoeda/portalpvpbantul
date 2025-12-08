<?php

namespace App\Providers;

use App\Services\ActivityLogger;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            \App\Listeners\LogSuccessfulLogin::class,
        ],
        Logout::class => [
            \App\Listeners\LogLogout::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
