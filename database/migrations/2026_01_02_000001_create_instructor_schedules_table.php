<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructor_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->json('meta')->nullable();
            $table->json('days')->nullable();
            $table->json('rows')->nullable();
            $table->json('unit_descriptions')->nullable();
            $table->json('trainer')->nullable();
            $table->json('signatures')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructor_schedules');
    }
};
