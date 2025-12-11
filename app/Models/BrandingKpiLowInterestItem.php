<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class BrandingKpiLowInterestItem extends Model
{
    use HasUuid;

    protected $fillable = [
        'branding_kpi_report_id',
        'program_name',
        'issue_description',
        'action_plan',
    ];

    public function report()
    {
        return $this->belongsTo(BrandingKpiReport::class, 'branding_kpi_report_id');
    }
}
