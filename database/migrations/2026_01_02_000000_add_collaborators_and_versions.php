<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_collaborators', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->string('role')->default('editor'); // owner/editor/viewer
            $table->timestamps();
            $table->unique(['survey_id', 'user_id']);
        });

        Schema::create('survey_versions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('snapshot');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_versions');
        Schema::dropIfExists('survey_collaborators');
    }
};
