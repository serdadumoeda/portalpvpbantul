<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_badges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('label');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('user_badges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('badge_id')->constrained('forum_badges')->cascadeOnDelete();
            $table->timestamp('awarded_at')->useCurrent();
            $table->unique(['user_id', 'badge_id']);
        });

        Schema::create('weekly_challenges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('question');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_challenges');
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('forum_badges');
    }
};
