<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('published_at');
            $table->uuid('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->string('meta_title')->nullable()->after('excerpt');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('focus_keyword')->nullable()->after('meta_description');
        });

        Schema::table('pengumumen', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('isi');
            $table->uuid('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->string('meta_title')->nullable()->after('isi');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('focus_keyword')->nullable()->after('meta_description');
        });
    }

    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropColumn(['status', 'approved_by', 'approved_at', 'meta_title', 'meta_description', 'focus_keyword']);
        });

        Schema::table('pengumumen', function (Blueprint $table) {
            $table->dropColumn(['status', 'approved_by', 'approved_at', 'meta_title', 'meta_description', 'focus_keyword']);
        });
    }
};
