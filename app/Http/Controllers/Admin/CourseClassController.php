<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseClass;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class CourseClassController extends Controller
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function index()
    {
        $statusOptions = CourseClass::statuses();
        $statusFilter = request('status');

        $query = CourseClass::orderBy('created_at', 'desc');
        if ($statusFilter && array_key_exists($statusFilter, $statusOptions)) {
            $query->where('status', $statusFilter);
        }

        $classes = $query->paginate(15)->withQueryString();

        return view('admin.course_class.index', compact('classes', 'statusOptions', 'statusFilter'));
    }

    public function create()
    {
        return view('admin.course_class.form', [
            'course' => new CourseClass(['format' => 'sinkron', 'is_active' => true]),
            'action' => route('admin.course-class.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['created_by'] = $request->user()->id;
        $this->applyWorkflow($request, $data);
        $course = CourseClass::create($data);

        $this->logger->log(
            $request->user(),
            'course.created',
            "Kelas '{$course->title}' ditambahkan",
            $course
        );

        return redirect()->route('admin.course-class.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(CourseClass $course_class)
    {
        return view('admin.course_class.form', [
            'course' => $course_class,
            'action' => route('admin.course-class.update', $course_class->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, CourseClass $course_class)
    {
        $data = $this->validateData($request);
        $this->applyWorkflow($request, $data, $course_class);
        $course_class->update($data);

        $this->logger->log(
            $request->user(),
            'course.updated',
            "Kelas '{$course_class->title}' diperbarui",
            $course_class
        );

        return redirect()->route('admin.course-class.index')->with('success', 'Kelas diperbarui.');
    }

    public function destroy(CourseClass $course_class)
    {
        $this->logger->log(
            request()->user(),
            'course.deleted',
            "Kelas '{$course_class->title}' dihapus",
            $course_class
        );
        $course_class->delete();
        return redirect()->route('admin.course-class.index')->with('success', 'Kelas dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'format' => 'required|in:sinkron,asinkron',
            'prerequisites' => 'nullable|string',
            'competencies' => 'nullable|string',
            'badge' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'status' => 'nullable|in:' . implode(',', array_keys(CourseClass::statuses())),
            'instructor_id' => 'nullable|exists:users,id',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['prerequisites'] = $data['prerequisites'] ? array_values(array_filter(array_map('trim', preg_split("/(\r?\n)+/", $data['prerequisites'])))) : null;
        $data['competencies'] = $data['competencies'] ? array_values(array_filter(array_map('trim', preg_split("/(\r?\n)+/", $data['competencies'])))) : null;

        return $data;
    }

    private function applyWorkflow(Request $request, array &$data, ?CourseClass $course = null): void
    {
        $defaultStatus = $request->user()->hasPermission('approve-content') ? 'published' : 'draft';
        $currentStatus = $course ? $course->status : $defaultStatus;
        $requestedStatus = $data['status'] ?? $currentStatus;

        if (! $request->user()->hasPermission('approve-content') && $requestedStatus === 'published') {
            $requestedStatus = 'pending';
        }

        $data['status'] = $requestedStatus ?: $currentStatus;

        if ($data['status'] === 'published') {
            $data['approved_by'] = $request->user()->id;
            $data['approved_at'] = now();
            $data['published_at'] = $course?->published_at ?? now();
        } else {
            $data['approved_by'] = null;
            $data['approved_at'] = null;
        }
    }
}
