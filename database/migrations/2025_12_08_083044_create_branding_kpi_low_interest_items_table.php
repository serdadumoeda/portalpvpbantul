<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('branding_kpi_low_interest_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('branding_kpi_report_id')->constrained('branding_kpi_reports')->cascadeOnDelete();
            $table->string('program_name');
            $table->text('issue_description')->nullable();
            $table->text('action_plan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branding_kpi_low_interest_items');
    }
};
