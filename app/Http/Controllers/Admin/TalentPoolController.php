<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseAssignment;
use App\Models\CourseClass;
use App\Models\CourseEnrollment;
use App\Models\CourseSubmission;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TalentPoolController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAccess();

        $classId = $request->input('class_id');
        $classes = CourseClass::orderBy('title')->get();

        $query = CourseEnrollment::with(['user', 'course'])
            ->whereHas('course', fn ($q) => $q->where('status', 'published'))
            ->orderByDesc('completed_at')
            ->orderBy('created_at');

        if ($classId) {
            $query->where('course_class_id', $classId);
        }

        $enrollments = $query->paginate(30)->withQueryString();
        $stats = $this->buildStatsForEnrollments($enrollments);

        return view('admin.talent_pool.index', compact('classes', 'classId', 'enrollments', 'stats'));
    }

    public function export(Request $request, CourseClass $course_class): StreamedResponse
    {
        $this->authorizeAccess();

        $enrollments = CourseEnrollment::with('user')
            ->where('course_class_id', $course_class->id)
            ->get();

        $stats = $this->buildStatsForEnrollments($enrollments);

        $filename = 'cv-book-' . \Str::slug($course_class->title) . '.csv';

        return response()->streamDownload(function () use ($enrollments, $stats, $course_class) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['CV Book - Satpel PVP Bantul']);
            fputcsv($out, ['Kelas', $course_class->title]);
            fputcsv($out, ['Badge', $course_class->badge]);
            fputcsv($out, ['Kompetensi', implode(' | ', $course_class->competencies ?? [])]);
            fputcsv($out, []);
            fputcsv($out, ['Nama', 'Email', 'Status', 'Sertifikat', 'Skor Total', 'Skor Maks', 'Persen', 'Submissions', 'Terakhir Update']);

            foreach ($enrollments as $enrollment) {
                $userStats = $stats[$enrollment->id] ?? [];
                fputcsv($out, [
                    $enrollment->user->name ?? '-',
                    $enrollment->user->email ?? '-',
                    $enrollment->completed_at ? 'Lulus' : 'Aktif',
                    $enrollment->certificate_url ?: '-',
                    $userStats['score_total'] ?? 0,
                    $userStats['score_max'] ?? 0,
                    $userStats['percent'] ?? 0,
                    $userStats['submission_count'] ?? 0,
                    $userStats['last_submission'] ?? '-',
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function buildStatsForEnrollments($enrollments): array
    {
        $stats = [];
        $assignmentScores = $this->assignmentScoresByClass($enrollments->pluck('course_class_id')->unique()->all());

        foreach ($enrollments as $enrollment) {
            $userId = $enrollment->user_id;
            $classId = $enrollment->course_class_id;

            $userSubs = CourseSubmission::where('user_id', $userId)
                ->whereHas('assignment', fn ($q) => $q->where('course_class_id', $classId))
                ->get();

            $scoreTotal = $userSubs->sum('total_score');
            $assignmentMax = $assignmentScores[$classId] ?? 0;
            $percent = $assignmentMax > 0 ? round(($scoreTotal / $assignmentMax) * 100, 2) : 0;

            $stats[$enrollment->id] = [
                'score_total' => $scoreTotal,
                'score_max' => $assignmentMax,
                'percent' => $percent,
                'submission_count' => $userSubs->count(),
                'last_submission' => optional($userSubs->max('submitted_at'))->format('Y-m-d H:i'),
            ];
        }

        return $stats;
    }

    private function assignmentScoresByClass(array $classIds): array
    {
        return CourseAssignment::whereIn('course_class_id', $classIds)
            ->where('status', 'published')
            ->where('is_active', true)
            ->selectRaw('course_class_id, COALESCE(SUM(max_score),0) as total')
            ->groupBy('course_class_id')
            ->pluck('total', 'course_class_id')
            ->toArray();
    }

    private function authorizeAccess(): void
    {
        $user = auth()->user();
        if ($user?->hasRole('superadmin')) {
            return;
        }

        if (! $user?->hasPermission('view-talent-pool')) {
            abort(403);
        }
    }
}
