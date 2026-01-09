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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik')->nullable()->unique()->after('name');
            $table->string('siap_kerja_id')->nullable()->unique()->after('email');
            $table->json('sso_payload')->nullable()->after('siap_kerja_id');
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->string('external_id')->nullable()->unique()->after('id');
        });

        Schema::table('training_schedules', function (Blueprint $table) {
            $table->string('external_id')->nullable()->unique()->after('id');
            $table->string('batch_id')->nullable()->unique()->after('external_id');
        });

        Schema::table('instructors', function (Blueprint $table) {
            $table->string('external_id')->nullable()->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nik', 'siap_kerja_id', 'sso_payload']);
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn('external_id');
        });

        Schema::table('training_schedules', function (Blueprint $table) {
            $table->dropColumn(['external_id', 'batch_id']);
        });

        Schema::table('instructors', function (Blueprint $table) {
            $table->dropColumn('external_id');
        });
    }
};
