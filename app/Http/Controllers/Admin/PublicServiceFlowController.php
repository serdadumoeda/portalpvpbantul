<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicServiceFlow;
use Illuminate\Http\Request;

class PublicServiceFlowController extends Controller
{
    public function index()
    {
        $flows = PublicServiceFlow::orderBy('category')->orderBy('urutan')->get();
        return view('admin.pelayanan.flows.index', compact('flows'));
    }

    public function create()
    {
        $flow = new PublicServiceFlow(['is_active' => true]);
        return view('admin.pelayanan.flows.form', compact('flow'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('image')) {
            $data['image'] = '/storage/' . $request->file('image')->store('pelayanan', 'public');
        }

        PublicServiceFlow::create($data);

        return redirect()->route('admin.public-service-flow.index')->with('success', 'Alur pelayanan ditambahkan.');
    }

    public function edit(PublicServiceFlow $public_service_flow)
    {
        return view('admin.pelayanan.flows.form', ['flow' => $public_service_flow]);
    }

    public function update(Request $request, PublicServiceFlow $public_service_flow)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('image')) {
            $data['image'] = '/storage/' . $request->file('image')->store('pelayanan', 'public');
        }

        $public_service_flow->update($data);

        return redirect()->route('admin.public-service-flow.index')->with('success', 'Alur pelayanan diperbarui.');
    }

    public function destroy(PublicServiceFlow $public_service_flow)
    {
        $public_service_flow->delete();

        return redirect()->route('admin.public-service-flow.index')->with('success', 'Alur pelayanan dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'category' => 'nullable|string|max:100',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'steps' => 'nullable|string',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['category'] = $data['category'] ?? 'pelayanan';
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
