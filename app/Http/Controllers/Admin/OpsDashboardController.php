<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseAttendance;
use App\Models\CourseSession;
use App\Models\CourseSubmission;
use App\Models\QueueHeartbeat;
use Illuminate\Support\Carbon;

class OpsDashboardController extends Controller
{
    public function __invoke()
    {
        $now = now();
        $pendingSubmissions = CourseSubmission::where('status', 'submitted');
        $pendingCount = $pendingSubmissions->count();
        $oldestPending = $pendingSubmissions->orderBy('submitted_at')->first();

        $todaySessions = CourseSession::whereDate('start_at', $now->toDateString())
            ->withCount('attendances')
            ->get();
        $noAttendanceSessions = $todaySessions->where('attendances_count', 0);

        $stats = [
            'pending_submissions' => $pendingCount,
            'oldest_pending_hours' => $oldestPending && $oldestPending->submitted_at ? $oldestPending->submitted_at->diffInHours($now) : null,
            'today_sessions' => $todaySessions->count(),
            'today_sessions_no_attendance' => $noAttendanceSessions->count(),
            'scheduler_ok' => ($hb = QueueHeartbeat::latest('created_at')->first()) && $hb->created_at->gt($now->subMinutes(10)),
            'last_heartbeat' => QueueHeartbeat::latest('created_at')->first()?->created_at,
        ];

        return view('admin.ops_dashboard', compact('stats', 'noAttendanceSessions', 'oldestPending'));
    }
}
