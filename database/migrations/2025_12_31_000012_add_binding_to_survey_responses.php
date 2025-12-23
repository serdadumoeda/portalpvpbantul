<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->foreignUuid('course_class_id')->nullable()->after('survey_id')->constrained('course_classes')->nullOnDelete();
            $table->foreignUuid('instructor_id')->nullable()->after('course_class_id')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropForeign(['course_class_id']);
            $table->dropColumn('course_class_id');
            $table->dropForeign(['instructor_id']);
            $table->dropColumn('instructor_id');
        });
    }
};
