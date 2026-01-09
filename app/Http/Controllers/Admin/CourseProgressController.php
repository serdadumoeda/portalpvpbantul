<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseAttendance;
use App\Models\CourseClass;
use App\Models\CourseEnrollment;
use App\Models\CourseSubmission;
use Illuminate\Http\Request;

class CourseProgressController extends Controller
{
    public function index()
    {
        $classFilter = request('class_id');
        $classes = CourseClass::orderBy('title')->pluck('title', 'id');

        $records = collect();
        $selectedClass = null;
        if ($classFilter) {
            $selectedClass = CourseClass::find($classFilter);
            $records = CourseEnrollment::with('user')
                ->where('course_class_id', $classFilter)
                ->whereIn('status', ['active', 'approved'])
                ->get()
                ->map(function ($enroll) use ($classFilter) {
                    $attended = CourseAttendance::whereHas('session', fn ($q) => $q->where('course_class_id', $classFilter))
                        ->where('user_id', $enroll->user_id)
                        ->where('status', 'hadir')
                        ->count();
                    $totalSessions = CourseAttendance::whereHas('session', fn ($q) => $q->where('course_class_id', $classFilter))
                        ->where('user_id', $enroll->user_id)
                        ->count();

                    $submissions = CourseSubmission::whereHas('assignment', fn ($q) => $q->where('course_class_id', $classFilter))
                        ->where('user_id', $enroll->user_id)
                        ->get();
                    $submittedCount = $submissions->count();
                    $gradedCount = $submissions->where('status', 'graded')->count();
                    $avgScore = $submissions->whereNotNull('total_score')->avg('total_score');

                    return [
                        'user' => $enroll->user,
                        'attended' => $attended,
                        'total_sessions' => $totalSessions,
                        'attendance_rate' => $totalSessions > 0 ? round(($attended / $totalSessions) * 100, 1) : null,
                        'submitted' => $submittedCount,
                        'graded' => $gradedCount,
                        'avg_score' => $avgScore ? round($avgScore, 1) : null,
                    ];
                });
        }

        return view('admin.course_progress.index', compact('records', 'classes', 'classFilter', 'selectedClass'));
    }
}
