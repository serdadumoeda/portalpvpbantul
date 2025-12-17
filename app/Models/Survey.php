<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Survey extends Model
{
    use HasUuid;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'welcome_message',
        'thank_you_message',
        'is_active',
        'require_login',
        'allow_multiple_responses',
        'show_progress',
        'max_responses',
        'opens_at',
        'closes_at',
        'settings',
        'theme',
        'embed_token',
        'restrict_to_logged_in',
        'allow_embed',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'require_login' => 'boolean',
        'allow_multiple_responses' => 'boolean',
        'show_progress' => 'boolean',
        'restrict_to_logged_in' => 'boolean',
        'allow_embed' => 'boolean',
        'opens_at' => 'datetime',
        'closes_at' => 'datetime',
        'settings' => 'array',
        'theme' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $survey) {
            if (! $survey->slug) {
                $survey->slug = Str::slug($survey->title) . '-' . Str::random(5);
            }

            if (! $survey->embed_token) {
                $survey->embed_token = Str::random(24);
            }

            if (! $survey->created_by && auth()->check()) {
                $survey->created_by = auth()->id();
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(SurveyQuestion::class)->orderBy('position');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function collaborators(): HasMany
    {
        return $this->hasMany(SurveyCollaborator::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(SurveyVersion::class)->latest();
    }

    public function sections(): HasMany
    {
        return $this->hasMany(SurveySection::class)->orderBy('position');
    }

    public function skipRules(): HasMany
    {
        return $this->hasMany(SurveySkipRule::class);
    }

    public function scopeActive($query)
    {
        return $query
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('opens_at')->orWhere('opens_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('closes_at')->orWhere('closes_at', '>=', now());
            });
    }

    public function isOpen(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = now();
        if ($this->opens_at && $this->opens_at->isFuture()) {
            return false;
        }

        if ($this->closes_at && $this->closes_at->isPast()) {
            return false;
        }

        if ($this->max_responses) {
            $currentResponses = $this->responses_count ?? $this->responses()->count();
            if ($currentResponses >= $this->max_responses) {
                return false;
            }
        }

        return true;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
