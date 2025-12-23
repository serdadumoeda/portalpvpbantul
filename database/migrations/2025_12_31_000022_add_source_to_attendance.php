<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_attendances', function (Blueprint $table) {
            $table->foreignUuid('recorded_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            $table->string('recorded_source')->default('self')->after('recorded_by');
        });
    }

    public function down(): void
    {
        Schema::table('course_attendances', function (Blueprint $table) {
            $table->dropColumn(['recorded_by', 'recorded_source']);
        });
    }
};
