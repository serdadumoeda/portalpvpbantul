<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->foreignUuid('survey_instance_id')->nullable()->after('survey_id')->constrained('survey_instances')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropForeign(['survey_instance_id']);
            $table->dropColumn('survey_instance_id');
        });
    }
};
