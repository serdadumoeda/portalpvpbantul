<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfographicEmbed;
use App\Models\InfographicYear;
use Illuminate\Http\Request;

class InfographicEmbedController extends Controller
{
    public function index()
    {
        $embeds = InfographicEmbed::with('year')->orderBy('infographic_year_id')->orderBy('urutan')->get();
        return view('admin.infographic.embed.index', compact('embeds'));
    }

    public function create()
    {
        return view('admin.infographic.embed.form', [
            'embed' => new InfographicEmbed(),
            'years' => InfographicYear::orderBy('tahun', 'desc')->pluck('tahun', 'id'),
            'action' => route('admin.infographic-embed.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        InfographicEmbed::create($data);
        return redirect()->route('admin.infographic-embed.index')->with('success', 'Embed ditambahkan.');
    }

    public function edit(InfographicEmbed $infographic_embed)
    {
        return view('admin.infographic.embed.form', [
            'embed' => $infographic_embed,
            'years' => InfographicYear::orderBy('tahun', 'desc')->pluck('tahun', 'id'),
            'action' => route('admin.infographic-embed.update', $infographic_embed->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, InfographicEmbed $infographic_embed)
    {
        $data = $this->validateData($request);
        $infographic_embed->update($data);
        return redirect()->route('admin.infographic-embed.index')->with('success', 'Embed diperbarui.');
    }

    public function destroy(InfographicEmbed $infographic_embed)
    {
        $infographic_embed->delete();
        return redirect()->route('admin.infographic-embed.index')->with('success', 'Embed dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'infographic_year_id' => 'required|exists:infographic_years,id',
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'height' => 'nullable|integer|min:200|max:2000',
            'urutan' => 'nullable|integer|min:0',
        ]);
        $data['height'] = $data['height'] ?? 600;
        $data['urutan'] = $data['urutan'] ?? 0;
        return $data;
    }
}
