<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('course_assignment_id')->constrained('course_assignments')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->longText('content_text')->nullable();
            $table->string('file_url')->nullable();
            $table->string('link_url')->nullable();
            $table->integer('version')->default(1);
            $table->boolean('late')->default(false);
            $table->integer('late_minutes')->nullable();
            $table->string('status')->default('submitted'); // submitted/graded/reopened
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->integer('total_score')->nullable();
            $table->foreignUuid('graded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('scores')->nullable(); // per kriteria
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_submissions');
    }
};
