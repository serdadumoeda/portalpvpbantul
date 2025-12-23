<?php

namespace App\Console\Commands;

use App\Models\CourseClass;
use App\Models\Survey;
use App\Models\SurveyInstance;
use Illuminate\Console\Command;

class TriggerClassSurvey extends Command
{
    protected $signature = 'survey:trigger-class {survey} {class} {--instructor=} {--open-now} {--close-in=7}';

    protected $description = 'Buat survey instance untuk kelas (binding class/instructor) dan buka otomatis.';

    public function handle(): int
    {
        $surveyIdOrSlug = $this->argument('survey');
        $classId = $this->argument('class');
        $instructorId = $this->option('instructor');

        $survey = Survey::where('id', $surveyIdOrSlug)->orWhere('slug', $surveyIdOrSlug)->first();
        if (! $survey) {
            $this->error('Survey tidak ditemukan.');
            return self::FAILURE;
        }

        $class = CourseClass::find($classId);
        if (! $class) {
            $this->error('Kelas tidak ditemukan.');
            return self::FAILURE;
        }

        $openNow = $this->option('open-now');
        $closeIn = (int) $this->option('close-in');
        $now = now();

        $instance = SurveyInstance::create([
            'survey_id' => $survey->id,
            'course_class_id' => $class->id,
            'instructor_id' => $instructorId,
            'status' => $openNow ? 'open' : 'draft',
            'opens_at' => $openNow ? $now : null,
            'closes_at' => $openNow ? $now->copy()->addDays($closeIn) : null,
            'triggered_at' => $now,
            'min_responses_threshold' => 5,
            'created_by' => null,
        ]);

        $this->info("Survey instance dibuat: {$instance->id} (status: {$instance->status})");

        return self::SUCCESS;
    }
}
