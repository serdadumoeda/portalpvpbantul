<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empowerment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpowermentController extends Controller
{
    public function index()
    {
        $items = Empowerment::orderBy('urutan')->get();
        return view('admin.empowerment.index', compact('items'));
    }

    public function create()
    {
        return view('admin.empowerment.form', [
            'item' => new Empowerment(),
            'action' => route('admin.empowerment.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('empowerments', 'public');
            $data['gambar'] = '/storage/' . $path;
        }
        Empowerment::create($data);
        return redirect()->route('admin.empowerment.index')->with('success', 'Konten pemberdayaan ditambahkan.');
    }

    public function edit(Empowerment $empowerment)
    {
        return view('admin.empowerment.form', [
            'item' => $empowerment,
            'action' => route('admin.empowerment.update', $empowerment->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, Empowerment $empowerment)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('gambar')) {
            if ($empowerment->gambar && Storage::exists(str_replace('/storage/', 'public/', $empowerment->gambar))) {
                // Storage::delete(str_replace('/storage/', 'public/', $empowerment->gambar));
            }
            $path = $request->file('gambar')->store('empowerments', 'public');
            $data['gambar'] = '/storage/' . $path;
        }
        $empowerment->update($data);
        return redirect()->route('admin.empowerment.index')->with('success', 'Konten pemberdayaan diperbarui.');
    }

    public function destroy(Empowerment $empowerment)
    {
        $empowerment->delete();
        return redirect()->route('admin.empowerment.index')->with('success', 'Konten pemberdayaan dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'gambar' => 'nullable|image|max:2048',
        ]);
    }
}
