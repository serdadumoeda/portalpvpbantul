<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WeeklyChallenge;
use Illuminate\Http\Request;

class WeeklyChallengeController extends Controller
{
    public function index()
    {
        $challenges = WeeklyChallenge::latest('start_date')->paginate(10);

        return view('admin.alumni_forum.challenge.index', compact('challenges'));
    }

    public function create()
    {
        return view('admin.alumni_forum.challenge.form', [
            'challenge' => new WeeklyChallenge(),
            'title' => 'Buat Challenge Mingguan',
            'action' => route('admin.alumni-forum.challenge.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:160',
            'question' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'sometimes|boolean',
        ]);

        if (! empty($data['is_active'])) {
            WeeklyChallenge::where('is_active', true)->update(['is_active' => false]);
        }

        WeeklyChallenge::create([
            'title' => $data['title'],
            'question' => $data['question'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'is_active' => ! empty($data['is_active']),
        ]);

        return redirect()->route('admin.alumni-forum.challenge.index')->with('success', 'Challenge berhasil disimpan.');
    }

    public function edit(WeeklyChallenge $weeklyChallenge)
    {
        return view('admin.alumni_forum.challenge.form', [
            'challenge' => $weeklyChallenge,
            'title' => 'Edit Challenge Mingguan',
            'action' => route('admin.alumni-forum.challenge.update', $weeklyChallenge),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, WeeklyChallenge $weeklyChallenge)
    {
        $data = $request->validate([
            'title' => 'required|string|max:160',
            'question' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'sometimes|boolean',
        ]);

        if (! empty($data['is_active'])) {
            WeeklyChallenge::where('is_active', true)
                ->where('id', '!=', $weeklyChallenge->id)
                ->update(['is_active' => false]);
        }

        $weeklyChallenge->update([
            'title' => $data['title'],
            'question' => $data['question'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'is_active' => ! empty($data['is_active']),
        ]);

        return redirect()->route('admin.alumni-forum.challenge.index')->with('success', 'Challenge berhasil diperbarui.');
    }

    public function destroy(WeeklyChallenge $weeklyChallenge)
    {
        $weeklyChallenge->delete();

        return back()->with('success', 'Challenge berhasil dihapus.');
    }
}
