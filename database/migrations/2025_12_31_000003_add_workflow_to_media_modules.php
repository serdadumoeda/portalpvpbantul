<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->string('status')->default('published')->after('gambar');
            $table->uuid('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->timestamp('published_at')->nullable()->after('approved_at');
        });

        Schema::table('partners', function (Blueprint $table) {
            $table->string('status')->default('published')->after('is_active');
            $table->uuid('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->timestamp('published_at')->nullable()->after('approved_at');
        });

        Schema::table('benefits', function (Blueprint $table) {
            $table->string('status')->default('published')->after('is_active');
            $table->uuid('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->timestamp('published_at')->nullable()->after('approved_at');
        });

        Schema::table('instructors', function (Blueprint $table) {
            $table->string('status')->default('published')->after('is_active');
            $table->uuid('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->timestamp('published_at')->nullable()->after('approved_at');
        });

        Schema::table('training_services', function (Blueprint $table) {
            $table->string('status')->default('published')->after('is_active');
            $table->uuid('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->timestamp('published_at')->nullable()->after('approved_at');
        });

        Schema::table('empowerments', function (Blueprint $table) {
            $table->string('status')->default('published')->after('is_active');
            $table->uuid('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->timestamp('published_at')->nullable()->after('approved_at');
        });

        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->string('status')->default('published')->after('is_active');
            $table->uuid('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->timestamp('published_at')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        foreach (['galeris', 'partners', 'benefits', 'instructors', 'training_services', 'empowerments', 'job_vacancies'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn(['status', 'approved_by', 'approved_at', 'published_at']);
            });
        }
    }
};
