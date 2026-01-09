<?php

namespace App\Http\Controllers;

use App\Models\InterviewAllocation;
use Illuminate\Http\Request;

class ParticipantInterviewController extends Controller
{
    public function index(Request $request)
    {
        $allocations = InterviewAllocation::with(['session.trainingSchedule', 'session.interviewer', 'score'])
            ->whereHas('enrollment', fn ($q) => $q->where('user_id', $request->user()->id))
            ->latest()
            ->get();

        return view('participant.interviews.index', compact('allocations'));
    }
}
