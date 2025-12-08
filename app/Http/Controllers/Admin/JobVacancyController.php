<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use Illuminate\Http\Request;

class JobVacancyController extends Controller
{
    public function index()
    {
        $vacancies = JobVacancy::latest()->paginate(12);
        return view('admin.lowongan.index', compact('vacancies'));
    }

    public function create()
    {
        return view('admin.lowongan.form', [
            'vacancy' => new JobVacancy(),
            'action' => route('admin.lowongan.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        if ($request->hasFile('gambar')) {
            $data['gambar'] = '/storage/' . $request->file('gambar')->store('lowongan', 'public');
        }
        JobVacancy::create($data);
        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan berhasil ditambahkan.');
    }

    public function edit(JobVacancy $lowongan)
    {
        return view('admin.lowongan.form', [
            'vacancy' => $lowongan,
            'action' => route('admin.lowongan.update', $lowongan->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, JobVacancy $lowongan)
    {
        $data = $this->validatedData($request);
        if ($request->hasFile('gambar')) {
            $data['gambar'] = '/storage/' . $request->file('gambar')->store('lowongan', 'public');
        }
        $lowongan->update($data);
        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan diperbarui.');
    }

    public function destroy(JobVacancy $lowongan)
    {
        $lowongan->delete();
        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan dihapus.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'perusahaan' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'tipe_pekerjaan' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'kualifikasi' => 'nullable|string',
            'deadline' => 'nullable|date',
            'link_pendaftaran' => 'nullable|url',
            'is_active' => 'nullable|boolean',
            'gambar' => 'nullable|image|max:2048',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        return $data;
    }
}
