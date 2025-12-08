<?php

namespace App\Listeners;

use App\Services\ActivityLogger;
use Illuminate\Auth\Events\Logout;

class LogLogout
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function handle(Logout $event): void
    {
        $this->logger->log(
            user: $event->user,
            action: 'auth.logout',
            description: 'User logout via web'
        );
    }
}
