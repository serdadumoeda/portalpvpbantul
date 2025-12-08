<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_service_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('hero_button_text')->nullable();
            $table->string('hero_button_link')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('intro_title')->nullable();
            $table->string('intro_description')->nullable();
            $table->longText('intro_content')->nullable();
            $table->string('regulation_title')->nullable();
            $table->longText('regulation_items')->nullable();
            $table->string('policy_title')->nullable();
            $table->string('policy_subtitle')->nullable();
            $table->text('policy_description')->nullable();
            $table->string('policy_signature')->nullable();
            $table->string('policy_position')->nullable();
            $table->string('policy_image')->nullable();
            $table->string('standard_title')->nullable();
            $table->text('standard_description')->nullable();
            $table->string('standard_document_title')->nullable();
            $table->text('standard_document_description')->nullable();
            $table->string('standard_document_file')->nullable();
            $table->string('standard_document_badge')->nullable();
            $table->string('flow_section_title')->nullable();
            $table->text('flow_section_description')->nullable();
            $table->string('cta_title')->nullable();
            $table->text('cta_description')->nullable();
            $table->string('cta_primary_text')->nullable();
            $table->string('cta_primary_link')->nullable();
            $table->string('cta_secondary_text')->nullable();
            $table->string('cta_secondary_link')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_service_settings');
    }
};
