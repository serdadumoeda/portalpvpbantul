<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('welcome_message')->nullable();
            $table->text('thank_you_message')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('require_login')->default(false);
            $table->boolean('allow_multiple_responses')->default(true);
            $table->boolean('show_progress')->default(true);
            $table->unsignedInteger('max_responses')->nullable();
            $table->timestamp('opens_at')->nullable();
            $table->timestamp('closes_at')->nullable();
            $table->json('settings')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('survey_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->string('type');
            $table->string('question');
            $table->text('description')->nullable();
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('position')->default(0);
            $table->json('settings')->nullable();
            $table->string('placeholder')->nullable();
            $table->timestamps();
        });

        Schema::create('survey_question_options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('survey_question_id')->constrained('survey_questions')->onDelete('cascade');
            $table->string('label');
            $table->string('value')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_other')->default(false);
            $table->timestamps();
        });

        Schema::create('survey_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('session_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('survey_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('survey_response_id')->constrained('survey_responses')->onDelete('cascade');
            $table->foreignUuid('survey_question_id')->constrained('survey_questions')->onDelete('cascade');
            $table->text('answer_text')->nullable();
            $table->json('selected_option_ids')->nullable();
            $table->decimal('answer_numeric', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_answers');
        Schema::dropIfExists('survey_responses');
        Schema::dropIfExists('survey_question_options');
        Schema::dropIfExists('survey_questions');
        Schema::dropIfExists('surveys');
    }
};
