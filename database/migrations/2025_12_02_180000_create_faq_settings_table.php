<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faq_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('hero_button_text')->nullable();
            $table->string('hero_button_link')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('intro_title')->nullable();
            $table->text('intro_description')->nullable();
            $table->string('info_title')->nullable();
            $table->text('info_description')->nullable();
            $table->string('info_stat_primary_label')->nullable();
            $table->string('info_stat_primary_value')->nullable();
            $table->string('info_stat_secondary_label')->nullable();
            $table->string('info_stat_secondary_value')->nullable();
            $table->string('contact_title')->nullable();
            $table->text('contact_description')->nullable();
            $table->string('contact_button_text')->nullable();
            $table->string('contact_button_link')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faq_settings');
    }
};
