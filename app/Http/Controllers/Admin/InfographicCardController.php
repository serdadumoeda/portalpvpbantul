<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfographicCard;
use App\Models\InfographicYear;
use Illuminate\Http\Request;

class InfographicCardController extends Controller
{
    public function index()
    {
        $cards = InfographicCard::with('year')->orderBy('infographic_year_id')->orderBy('urutan')->get();
        return view('admin.infographic.card.index', compact('cards'));
    }

    public function create()
    {
        return view('admin.infographic.card.form', [
            'card' => new InfographicCard(),
            'years' => InfographicYear::orderBy('tahun', 'desc')->pluck('tahun', 'id'),
            'action' => route('admin.infographic-card.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        InfographicCard::create($data);
        return redirect()->route('admin.infographic-card.index')->with('success', 'Card ditambahkan.');
    }

    public function edit(InfographicCard $infographic_card)
    {
        return view('admin.infographic.card.form', [
            'card' => $infographic_card,
            'years' => InfographicYear::orderBy('tahun', 'desc')->pluck('tahun', 'id'),
            'action' => route('admin.infographic-card.update', $infographic_card->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, InfographicCard $infographic_card)
    {
        $data = $this->validateData($request);
        $infographic_card->update($data);
        return redirect()->route('admin.infographic-card.index')->with('success', 'Card diperbarui.');
    }

    public function destroy(InfographicCard $infographic_card)
    {
        $infographic_card->delete();
        return redirect()->route('admin.infographic-card.index')->with('success', 'Card dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'infographic_year_id' => 'required|exists:infographic_years,id',
            'title' => 'required|string|max:255',
            'entries' => 'nullable|string',
            'urutan' => 'nullable|integer|min:0',
        ]);
        $entries = [];
        if (!empty($data['entries'])) {
            $entries = array_values(array_filter(array_map('trim', preg_split("/(\r?\n)+/", $data['entries']))));
        }
        $data['entries'] = $entries;
        $data['urutan'] = $data['urutan'] ?? 0;
        return $data;
    }
}
