<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingSchedule;
use Illuminate\Http\Request;

class TrainingScheduleController extends Controller
{
    public function index()
    {
        $schedules = TrainingSchedule::orderBy('tahun', 'desc')->orderBy('bulan')->orderBy('mulai')->get();
        return view('admin.training_schedule.index', compact('schedules'));
    }

    public function create()
    {
        return redirect()->route('admin.training-schedule.index')->with('error', 'Pembuatan jadwal manual dinonaktifkan. Gunakan sinkronisasi.');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.training-schedule.index')->with('error', 'Pembuatan jadwal manual dinonaktifkan.');
    }

    public function show(TrainingSchedule $training_schedule)
    {
        return view('admin.training_schedule.show', [
            'schedule' => $training_schedule,
        ]);
    }

    public function edit(TrainingSchedule $training_schedule)
    {
        return $this->show($training_schedule);
    }

    public function update(Request $request, TrainingSchedule $training_schedule)
    {
        return redirect()->route('admin.training-schedule.index')->with('error', 'Perubahan jadwal manual dinonaktifkan.');
    }

    public function destroy(TrainingSchedule $training_schedule)
    {
        return redirect()->route('admin.training-schedule.index')->with('error', 'Penghapusan jadwal manual dinonaktifkan.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'judul' => 'required|string|max:255',
            'penyelenggara' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'mulai' => 'nullable|date',
            'selesai' => 'nullable|date',
            'kuota' => 'nullable|string|max:100',
            'bulan' => 'nullable|string|max:50',
            'tahun' => 'nullable|string|max:10',
            'pendaftaran_link' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);
    }
}
