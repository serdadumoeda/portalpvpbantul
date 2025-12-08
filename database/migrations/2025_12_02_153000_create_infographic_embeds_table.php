<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('infographic_embeds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('infographic_year_id')->constrained('infographic_years')->onDelete('cascade');
            $table->string('title');
            $table->string('url');
            $table->integer('height')->default(600);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infographic_embeds');
    }
};
