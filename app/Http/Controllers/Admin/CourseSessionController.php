<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseClass;
use App\Models\CourseSession;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseSessionController extends Controller
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function index()
    {
        $statusOptions = CourseSession::statuses();
        $statusFilter = request('status');
        $classFilter = request('class_id');

        $query = CourseSession::with('course')->orderBy('start_at');
        if ($statusFilter && array_key_exists($statusFilter, $statusOptions)) {
            $query->where('status', $statusFilter);
        }
        if ($classFilter) {
            $query->where('course_class_id', $classFilter);
        }

        $sessions = $query->paginate(20)->withQueryString();
        $classes = CourseClass::orderBy('title')->pluck('title', 'id');

        return view('admin.course_session.index', compact('sessions', 'statusOptions', 'statusFilter', 'classes', 'classFilter'));
    }

    public function create()
    {
        $classes = CourseClass::orderBy('title')->pluck('title', 'id');
        return view('admin.course_session.form', [
            'session' => new CourseSession(['is_active' => true, 'allow_download' => false]),
            'classes' => $classes,
            'action' => route('admin.course-session.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['attendance_code'] = $data['attendance_code'] ?: Str::upper(Str::random(8));
        $data['created_by'] = $request->user()->id;
        $this->applyWorkflow($request, $data);
        $session = CourseSession::create($data);

        $this->logger->log(
            $request->user(),
            'course.session.created',
            "Sesi '{$session->title}' ditambahkan",
            $session
        );

        return redirect()->route('admin.course-session.index')->with('success', 'Sesi berhasil ditambahkan.');
    }

    public function edit(CourseSession $course_session)
    {
        $classes = CourseClass::orderBy('title')->pluck('title', 'id');
        return view('admin.course_session.form', [
            'session' => $course_session,
            'classes' => $classes,
            'action' => route('admin.course-session.update', $course_session->id),
            'method' => 'PUT',
        ]);
    }

    public function qr(CourseSession $course_session)
    {
        abort_unless($course_session->attendance_code, 404);
        return view('admin.course_session.qr', ['session' => $course_session]);
    }

    public function show(CourseSession $course_session)
    {
        $course_session->load(['course']);
        $attendances = $course_session->attendances()->with('user')->latest('checked_at')->get();
        return view('admin.course_session.show', [
            'session' => $course_session,
            'attendances' => $attendances,
        ]);
    }

    public function cards(CourseSession $course_session)
    {
        abort_unless($course_session->attendance_code, 404);
        $enrollments = \App\Models\CourseEnrollment::with('user')
            ->where('course_class_id', $course_session->course_class_id)
            ->where('status', 'active')
            ->get();

        return view('admin.course_session.cards', [
            'session' => $course_session,
            'enrollments' => $enrollments,
        ]);
    }

    public function update(Request $request, CourseSession $course_session)
    {
        $data = $this->validateData($request);
        $data['attendance_code'] = $data['attendance_code'] ?: ($course_session->attendance_code ?: Str::upper(Str::random(8)));
        $this->applyWorkflow($request, $data, $course_session);
        $course_session->update($data);

        $this->logger->log(
            $request->user(),
            'course.session.updated',
            "Sesi '{$course_session->title}' diperbarui",
            $course_session
        );

        return redirect()->route('admin.course-session.index')->with('success', 'Sesi diperbarui.');
    }

    public function destroy(CourseSession $course_session)
    {
        $this->logger->log(
            request()->user(),
            'course.session.deleted',
            "Sesi '{$course_session->title}' dihapus",
            $course_session
        );
        $course_session->delete();
        return redirect()->route('admin.course-session.index')->with('success', 'Sesi dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'course_class_id' => 'required|exists:course_classes,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'meeting_link' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'status' => 'nullable|in:' . implode(',', array_keys(CourseSession::statuses())),
            'attendance_code' => 'nullable|string|max:20',
            'attendance_code_expires_at' => 'nullable|date|after:now',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        return $data;
    }

    private function applyWorkflow(Request $request, array &$data, ?CourseSession $session = null): void
    {
        $defaultStatus = $request->user()->hasPermission('approve-content') ? 'published' : 'draft';
        $currentStatus = $session ? $session->status : $defaultStatus;
        $requestedStatus = $data['status'] ?? $currentStatus;

        if (! $request->user()->hasPermission('approve-content')) {
            $requestedStatus = $requestedStatus === 'published' ? 'pending' : $requestedStatus;
        }

        $data['status'] = $requestedStatus ?: $currentStatus;

        if ($data['status'] === 'published') {
            $data['approved_by'] = $request->user()->id;
            $data['approved_at'] = now();
            $data['published_at'] = $session?->published_at ?? now();
        } else {
            $data['approved_by'] = null;
            $data['approved_at'] = null;
        }
    }
}
