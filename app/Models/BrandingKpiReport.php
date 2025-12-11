<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class BrandingKpiReport extends Model
{
    use HasUuid;

    protected $fillable = [
        'month',
        'year',
        'reach_previous',
        'reach_current',
        'reach_target',
        'reach_notes',
        'registrant_previous',
        'registrant_current',
        'registrant_target',
        'registrant_notes',
        'rating_previous',
        'rating_current',
        'rating_target',
        'rating_notes',
        'partner_previous',
        'partner_current',
        'partner_target',
        'partner_notes',
        'low_interest_program',
        'issue_description',
        'action_plan',
        'reported_by',
        'approved_by',
        'meeting_date',
        'evidence_link',
        'meeting_notes',
    ];

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
        'reach_previous' => 'integer',
        'reach_current' => 'integer',
        'reach_target' => 'integer',
        'registrant_previous' => 'integer',
        'registrant_current' => 'integer',
        'registrant_target' => 'integer',
        'rating_previous' => 'float',
        'rating_current' => 'float',
        'rating_target' => 'float',
        'partner_previous' => 'integer',
        'partner_current' => 'integer',
        'partner_target' => 'integer',
        'meeting_date' => 'date',
    ];

    public function getReachDifferenceAttribute(): ?int
    {
        return $this->reach_current !== null && $this->reach_previous !== null
            ? $this->reach_current - $this->reach_previous
            : null;
    }

    public function getRegistrantDifferenceAttribute(): ?int
    {
        return $this->registrant_current !== null && $this->registrant_previous !== null
            ? $this->registrant_current - $this->registrant_previous
            : null;
    }

    public function getRatingDifferenceAttribute(): ?float
    {
        return $this->rating_current !== null && $this->rating_previous !== null
            ? round($this->rating_current - $this->rating_previous, 2)
            : null;
    }

    public function getPartnerDifferenceAttribute(): ?int
    {
        return $this->partner_current !== null && $this->partner_previous !== null
            ? $this->partner_current - $this->partner_previous
            : null;
    }

    public function getReachAchievementAttribute(): ?float
    {
        return $this->calculateAchievement($this->reach_current, $this->reach_target);
    }

    public function getRegistrantAchievementAttribute(): ?float
    {
        return $this->calculateAchievement($this->registrant_current, $this->registrant_target);
    }

    public function getRatingAchievementAttribute(): ?float
    {
        return $this->calculateAchievement($this->rating_current, $this->rating_target);
    }

    public function getPartnerAchievementAttribute(): ?float
    {
        return $this->calculateAchievement($this->partner_current, $this->partner_target);
    }

    protected function calculateAchievement(?float $value, ?float $target): ?float
    {
        return $value !== null && $target !== null && $target > 0
            ? round(($value / $target) * 100, 1)
            : null;
    }

    public function monthName(): string
    {
        return \Carbon\Carbon::create($this->year, $this->month, 1)->translatedFormat('F');
    }

    public function lowInterestItems()
    {
        return $this->hasMany(BrandingKpiLowInterestItem::class, 'branding_kpi_report_id');
    }
}
