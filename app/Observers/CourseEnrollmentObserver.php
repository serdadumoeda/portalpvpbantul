<?php

namespace App\Observers;

use App\Models\CourseEnrollment;
use App\Models\CourseSubmission;
use App\Models\Role;

class CourseEnrollmentObserver
{
    public function saved(CourseEnrollment $enrollment): void
    {
        $this->maybePromoteToAlumni($enrollment);
    }

    private function maybePromoteToAlumni(CourseEnrollment $enrollment): void
    {
        $user = $enrollment->user;
        if (! $user) {
            return;
        }

        $hasCompletedFlag = ! empty($enrollment->completed_at);
        $hasPassedAllRequired = $this->hasPassedAllAssignments($enrollment);

        if (! $hasCompletedFlag && ! $hasPassedAllRequired) {
            return;
        }

        $alumniRole = Role::where('name', 'alumni')->first();
        if (! $alumniRole) {
            return;
        }

        // keep existing roles; just ensure alumni attached
        if (! $user->roles()->where('roles.id', $alumniRole->id)->exists()) {
            $user->roles()->attach($alumniRole->id);
        }
    }

    private function hasPassedAllAssignments(CourseEnrollment $enrollment): bool
    {
        $assignmentIds = optional($enrollment->course)
            ? $enrollment->course->assignments()->where('status', 'published')->pluck('id')
            : collect();

        if ($assignmentIds->isEmpty()) {
            return false;
        }

        $userId = $enrollment->user_id;
        $completedCount = CourseSubmission::whereIn('course_assignment_id', $assignmentIds)
            ->where('user_id', $userId)
            ->whereNotNull('total_score')
            ->count();

        return $completedCount >= $assignmentIds->count();
    }
}
