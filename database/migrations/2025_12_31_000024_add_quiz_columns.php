<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_assignments', function (Blueprint $table) {
            $table->json('quiz_schema')->nullable()->after('description');
            $table->json('quiz_settings')->nullable()->after('quiz_schema');
        });

        Schema::table('course_submissions', function (Blueprint $table) {
            $table->json('quiz_answers')->nullable()->after('feedback');
            $table->integer('quiz_score')->nullable()->after('quiz_answers');
        });
    }

    public function down(): void
    {
        Schema::table('course_assignments', function (Blueprint $table) {
            $table->dropColumn(['quiz_schema', 'quiz_settings']);
        });

        Schema::table('course_submissions', function (Blueprint $table) {
            $table->dropColumn(['quiz_answers', 'quiz_score']);
        });
    }
};
