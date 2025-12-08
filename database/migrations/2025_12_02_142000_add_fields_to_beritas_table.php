<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->string('kategori')->default('berita')->after('slug');
            $table->string('author')->nullable()->after('kategori');
            $table->text('excerpt')->nullable()->after('konten');
            $table->timestamp('published_at')->nullable()->after('excerpt');
        });
    }

    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'author', 'excerpt', 'published_at']);
        });
    }
};
