<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_forum_topics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('course_class_id')->constrained('course_classes')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('body')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_closed')->default(false);
            $table->timestamps();
        });

        Schema::create('course_forum_posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('course_forum_topic_id')->constrained('course_forum_topics')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();
        });

        Schema::create('course_forum_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('course_forum_post_id')->constrained('course_forum_posts')->cascadeOnDelete();
            $table->foreignUuid('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->text('reason')->nullable();
            $table->string('status')->default('open'); // open/resolved
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_forum_reports');
        Schema::dropIfExists('course_forum_posts');
        Schema::dropIfExists('course_forum_topics');
    }
};
