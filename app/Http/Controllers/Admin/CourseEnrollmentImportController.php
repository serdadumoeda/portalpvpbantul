<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseClass;
use App\Models\CourseEnrollment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Validator;

class CourseEnrollmentImportController extends Controller
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function create()
    {
        $classes = CourseClass::orderBy('title')->pluck('title', 'id');
        return view('admin.course_enrollment.import', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_class_id' => 'required|exists:course_classes,id',
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $rows = array_map('str_getcsv', file($request->file('csv_file')->getRealPath()));
        $lineNumber = 0;
        $imported = 0;
        $skipped = 0;
        $errors = [];
        $classId = $request->input('course_class_id');

        foreach ($rows as $row) {
            $lineNumber++;
            if (count($row) < 1) {
                $skipped++;
                continue;
            }
            $email = trim($row[0]);
            if (empty($email)) {
                $skipped++;
                continue;
            }
            $validator = Validator::make(['email' => $email], [
                'email' => 'required|email|exists:users,email',
            ]);
            if ($validator->fails()) {
                $errors[] = "Baris {$lineNumber}: {$email} tidak valid atau tidak ditemukan.";
                $skipped++;
                continue;
            }
            $user = User::where('email', $email)->first();
            $exists = CourseEnrollment::where('course_class_id', $classId)->where('user_id', $user->id)->exists();
            if ($exists) {
                $skipped++;
                continue;
            }
            CourseEnrollment::create([
                'course_class_id' => $classId,
                'user_id' => $user->id,
                'status' => 'active',
                'created_by' => $request->user()->id,
            ]);
            $imported++;
        }

        $this->logger->log(
            $request->user(),
            'course.enrollment.import',
            "Import enrollment kelas {$classId}: {$imported} berhasil, {$skipped} dilewati",
            ['class_id' => $classId, 'imported' => $imported, 'skipped' => $skipped, 'errors' => $errors]
        );

        return redirect()
            ->route('admin.course-enrollment.index')
            ->with('success', "{$imported} enrollment ditambahkan, {$skipped} dilewati.")
            ->with('import_errors', $errors);
    }
}
