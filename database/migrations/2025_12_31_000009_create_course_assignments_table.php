<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('course_class_id')->constrained('course_classes')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type')->default('essay'); // essay/file/quiz
            $table->dateTime('due_at')->nullable();
            $table->integer('weight')->default(0);
            $table->integer('max_score')->default(100);
            $table->uuid('rubric_id')->nullable()->index();
            $table->string('late_policy')->default('no-accept'); // no-accept/penalty/allow
            $table->integer('penalty_percent')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('status')->default('draft');
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_assignments');
    }
};
