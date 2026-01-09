<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumni_tracers', function (Blueprint $table) {
            $table->string('gender', 12)->nullable()->after('phone');
            $table->string('education_level', 20)->nullable()->after('training_batch');
        });
    }

    public function down(): void
    {
        Schema::table('alumni_tracers', function (Blueprint $table) {
            $table->dropColumn(['gender', 'education_level']);
        });
    }
};
