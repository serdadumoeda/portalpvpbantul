<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificationScheme;
use Illuminate\Http\Request;

class CertificationSchemeController extends Controller
{
    public function index()
    {
        $items = CertificationScheme::orderBy('category')->orderBy('urutan')->get();
        return view('admin.certification_scheme.index', compact('items'));
    }

    public function create()
    {
        return view('admin.certification_scheme.form', [
            'item' => new CertificationScheme(),
            'action' => route('admin.certification-scheme.store'),
            'method' => 'POST',
            'categories' => $this->categories(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('certification/schemes', 'public');
            $data['image_path'] = '/storage/' . $path;
        }
        CertificationScheme::create($data);
        return redirect()->route('admin.certification-scheme.index')->with('success', 'Skema sertifikasi ditambahkan.');
    }

    public function edit(CertificationScheme $certification_scheme)
    {
        return view('admin.certification_scheme.form', [
            'item' => $certification_scheme,
            'action' => route('admin.certification-scheme.update', $certification_scheme->id),
            'method' => 'PUT',
            'categories' => $this->categories(),
        ]);
    }

    public function update(Request $request, CertificationScheme $certification_scheme)
    {
        $data = $this->validatedData($request);
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('certification/schemes', 'public');
            $data['image_path'] = '/storage/' . $path;
        }
        $certification_scheme->update($data);
        return redirect()->route('admin.certification-scheme.index')->with('success', 'Skema sertifikasi diperbarui.');
    }

    public function destroy(CertificationScheme $certification_scheme)
    {
        $certification_scheme->delete();
        return redirect()->route('admin.certification-scheme.index')->with('success', 'Skema sertifikasi dihapus.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'category' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'cta_text' => 'nullable|string|max:150',
            'cta_url' => 'nullable|url|max:255',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['urutan'] = $data['urutan'] ?? 0;
        unset($data['gambar']);

        return $data;
    }

    private function categories(): array
    {
        return [
            'kluster' => 'Kluster',
            'okupasi' => 'Okupasi',
        ];
    }
}
