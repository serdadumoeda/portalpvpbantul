<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_sections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        Schema::table('survey_questions', function (Blueprint $table) {
            $table->foreignUuid('survey_section_id')->nullable()->after('survey_id')->constrained('survey_sections')->nullOnDelete();
            $table->json('validation')->nullable()->after('settings');
        });

        Schema::create('survey_skip_rules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->foreignUuid('survey_question_id')->constrained('survey_questions')->onDelete('cascade');
            $table->foreignUuid('target_section_id')->constrained('survey_sections')->onDelete('cascade');
            $table->json('conditions'); // contoh: { "selected_option_ids": ["..."], "equals_text": "ya" }
            $table->timestamps();
        });

        Schema::table('survey_answers', function (Blueprint $table) {
            $table->json('answer_json')->nullable()->after('answer_text');
            $table->string('file_path')->nullable()->after('answer_json');
        });
    }

    public function down(): void
    {
        Schema::table('survey_answers', function (Blueprint $table) {
            $table->dropColumn(['answer_json', 'file_path']);
        });

        Schema::table('survey_questions', function (Blueprint $table) {
            $table->dropForeign(['survey_section_id']);
            $table->dropColumn(['survey_section_id', 'validation']);
        });

        Schema::dropIfExists('survey_skip_rules');
        Schema::dropIfExists('survey_sections');
    }
};
