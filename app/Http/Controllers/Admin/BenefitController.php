<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Benefit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BenefitController extends Controller
{
    public function index()
    {
        $benefits = Benefit::orderBy('urutan')->get();
        return view('admin.benefit.index', compact('benefits'));
    }

    public function create()
    {
        return view('admin.benefit.form', [
            'benefit' => new Benefit(),
            'action' => route('admin.benefit.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('ikon_file')) {
            $path = $request->file('ikon_file')->store('benefits', 'public');
            $data['ikon'] = '/storage/' . $path;
        }
        Benefit::create($data);
        return redirect()->route('admin.benefit.index')->with('success', 'Benefit berhasil ditambahkan.');
    }

    public function edit(Benefit $benefit)
    {
        return view('admin.benefit.form', [
            'benefit' => $benefit,
            'action' => route('admin.benefit.update', $benefit->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, Benefit $benefit)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('ikon_file')) {
            if ($benefit->ikon && Storage::exists(str_replace('/storage/', 'public/', $benefit->ikon))) {
                // Storage::delete(str_replace('/storage/', 'public/', $benefit->ikon));
            }
            $path = $request->file('ikon_file')->store('benefits', 'public');
            $data['ikon'] = '/storage/' . $path;
        }
        $benefit->update($data);
        return redirect()->route('admin.benefit.index')->with('success', 'Benefit berhasil diperbarui.');
    }

    public function destroy(Benefit $benefit)
    {
        $benefit->delete();
        return redirect()->route('admin.benefit.index')->with('success', 'Benefit berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'ikon' => 'nullable|string|max:255',
            'ikon_file' => 'nullable|image|max:2048',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);
    }
}
