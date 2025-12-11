<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('branding_kpi_reports', function (Blueprint $table) {
                $table->unsignedInteger('reach_target')->nullable()->after('reach_current');
                $table->unsignedInteger('registrant_target')->nullable()->after('registrant_current');
                $table->decimal('rating_target', 3, 2)->nullable()->after('rating_current');
                $table->unsignedInteger('partner_target')->nullable()->after('partner_current');
                $table->string('reported_by')->nullable()->after('action_plan');
                $table->string('approved_by')->nullable()->after('reported_by');
                $table->date('meeting_date')->nullable()->after('approved_by');
                $table->string('evidence_link')->nullable()->after('meeting_date');
                $table->text('meeting_notes')->nullable()->after('evidence_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branding_kpi_reports', function (Blueprint $table) {
            $table->dropColumn([
                'reach_target',
                'registrant_target',
                'rating_target',
                'partner_target',
                'reported_by',
                'approved_by',
                'meeting_date',
                'evidence_link',
                'meeting_notes',
            ]);
        });
    }
};
