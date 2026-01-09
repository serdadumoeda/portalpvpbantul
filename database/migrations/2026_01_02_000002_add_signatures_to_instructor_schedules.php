<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instructor_schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('instructor_schedules', 'signatures')) {
                $table->json('signatures')->nullable()->after('trainer');
            }
        });
    }

    public function down(): void
    {
        Schema::table('instructor_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('instructor_schedules', 'signatures')) {
                $table->dropColumn('signatures');
            }
        });
    }
};
