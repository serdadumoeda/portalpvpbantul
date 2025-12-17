<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forum_topics', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false)->after('content');
            $table->foreignUuid('approved_by')->nullable()->after('is_approved')->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('moderation_note')->nullable()->after('approved_at');
        });

        Schema::table('forum_posts', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false)->after('content');
            $table->foreignUuid('approved_by')->nullable()->after('is_approved')->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('moderation_note')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['is_approved', 'approved_by', 'approved_at', 'moderation_note']);
        });

        Schema::table('forum_topics', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['is_approved', 'approved_by', 'approved_at', 'moderation_note']);
        });
    }
};
