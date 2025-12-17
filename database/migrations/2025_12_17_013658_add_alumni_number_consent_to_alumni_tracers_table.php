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
        Schema::table('alumni_tracers', function (Blueprint $table) {
            $table->string('alumni_number')->unique()->after('national_id');
            $table->boolean('consent_given')->default(false)->after('platform_origin');
            $table->timestamp('consent_at')->nullable()->after('consent_given');
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete()->after('consent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumni_tracers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'alumni_number', 'consent_given', 'consent_at']);
        });
    }
};
