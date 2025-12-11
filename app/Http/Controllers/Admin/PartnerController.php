<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::orderBy('urutan')->get();
        return view('admin.partner.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.partner.form', [
            'partner' => new Partner(),
            'action' => route('admin.partner.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('partners', 'public');
            $data['logo'] = '/storage/' . $path;
        }
        Partner::create($data);
        return redirect()->route('admin.partner.index')->with('success', 'Partner berhasil ditambahkan.');
    }

    public function edit(Partner $partner)
    {
        return view('admin.partner.form', [
            'partner' => $partner,
            'action' => route('admin.partner.update', $partner->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, Partner $partner)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('logo')) {
            if ($partner->logo && Storage::exists(str_replace('/storage/', 'public/', $partner->logo))) {
                // Storage::delete(str_replace('/storage/', 'public/', $partner->logo));
            }
            $path = $request->file('logo')->store('partners', 'public');
            $data['logo'] = '/storage/' . $path;
        }
        $partner->update($data);
        return redirect()->route('admin.partner.index')->with('success', 'Partner berhasil diperbarui.');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();
        return redirect()->route('admin.partner.index')->with('success', 'Partner berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'tautan' => 'nullable|url|max:255',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data['urutan'] = $data['urutan'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
