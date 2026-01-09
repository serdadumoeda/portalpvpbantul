<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->string('biaya_label', 255)->default('Gratis')->after('pendaftaran_link');
            $table->string('sertifikat_label', 255)->default('Sertifikat Mengikuti Pelatihan')->after('biaya_label');
            $table->string('bahasa_label', 255)->default('Bahasa Indonesia')->after('sertifikat_label');
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['biaya_label', 'sertifikat_label', 'bahasa_label']);
        });
    }
};
