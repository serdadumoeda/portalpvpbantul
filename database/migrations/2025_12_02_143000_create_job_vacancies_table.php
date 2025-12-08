<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('judul');
            $table->string('perusahaan')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('tipe_pekerjaan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('kualifikasi')->nullable();
            $table->date('deadline')->nullable();
            $table->string('link_pendaftaran')->nullable();
            $table->string('gambar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_vacancies');
    }
};
