<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ForumEngagement extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'date';
    protected $keyType = 'string';
    protected $fillable = [
        'date',
        'topics_created',
        'topics_approved',
        'posts_created',
        'posts_approved',
    ];

    public function scopeRecentWeek($query)
    {
        $weekAgo = Carbon::now()->subDays(6)->startOfDay();
        return $query->where('date', '>=', $weekAgo->toDateString())->orderBy('date');
    }

    public static function incrementField(string $field, ?Carbon $when = null): void
    {
        $when = $when ?? Carbon::now();
        $date = $when->toDateString();

        $record = self::firstOrCreate(['date' => $date]);
        $record->increment($field);
    }
}
