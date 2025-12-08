<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlowStep;
use Illuminate\Http\Request;

class FlowStepController extends Controller
{
    public function index()
    {
        $flows = FlowStep::orderBy('urutan')->get();
        return view('admin.flow.index', compact('flows'));
    }

    public function create()
    {
        return view('admin.flow.form', [
            'flow' => new FlowStep(),
            'action' => route('admin.flow.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        FlowStep::create($data);
        return redirect()->route('admin.flow.index')->with('success', 'Langkah alur ditambahkan.');
    }

    public function edit(FlowStep $flow)
    {
        return view('admin.flow.form', [
            'flow' => $flow,
            'action' => route('admin.flow.update', $flow->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, FlowStep $flow)
    {
        $data = $this->validateData($request);
        $flow->update($data);
        return redirect()->route('admin.flow.index')->with('success', 'Langkah alur diperbarui.');
    }

    public function destroy(FlowStep $flow)
    {
        $flow->delete();
        return redirect()->route('admin.flow.index')->with('success', 'Langkah alur dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);
    }
}
