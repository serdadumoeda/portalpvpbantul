<?php

return [
    'reminders' => [
        'assignment_due_hours' => env('ASSIGNMENT_REMINDER_HOURS', 24),
        'session_start_minutes' => env('SESSION_REMINDER_MINUTES', 60),
        'grading_sla_hours' => env('GRADING_SLA_HOURS', 48),
        'survey_auto_trigger_enabled' => env('SURVEY_AUTO_TRIGGER', true),
        'survey_auto_close_days' => env('SURVEY_AUTO_CLOSE_DAYS', 7),
        'survey_post_class_id' => env('SURVEY_POST_CLASS_ID'), // id/slug survey pasca kelas
    ],
];
