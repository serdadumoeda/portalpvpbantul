<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppid_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('nomor_identitas');
            $table->string('npwp')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('jenis_pemohon')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();
            $table->text('informasi_dimohon')->nullable();
            $table->text('tujuan_penggunaan')->nullable();
            $table->text('cara_memperoleh')->nullable();
            $table->string('tanda_tangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppid_requests');
    }
};
