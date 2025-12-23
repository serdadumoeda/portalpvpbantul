<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'certification_contents',
            'certification_schemes',
            'public_service_flows',
            'infographic_years',
            'infographic_metrics',
            'infographic_cards',
            'infographic_embeds',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                if (! Schema::hasColumn($table->getTable(), 'status')) {
                    $table->string('status')->default('published')->after('is_active');
                    $table->uuid('approved_by')->nullable()->after('status');
                    $table->timestamp('approved_at')->nullable()->after('approved_by');
                    $table->timestamp('published_at')->nullable()->after('approved_at');
                }
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'certification_contents',
            'certification_schemes',
            'public_service_flows',
            'infographic_years',
            'infographic_metrics',
            'infographic_cards',
            'infographic_embeds',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn(['status', 'approved_by', 'approved_at', 'published_at']);
            });
        }
    }
};
