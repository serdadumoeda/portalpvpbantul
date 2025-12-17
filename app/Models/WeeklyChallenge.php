<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class WeeklyChallenge extends Model
{
    use HasUuid;

    protected $fillable = [
        'title',
        'question',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public static function active(): ?self
    {
        return self::where('is_active', true)
            ->where('start_date', '<=', Carbon::today())
            ->where('end_date', '>=', Carbon::today())
            ->orderByDesc('start_date')
            ->first();
    }
}
