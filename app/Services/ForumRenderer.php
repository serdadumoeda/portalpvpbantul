<?php

namespace App\Services;

class ForumRenderer
{
    public static function renderWithMentions(string $text): string
    {
        $escaped = e($text);

        $highlighted = preg_replace_callback(
            '/@([A-Za-z0-9_.]+)/',
            fn ($matches) => '<span class="text-primary fw-semibold">@' . e($matches[1]) . '</span>',
            $escaped
        );

        return nl2br($highlighted);
    }
}
