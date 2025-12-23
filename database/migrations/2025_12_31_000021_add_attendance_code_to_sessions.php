<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_sessions', function (Blueprint $table) {
            $table->string('attendance_code', 50)->nullable()->after('meeting_link');
            $table->timestamp('attendance_code_expires_at')->nullable()->after('attendance_code');
        });
    }

    public function down(): void
    {
        Schema::table('course_sessions', function (Blueprint $table) {
            $table->dropColumn(['attendance_code', 'attendance_code_expires_at']);
        });
    }
};
