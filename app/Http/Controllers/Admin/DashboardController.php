<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\JobVacancy;
use App\Models\Pengumuman;
use App\Models\Pesan;
use App\Models\Program;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $metrics = [
            'berita' => Berita::count(),
            'program' => Program::count(),
            'pengumuman' => Pengumuman::count(),
            'lowongan' => JobVacancy::count(),
            'pesan' => Pesan::count(),
            'visits' => Visit::count(),
        ];

        $recentActivities = DB::table('activity_logs')
            ->select('action', 'description', 'created_at')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $beritaTrend = Berita::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $visitTrend = Visit::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $trendLabels = collect(range(6, 0))->map(fn ($i) => now()->subDays($i)->toDateString());
        $beritaSeries = $trendLabels->map(fn ($date) => optional($beritaTrend->firstWhere('date', $date))->total ?? 0);
        $visitSeries = $trendLabels->map(fn ($date) => optional($visitTrend->firstWhere('date', $date))->total ?? 0);

        $pesanUnread = Pesan::where('is_read', false)->count();

        return view('admin.dashboard', [
            'metrics' => $metrics,
            'recentActivities' => $recentActivities,
            'trendLabels' => $trendLabels,
            'beritaSeries' => $beritaSeries,
            'visitSeries' => $visitSeries,
            'visitTrend' => $visitTrend,
            'pesanUnread' => $pesanUnread,
        ]);
    }
}
