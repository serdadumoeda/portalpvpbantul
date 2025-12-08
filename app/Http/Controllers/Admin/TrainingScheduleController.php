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
        return view('admin.training_schedule.form', [
            'schedule' => new TrainingSchedule(),
            'action' => route('admin.training-schedule.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        TrainingSchedule::create($data);
        return redirect()->route('admin.training-schedule.index')->with('success', 'Jadwal pelatihan ditambahkan.');
    }

    public function edit(TrainingSchedule $training_schedule)
    {
        return view('admin.training_schedule.form', [
            'schedule' => $training_schedule,
            'action' => route('admin.training-schedule.update', $training_schedule->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, TrainingSchedule $training_schedule)
    {
        $data = $this->validateData($request);
        $training_schedule->update($data);
        return redirect()->route('admin.training-schedule.index')->with('success', 'Jadwal pelatihan diperbarui.');
    }

    public function destroy(TrainingSchedule $training_schedule)
    {
        $training_schedule->delete();
        return redirect()->route('admin.training-schedule.index')->with('success', 'Jadwal pelatihan dihapus.');
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
