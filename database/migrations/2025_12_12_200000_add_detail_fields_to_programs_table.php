<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->text('kode_unit_kompetensi')->nullable()->after('deskripsi');
            $table->text('fasilitas_keunggulan')->nullable()->after('kode_unit_kompetensi');
            $table->text('info_tambahan')->nullable()->after('fasilitas_keunggulan');
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn([
                'kode_unit_kompetensi',
                'fasilitas_keunggulan',
                'info_tambahan',
            ]);
        });
    }
};
