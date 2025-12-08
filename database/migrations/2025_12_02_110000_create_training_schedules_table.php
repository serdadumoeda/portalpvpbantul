<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('judul');
            $table->string('penyelenggara')->nullable();
            $table->string('lokasi')->nullable();
            $table->date('mulai')->nullable();
            $table->date('selesai')->nullable();
            $table->string('kuota')->nullable();
            $table->string('bulan')->nullable();
            $table->string('tahun')->nullable();
            $table->string('pendaftaran_link')->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_schedules');
    }
};
