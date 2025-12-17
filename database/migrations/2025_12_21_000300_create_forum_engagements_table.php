<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_engagements', function (Blueprint $table) {
            $table->date('date')->primary();
            $table->unsignedInteger('topics_created')->default(0);
            $table->unsignedInteger('topics_approved')->default(0);
            $table->unsignedInteger('posts_created')->default(0);
            $table->unsignedInteger('posts_approved')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_engagements');
    }
};
