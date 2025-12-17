<?php

namespace App\Models;

use App\Models\Role;
use App\Models\User;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    use HasUuid;

    protected $fillable = [
        'email',
        'role_id',
        'token',
        'expires_at',
        'created_by_id',
        'used_by_id',
        'used_at',
        'revoked_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function usedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'used_by_id');
    }

    public function isExpired(): bool
    {
        return $this->expires_at?->isPast() ?? false;
    }

    public function isUsed(): bool
    {
        return (bool) $this->used_at;
    }

    public function isRevoked(): bool
    {
        return (bool) $this->revoked_at;
    }

    public function isValid(): bool
    {
        return ! $this->isExpired() && ! $this->isUsed() && ! $this->isRevoked();
    }

    public static function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }

    public static function findByToken(string $token): ?self
    {
        return static::where('token', static::hashToken($token))->first();
    }
}
