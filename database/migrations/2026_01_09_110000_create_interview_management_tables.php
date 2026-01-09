<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_enrollments', function (Blueprint $table) {
            $table->string('admin_status')->default('pending')->after('status');
            $table->text('admin_note')->nullable()->after('admin_status');
            $table->decimal('written_score', 5, 2)->nullable()->after('admin_note');
            $table->decimal('interview_score', 5, 2)->nullable()->after('written_score');
            $table->decimal('final_score', 5, 2)->nullable()->after('interview_score');
        });

        Schema::create('interview_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('training_schedule_id')->constrained('training_schedules')->cascadeOnDelete();
            $table->foreignUuid('interviewer_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location')->default('Ruang Wawancara BLK');
            $table->unsignedInteger('quota')->default(1);
            $table->timestamps();
        });

        Schema::create('interview_allocations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('interview_session_id')->constrained('interview_sessions')->cascadeOnDelete();
            $table->foreignUuid('course_enrollment_id')->constrained('course_enrollments')->cascadeOnDelete();
            $table->string('status')->default('SCHEDULED'); // SCHEDULED/ATTENDED/ABSENT
            $table->timestamps();
            $table->unique(['interview_session_id', 'course_enrollment_id']);
        });

        Schema::create('interview_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('interview_allocation_id')->constrained('interview_allocations')->cascadeOnDelete();
            $table->unsignedTinyInteger('score_communication')->default(0);
            $table->unsignedTinyInteger('score_motivation')->default(0);
            $table->unsignedTinyInteger('score_technical')->default(0);
            $table->text('interviewer_notes')->nullable();
            $table->decimal('final_score', 5, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_scores');
        Schema::dropIfExists('interview_allocations');
        Schema::dropIfExists('interview_sessions');

        Schema::table('course_enrollments', function (Blueprint $table) {
            $table->dropColumn(['admin_status', 'admin_note', 'written_score', 'interview_score', 'final_score']);
        });
    }
};
