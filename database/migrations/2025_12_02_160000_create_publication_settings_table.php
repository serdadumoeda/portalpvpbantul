<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publication_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('hero_title')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('hero_button_text')->nullable();
            $table->string('hero_button_link')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('intro_title')->nullable();
            $table->text('intro_description')->nullable();
            $table->string('alumni_title')->nullable();
            $table->text('alumni_description')->nullable();
            $table->string('alumni_video_url')->nullable();
            $table->string('downloads_title')->nullable();
            $table->text('downloads_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publication_settings');
    }
};
