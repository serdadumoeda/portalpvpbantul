<?php

namespace App\Traits;

use App\Models\Reaction;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Model;

trait HasReactions
{
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactionable');
    }

    public function addReaction(string $type, Model $user): Reaction
    {
        $existing = $this->reactions()->where('user_id', $user->id)->first();

        if ($existing) {
            if ($existing->type === $type) {
                return $existing;
            }

            $existing->update(['type' => $type]);
            return $existing;
        }

        $reaction = $this->reactions()->create([
            'user_id' => $user->id,
            'type' => $type,
        ]);

        $this->increment('reaction_count');

        return $reaction;
    }
}
