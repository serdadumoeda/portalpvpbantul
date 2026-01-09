<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_assignments', function (Blueprint $table) {
            $table->boolean('require_token')->default(false)->after('quiz_settings');
            $table->string('exam_token')->nullable()->after('require_token');
            $table->timestamp('exam_start_at')->nullable()->after('exam_token');
            $table->timestamp('exam_end_at')->nullable()->after('exam_start_at');
            $table->boolean('auto_submit')->default(false)->after('exam_end_at');
        });
    }

    public function down(): void
    {
        Schema::table('course_assignments', function (Blueprint $table) {
            $table->dropColumn(['require_token', 'exam_token', 'exam_start_at', 'exam_end_at', 'auto_submit']);
        });
    }
};
