<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('survey_questions', function (Blueprint $table) {
            $table->json('visibility_rules')->nullable()->after('validation'); // show/hide based on answers
        });

        Schema::create('survey_response_drafts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->json('data')->nullable();
            $table->timestamp('saved_at')->nullable();
            $table->timestamps();
        });

        Schema::table('survey_responses', function (Blueprint $table) {
            $table->json('audit')->nullable()->after('meta'); // device/os/referrer/fingerprint/finished_at
        });
    }

    public function down(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropColumn('audit');
        });
        Schema::dropIfExists('survey_response_drafts');
        Schema::table('survey_questions', function (Blueprint $table) {
            $table->dropColumn('visibility_rules');
        });
    }
};
