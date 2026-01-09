<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SyncParticipantsJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;

class SkillhubSyncController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        try {
            Artisan::call('skillhub:sync');
            SyncParticipantsJob::dispatch();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Sinkronisasi gagal: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Sinkronisasi Skillhub berhasil dijalankan.');
    }
}
