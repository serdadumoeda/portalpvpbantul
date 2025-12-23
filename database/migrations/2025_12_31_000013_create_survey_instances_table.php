<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_instances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('survey_id')->constrained('surveys')->cascadeOnDelete();
            $table->foreignUuid('course_class_id')->nullable()->constrained('course_classes')->nullOnDelete();
            $table->foreignUuid('instructor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('open'); // draft/open/closed
            $table->timestamp('opens_at')->nullable();
            $table->timestamp('closes_at')->nullable();
            $table->timestamp('triggered_at')->nullable();
            $table->unsignedInteger('min_responses_threshold')->default(5);
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_instances');
    }
};
