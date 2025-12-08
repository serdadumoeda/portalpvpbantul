<?php

namespace App\Listeners;

use App\Services\ActivityLogger;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function handle(Login $event): void
    {
        $this->logger->log(
            user: $event->user,
            action: 'auth.login',
            description: 'User login via web'
        );
    }
}
