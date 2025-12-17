<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forum_topics', function (Blueprint $table) {
            $table->unsignedInteger('reaction_count')->default(0)->after('is_pinned');
        });

        Schema::table('forum_posts', function (Blueprint $table) {
            $table->unsignedInteger('reaction_count')->default(0)->after('content');
        });

        Schema::create('forum_reactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->uuidMorphs('reactionable');
            $table->string('type')->default('like');
            $table->timestamps();
            $table->unique(['user_id', 'reactionable_type', 'reactionable_id'], 'forum_reaction_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_reactions');

        Schema::table('forum_posts', function (Blueprint $table) {
            $table->dropColumn('reaction_count');
        });

        Schema::table('forum_topics', function (Blueprint $table) {
            $table->dropColumn('reaction_count');
        });
    }
};
