<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseClass;
use App\Models\CourseEnrollment;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use App\Notifications\EnrollmentCreated;
use App\Notifications\EnrollmentBlocked;

class CourseEnrollmentController extends Controller
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function index()
    {
        abort_unless(request()->user()?->hasPermission('manage-enrollment'), 403);
        $statusOptions = CourseEnrollment::statuses();
        $statusFilter = request('status');
        $classFilter = request('class_id');
        $userFilter = request('user_id');

        $query = CourseEnrollment::with(['course', 'user'])->orderByDesc('created_at');

        if ($statusFilter && array_key_exists($statusFilter, $statusOptions)) {
            $query->where('status', $statusFilter);
        }
        if ($classFilter) {
            $query->where('course_class_id', $classFilter);
        }
        if ($userFilter) {
            $query->where('user_id', $userFilter);
        }

        $enrollments = $query->paginate(20)->withQueryString();
        $classes = CourseClass::orderBy('title')->pluck('title', 'id');
        $users = User::orderBy('name')->pluck('name', 'id');

        return view('admin.course_enrollment.index', compact('enrollments', 'statusOptions', 'statusFilter', 'classes', 'classFilter', 'users', 'userFilter'));
    }

    public function create()
    {
        abort_unless(request()->user()?->hasPermission('manage-enrollment'), 403);
        $classes = CourseClass::orderBy('title')->pluck('title', 'id');
        $users = User::orderBy('name')->pluck('name', 'id');

        return view('admin.course_enrollment.form', [
            'enrollment' => new CourseEnrollment(['status' => 'active']),
            'classes' => $classes,
            'users' => $users,
            'action' => route('admin.course-enrollment.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        abort_unless($request->user()?->hasPermission('manage-enrollment'), 403);
        $data = $this->validateData($request);
        $data['created_by'] = $request->user()->id;
        $enrollment = CourseEnrollment::create($data);

        if ($enrollment->user) {
            $enrollment->user->notify(new EnrollmentCreated($enrollment));
        }

        $this->logger->log(
            $request->user(),
            'course.enrollment.created',
            "Enrollment user '{$enrollment->user->name}' ke kelas '{$enrollment->course->title}' ditambahkan",
            $enrollment
        );

        return redirect()->route('admin.course-enrollment.index')->with('success', 'Enrollment ditambahkan.');
    }

    public function edit(CourseEnrollment $course_enrollment)
    {
        abort_unless(request()->user()?->hasPermission('manage-enrollment'), 403);
        $classes = CourseClass::orderBy('title')->pluck('title', 'id');
        $users = User::orderBy('name')->pluck('name', 'id');

        return view('admin.course_enrollment.form', [
            'enrollment' => $course_enrollment,
            'classes' => $classes,
            'users' => $users,
            'action' => route('admin.course-enrollment.update', $course_enrollment->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, CourseEnrollment $course_enrollment)
    {
        abort_unless($request->user()?->hasPermission('manage-enrollment'), 403);
        $data = $this->validateData($request, $course_enrollment->id);
        $course_enrollment->update($data);

        if ($course_enrollment->user && $data['status'] === 'blocked') {
            $course_enrollment->user->notify(new EnrollmentBlocked($course_enrollment));
        }
        if ($course_enrollment->user && $data['status'] === 'active' && $course_enrollment->wasChanged('status')) {
            $course_enrollment->user->notify(new EnrollmentCreated($course_enrollment));
        }

        $this->logger->log(
            $request->user(),
            'course.enrollment.updated',
            "Enrollment user '{$course_enrollment->user->name}' ke kelas '{$course_enrollment->course->title}' diperbarui",
            $course_enrollment
        );

        return redirect()->route('admin.course-enrollment.index')->with('success', 'Enrollment diperbarui.');
    }

    public function destroy(CourseEnrollment $course_enrollment)
    {
        abort_unless(request()->user()?->hasPermission('manage-enrollment'), 403);
        $this->logger->log(
            request()->user(),
            'course.enrollment.deleted',
            "Enrollment user '{$course_enrollment->user->name}' ke kelas '{$course_enrollment->course->title}' dihapus",
            $course_enrollment
        );
        $course_enrollment->delete();
        return redirect()->route('admin.course-enrollment.index')->with('success', 'Enrollment dihapus.');
    }

    private function validateData(Request $request, ?string $ignoreId = null): array
    {
        $rules = [
            'course_class_id' => 'required|exists:course_classes,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:' . implode(',', array_keys(CourseEnrollment::statuses())),
            'muted_until' => 'nullable|date|after:now',
        ];

        if ($ignoreId) {
            // unique combination
            $rules['user_id'] .= '|unique:course_enrollments,user_id,' . $ignoreId . ',id,course_class_id,' . $request->input('course_class_id');
        } else {
            $rules['user_id'] .= '|unique:course_enrollments,user_id,NULL,id,course_class_id,' . $request->input('course_class_id');
        }

        return $request->validate($rules);
    }
}
