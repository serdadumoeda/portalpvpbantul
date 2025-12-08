<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfographicYear;
use Illuminate\Http\Request;

class InfographicYearController extends Controller
{
    public function index()
    {
        $years = InfographicYear::orderBy('urutan')->get();
        return view('admin.infographic.year.index', compact('years'));
    }

    public function create()
    {
        return view('admin.infographic.year.form', [
            'year' => new InfographicYear(),
            'action' => route('admin.infographic-year.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('hero_image')) {
            $data['hero_image'] = '/storage/' . $request->file('hero_image')->store('infographics', 'public');
        }
        InfographicYear::create($data);
        return redirect()->route('admin.infographic-year.index')->with('success', 'Data infografis ditambahkan.');
    }

    public function edit(InfographicYear $infographic_year)
    {
        return view('admin.infographic.year.form', [
            'year' => $infographic_year,
            'action' => route('admin.infographic-year.update', $infographic_year->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, InfographicYear $infographic_year)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('hero_image')) {
            $data['hero_image'] = '/storage/' . $request->file('hero_image')->store('infographics', 'public');
        }
        $infographic_year->update($data);
        return redirect()->route('admin.infographic-year.index')->with('success', 'Data infografis diperbarui.');
    }

    public function destroy(InfographicYear $infographic_year)
    {
        $infographic_year->delete();
        return redirect()->route('admin.infographic-year.index')->with('success', 'Data infografis dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'tahun' => 'required|string|max:10',
            'title' => 'nullable|string|max:255',
            'headline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'hero_button_text' => 'nullable|string|max:100',
            'hero_button_link' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'hero_image' => 'nullable|image|max:2048',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        return $data;
    }
}
