<?php

namespace App\Http\Controllers;

use App\Models\ForumPost;
use App\Models\ForumTopic;
use Illuminate\Http\Request;

class ForumReactionController extends Controller
{
    public function reactTopic(Request $request, ForumTopic $topic)
    {
        $request->validate(['type' => ['required', 'string']]);

        $topic->addReaction($request->type, $request->user());

        if ($topic->reaction_count >= 5 && ! $topic->is_pinned) {
            $topic->update(['is_pinned' => true]);
        }

        return back()->with('success', 'Terima kasih telah bereaksi!');
    }

    public function reactPost(Request $request, ForumPost $post)
    {
        $request->validate(['type' => ['required', 'string']]);

        $post->addReaction($request->type, $request->user());

        return back()->with('success', 'Reaksi kamu tersimpan.');
    }
}
