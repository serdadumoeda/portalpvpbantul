<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificationContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificationContentController extends Controller
{
    public function index()
    {
        $items = CertificationContent::orderBy('section')->orderBy('urutan')->get();
        return view('admin.certification_content.index', compact('items'));
    }

    public function create()
    {
        return view('admin.certification_content.form', [
            'item' => new CertificationContent(),
            'action' => route('admin.certification-content.store'),
            'method' => 'POST',
            'sections' => $this->sections(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('certification/sections', 'public');
            $data['image_path'] = '/storage/' . $path;
        }
        CertificationContent::create($data);
        return redirect()->route('admin.certification-content.index')->with('success', 'Konten sertifikasi ditambahkan.');
    }

    public function edit(CertificationContent $certification_content)
    {
        $certification_content->list_items_text = $certification_content->list_items ? implode("\n", $certification_content->list_items) : '';
        return view('admin.certification_content.form', [
            'item' => $certification_content,
            'action' => route('admin.certification-content.update', $certification_content->id),
            'method' => 'PUT',
            'sections' => $this->sections(),
        ]);
    }

    public function update(Request $request, CertificationContent $certification_content)
    {
        $data = $this->validatedData($request);
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('certification/sections', 'public');
            $data['image_path'] = '/storage/' . $path;
        }
        $certification_content->update($data);
        return redirect()->route('admin.certification-content.index')->with('success', 'Konten sertifikasi diperbarui.');
    }

    public function destroy(CertificationContent $certification_content)
    {
        if ($certification_content->image_path) {
            $storedPath = str_replace('/storage/', 'public/', $certification_content->image_path);
            if (Storage::exists($storedPath)) {
                // Storage::delete($storedPath);
            }
        }
        $certification_content->delete();
        return redirect()->route('admin.certification-content.index')->with('success', 'Konten sertifikasi dihapus.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'section' => 'required|string|max:100',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'badge' => 'nullable|string|max:100',
            'button_text' => 'nullable|string|max:150',
            'button_url' => 'nullable|string|max:255',
            'background' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'list_items' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $list = [];
        if (!empty($data['list_items'])) {
            $list = array_values(array_filter(array_map('trim', preg_split("/(\r?\n)+/", $data['list_items']))));
        }
        $data['list_items'] = $list ?: null;
        $data['is_active'] = $request->boolean('is_active');
        unset($data['gambar']);

        return $data;
    }

    private function sections(): array
    {
        return [
            'hero' => 'Hero Sertifikasi',
            'intro' => 'Intro LSP',
            'visi' => 'Visi',
            'misi' => 'Misi',
            'tujuan' => 'Tujuan',
            'highlight' => 'Highlight CTA',
        ];
    }
}
