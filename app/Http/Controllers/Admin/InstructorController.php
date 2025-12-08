<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstructorController extends Controller
{
    public function index()
    {
        $instructors = Instructor::orderBy('urutan')->get();
        return view('admin.instructor.index', compact('instructors'));
    }

    public function create()
    {
        return view('admin.instructor.form', [
            'instructor' => new Instructor(),
            'action' => route('admin.instructor.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('instructors', 'public');
            $data['foto'] = '/storage/' . $path;
        }
        Instructor::create($data);
        return redirect()->route('admin.instructor.index')->with('success', 'Instruktur berhasil ditambahkan.');
    }

    public function edit(Instructor $instructor)
    {
        return view('admin.instructor.form', [
            'instructor' => $instructor,
            'action' => route('admin.instructor.update', $instructor->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, Instructor $instructor)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('foto')) {
            if ($instructor->foto && Storage::exists(str_replace('/storage/', 'public/', $instructor->foto))) {
                // Storage::delete(str_replace('/storage/', 'public/', $instructor->foto));
            }
            $path = $request->file('foto')->store('instructors', 'public');
            $data['foto'] = '/storage/' . $path;
        }
        $instructor->update($data);
        return redirect()->route('admin.instructor.index')->with('success', 'Instruktur berhasil diperbarui.');
    }

    public function destroy(Instructor $instructor)
    {
        $instructor->delete();
        return redirect()->route('admin.instructor.index')->with('success', 'Instruktur berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nama' => 'required|string|max:255',
            'keahlian' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'linkedin' => 'nullable|url',
            'whatsapp' => 'nullable|string|max:30',
            'email' => 'nullable|email',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'foto' => 'nullable|image|max:2048',
        ]);
    }
}
