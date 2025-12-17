<?php

namespace App\Services;

use App\Models\ForumBadge;
use App\Models\User;
use App\Models\UserBadge;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BadgeService
{
    public static function ensureBadge(string $name, string $label, ?string $description = null): ForumBadge
    {
        return ForumBadge::firstOrCreate(
            ['name' => $name],
            ['label' => $label, 'description' => $description]
        );
    }

    public static function award(User $user, string $badgeName, ?string $description = null): void
    {
        $badge = self::ensureBadge($badgeName, Str::title(str_replace('-', ' ', $badgeName)), $description);

        UserBadge::firstOrCreate([
            'user_id' => $user->id,
            'badge_id' => $badge->id,
        ], [
            'awarded_at' => Carbon::now(),
        ]);
    }

    public static function evaluate(User $user): void
    {
        $topics = $user->forumTopics()->approved()->count();
        $posts = $user->forumPosts()->approved()->count();
        $totalContributions = $topics + $posts;

        if ($totalContributions >= 5) {
            self::award($user, 'kontributor-aktif', 'Sudah berkontribusi minimal 5 kali.');
        }

        if ($posts >= 3 && $topics >= 2) {
            self::award($user, 'mentor-alumni', 'Sering membantu alumni lain di forum.');
        }
    }
}
