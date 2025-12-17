<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->json('theme')->nullable()->after('settings'); // colors, font, cover_image
            $table->string('embed_token')->nullable()->unique()->after('slug');
            $table->boolean('restrict_to_logged_in')->default(false)->after('require_login');
            $table->boolean('allow_embed')->default(true)->after('allow_multiple_responses');
        });
    }

    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn(['theme', 'embed_token', 'restrict_to_logged_in', 'allow_embed']);
        });
    }
};
