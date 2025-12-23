<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class QueueHeartbeatCheck extends Command
{
    protected $signature = 'queue:heartbeat:check {--grace=15 : Batas menit keterlambatan yang masih dianggap sehat}';

    protected $description = 'Periksa apakah scheduler & worker queue masih berjalan (berdasarkan heartbeat).';

    public function handle(): int
    {
        $grace = (int) $this->option('grace');
        $last = Cache::get('queue:heartbeat');

        if (! $last) {
            $this->error('Heartbeat tidak ditemukan. Scheduler atau worker mungkin tidak berjalan.');
            return 1;
        }

        $timestamp = now()->parse($last);
        $diff = $timestamp->diffInMinutes(now());

        if ($diff > $grace) {
            $this->error("Heartbeat terlambat {$diff} menit (batas {$grace}). Scheduler atau worker mungkin berhenti.");
            return 1;
        }

        $this->info("OK: heartbeat {$diff} menit lalu ({$timestamp}).");
        return 0;
    }
}
