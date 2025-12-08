<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('org_structures', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('jabatan')->nullable();
            $table->foreignUuid('parent_id')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        Schema::table('org_structures', function (Blueprint $table) {
            $table->foreign('parent_id')
                ->references('id')
                ->on('org_structures')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('org_structures');
    }
};
