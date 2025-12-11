<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branding_kpi_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            $table->unsignedInteger('reach_previous')->nullable();
            $table->unsignedInteger('reach_current')->nullable();
            $table->string('reach_notes')->nullable();
            $table->unsignedInteger('registrant_previous')->nullable();
            $table->unsignedInteger('registrant_current')->nullable();
            $table->string('registrant_notes')->nullable();
            $table->decimal('rating_previous', 3, 2)->nullable();
            $table->decimal('rating_current', 3, 2)->nullable();
            $table->string('rating_notes')->nullable();
            $table->unsignedInteger('partner_previous')->nullable();
            $table->unsignedInteger('partner_current')->nullable();
            $table->string('partner_notes')->nullable();
            $table->string('low_interest_program')->nullable();
            $table->text('issue_description')->nullable();
            $table->text('action_plan')->nullable();
            $table->timestamps();
            $table->unique(['month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branding_kpi_reports');
    }
};
