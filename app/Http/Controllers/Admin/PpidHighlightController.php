<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpidHighlight;
use Illuminate\Http\Request;

class PpidHighlightController extends Controller
{
    public function index()
    {
        $highlights = PpidHighlight::orderBy('urutan')->get();
        return view('admin.ppid.highlights.index', compact('highlights'));
    }

    public function create()
    {
        $highlight = new PpidHighlight(['is_active' => true]);
        return view('admin.ppid.highlights.form', compact('highlight'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        PpidHighlight::create($data);

        return redirect()->route('admin.ppid-highlight.index')->with('success', 'Highlight ditambahkan.');
    }

    public function edit(PpidHighlight $ppid_highlight)
    {
        return view('admin.ppid.highlights.form', ['highlight' => $ppid_highlight]);
    }

    public function update(Request $request, PpidHighlight $ppid_highlight)
    {
        $data = $this->validateData($request);
        $ppid_highlight->update($data);

        return redirect()->route('admin.ppid-highlight.index')->with('success', 'Highlight diperbarui.');
    }

    public function destroy(PpidHighlight $ppid_highlight)
    {
        $ppid_highlight->delete();

        return redirect()->route('admin.ppid-highlight.index')->with('success', 'Highlight dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }
}
