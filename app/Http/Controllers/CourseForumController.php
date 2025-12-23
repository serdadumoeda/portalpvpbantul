<?php

namespace App\Http\Controllers;

use App\Models\CourseClass;
use App\Models\CourseForumPost;
use App\Models\CourseForumReport;
use App\Models\CourseForumTopic;
use App\Models\CourseEnrollment;
use App\Notifications\ForumReplyAdded;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CourseForumController extends Controller
{
    public function index(Request $request, CourseClass $class)
    {
        $enrollment = $this->authorizeAccess($request->user(), $class->id);
        $topics = CourseForumTopic::with('user')
            ->withCount('posts')
            ->where('course_class_id', $class->id)
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('participant.forum.index', compact('class', 'topics', 'enrollment'));
    }

    public function storeTopic(Request $request, CourseClass $class)
    {
        $this->authorizeAccess($request->user(), $class->id, true);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
        ]);

        CourseForumTopic::create([
            'course_class_id' => $class->id,
            'user_id' => $request->user()->id,
            'title' => $data['title'],
            'body' => $data['body'] ?? null,
        ]);

        return back()->with('success', 'Topik ditambahkan.');
    }

    public function show(Request $request, CourseClass $class, CourseForumTopic $topic)
    {
        $enrollment = $this->authorizeAccess($request->user(), $class->id);
        abort_unless($topic->course_class_id === $class->id, 404);
        $posts = $topic->posts()->with('user')->paginate(20)->withQueryString();
        return view('participant.forum.show', compact('class', 'topic', 'posts', 'enrollment'));
    }

    public function storePost(Request $request, CourseClass $class, CourseForumTopic $topic)
    {
        $this->authorizeAccess($request->user(), $class->id, true);
        abort_unless($topic->course_class_id === $class->id, 404);
        $data = $request->validate([
            'body' => 'required|string',
        ]);

        CourseForumPost::create([
            'course_forum_topic_id' => $topic->id,
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        if ($topic->user && $topic->user->isNot($request->user())) {
            $topic->user->notify(new ForumReplyAdded($topic->title, $class->title ?? null, $request->user()->name));
        }

        return back()->with('success', 'Balasan ditambahkan.');
    }

    public function reportPost(Request $request, CourseForumPost $post)
    {
        $post->load('topic');
        $this->authorizeAccess($request->user(), $post->topic->course_class_id, true);
        $data = $request->validate([
            'reason' => 'nullable|string',
        ]);

        CourseForumReport::create([
            'course_forum_post_id' => $post->id,
            'reporter_id' => $request->user()->id,
            'reason' => $data['reason'] ?? null,
            'status' => 'open',
        ]);

        return back()->with('success', 'Laporan dikirim.');
    }

    private function authorizeAccess($user, string $classId, bool $forPosting = false): CourseEnrollment
    {
        $enrollment = CourseEnrollment::where('user_id', $user->id)
            ->where('course_class_id', $classId)
            ->where('status', 'active')
            ->first();

        if (! $enrollment) {
            throw ValidationException::withMessages(['access' => 'Anda tidak terdaftar di kelas ini.']);
        }

        if ($forPosting && $enrollment->muted_until && $enrollment->muted_until->isFuture()) {
            throw ValidationException::withMessages([
                'access' => 'Anda dibatasi berpartisipasi di forum hingga ' . $enrollment->muted_until->format('d M Y H:i'),
            ]);
        }

        return $enrollment;
    }
}
