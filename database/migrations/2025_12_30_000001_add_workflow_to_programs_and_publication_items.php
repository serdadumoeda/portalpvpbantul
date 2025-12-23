<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->string('status')->default('published')->after('gambar');
            $table->uuid('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->timestamp('published_at')->nullable()->after('approved_at');
        });

        Schema::table('publication_items', function (Blueprint $table) {
            $table->string('status')->default('published')->after('is_active');
            $table->uuid('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->timestamp('published_at')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['status', 'approved_by', 'approved_at', 'published_at']);
        });

        Schema::table('publication_items', function (Blueprint $table) {
            $table->dropColumn(['status', 'approved_by', 'approved_at', 'published_at']);
        });
    }
};
