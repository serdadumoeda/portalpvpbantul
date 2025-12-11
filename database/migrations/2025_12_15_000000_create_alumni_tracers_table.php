<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni_tracers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('national_id', 32)->nullable();
            $table->uuid('program_id')->nullable();
            $table->string('program_name')->nullable();
            $table->unsignedSmallInteger('graduation_year')->nullable();
            $table->string('training_batch')->nullable();
            $table->enum('status', ['employed', 'entrepreneur', 'studying', 'seeking', 'other'])->default('seeking');
            $table->string('job_title')->nullable();
            $table->string('company_name')->nullable();
            $table->string('industry_sector')->nullable();
            $table->date('job_start_date')->nullable();
            $table->string('employment_type')->nullable();
            $table->string('salary_range')->nullable();
            $table->boolean('continue_study')->default(false);
            $table->boolean('is_entrepreneur')->default(false);
            $table->string('business_name')->nullable();
            $table->string('business_sector')->nullable();
            $table->tinyInteger('satisfaction_rating')->nullable();
            $table->text('feedback')->nullable();
            $table->string('platform_origin')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('program_id')->references('id')->on('programs')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_tracers');
    }
};
