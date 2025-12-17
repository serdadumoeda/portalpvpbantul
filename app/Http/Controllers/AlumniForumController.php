<?php

namespace App\Http\Controllers;

use App\Models\ForumEngagement;
use App\Models\ForumPost;
use App\Models\ForumTopic;
use App\Models\User;
use App\Models\WeeklyChallenge;
use App\Services\SpamDetector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AlumniForumController extends Controller
{
    public function index()
    {
        $topics = ForumTopic::approved()
            ->with('user')
            ->withCount(['posts as replies_count' => function ($query) {
                $query->approved();
            }])
            ->orderByDesc('is_pinned')
            ->orderByDesc('updated_at')
            ->paginate(10);

        $stats = [
            'topics' => ForumTopic::approved()->count(),
            'responses' => ForumPost::approved()->count(),
            'alumni' => User::whereHas('roles', fn ($query) => $query->where('name', 'alumni'))->count(),
        ];

        $pendingTopicsCount = ForumTopic::where('is_approved', false)->count();
        $latestReplies = ForumPost::approved()->with(['user', 'topic'])->latest('created_at')->limit(5)->get();
        $engagementTrends = ForumEngagement::recentWeek()->get();
        $engagementSummary = [
            'topics_created' => $engagementTrends->sum('topics_created'),
            'posts_created' => $engagementTrends->sum('posts_created'),
            'topics_approved' => $engagementTrends->sum('topics_approved'),
            'posts_approved' => $engagementTrends->sum('posts_approved'),
        ];

        $popularTopics = ForumTopic::popular()->with('user')->take(3)->get();
        $topicCountExpr = '(SELECT count(*) FROM forum_topics WHERE forum_topics.user_id = users.id AND forum_topics.is_approved = true)';
        $postCountExpr = '(SELECT count(*) FROM forum_posts WHERE forum_posts.user_id = users.id AND forum_posts.is_approved = true)';

        $leaderboard = User::select('users.*')
            ->selectRaw("{$topicCountExpr} as approved_topics_count")
            ->selectRaw("{$postCountExpr} as approved_posts_count")
            ->whereHas('roles', fn ($query) => $query->where('name', 'alumni'))
            ->orderByDesc(DB::raw("{$topicCountExpr} + {$postCountExpr}"))
            ->orderByDesc(DB::raw("{$topicCountExpr}"))
            ->take(5)
            ->get();
        $challenge = WeeklyChallenge::active();

        return view('alumni.forum.index', compact(
            'topics',
            'stats',
            'pendingTopicsCount',
            'latestReplies',
            'engagementTrends',
            'engagementSummary',
            'popularTopics',
            'leaderboard',
            'challenge'
        ));
    }

    public function show(Request $request, ForumTopic $topic)
    {
        if (! $topic->is_approved
            && ! $request->user()->hasPermission('moderate-alumni-forum')
            && ! $topic->user->is($request->user())
        ) {
            abort(404);
        }

        $approvedPosts = $topic->approvedPosts()->with('user')->get();
        $pendingOwnPosts = $topic->posts()
            ->where('user_id', $request->user()->id)
            ->pending()
            ->get();

        return view('alumni.forum.show', [
            'topic' => $topic->load('user'),
            'approvedPosts' => $approvedPosts,
            'pendingOwnPosts' => $pendingOwnPosts,
        ]);
    }

    public function storeTopic(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        SpamDetector::detect($data['content']);

        $topic = ForumTopic::create([
            'title' => $data['title'],
            'slug' => $this->generateSlug($data['title']),
            'content' => $data['content'],
            'user_id' => $request->user()->id,
        ]);

        ForumEngagement::incrementField('topics_created');

        return redirect()->route('alumni.forum.show', $topic)
            ->with('success', 'Topik kamu berhasil dikirim dan sedang menunggu verifikasi admin.');
    }

    public function storePost(Request $request, ForumTopic $topic)
    {
        if ($topic->is_locked) {
            return back()->with('error', 'Topik ini telah ditutup.');
        }

        $data = $request->validate([
            'content' => ['required', 'string'],
        ]);

        SpamDetector::detect($data['content']);

        $topic->posts()->create([
            'content' => $data['content'],
            'user_id' => $request->user()->id,
        ]);

        $topic->touch();
        ForumEngagement::incrementField('posts_created');

        return redirect()->route('alumni.forum.show', $topic)
            ->with('success', 'Balasan kamu sudah dikirim ke admin untuk diverifikasi.');
    }

    private function generateSlug(string $title): string
    {
        $slug = Str::slug($title) ?: Str::random(8);
        $base = $slug;

        while (ForumTopic::where('slug', $slug)->exists()) {
            $slug = $base . '-' . substr(Str::uuid(), 0, 6);
        }

        return $slug;
    }
}
