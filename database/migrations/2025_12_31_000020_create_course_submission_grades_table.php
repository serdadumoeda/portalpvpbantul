<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_submission_grades', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('course_submission_id')->constrained('course_submissions')->cascadeOnDelete();
            $table->foreignUuid('graded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('total_score')->nullable();
            $table->json('scores')->nullable(); // per-kriteria
            $table->json('rubric_meta')->nullable(); // snapshot rubric at grading time
            $table->text('feedback')->nullable();
            $table->integer('version')->default(1);
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_submission_grades');
    }
};
