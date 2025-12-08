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
        Schema::create('pengumumen', function (Blueprint $table) { // perhatikan nama tabelnya
            $table->uuid('id')->primary();
            $table->string('judul');
            $table->string('slug');
            $table->text('isi');
            $table->string('file_download')->nullable(); // Jika ada PDF lampiran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumumen');
    }
};
