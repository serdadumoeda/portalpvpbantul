<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrainingServiceController extends Controller
{
    public function index()
    {
        $services = TrainingService::orderBy('urutan')->get();
        return view('admin.training_service.index', compact('services'));
    }

    public function create()
    {
        return view('admin.training_service.form', [
            'service' => new TrainingService(),
            'action' => route('admin.training-service.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('training_services', 'public');
            $data['gambar'] = '/storage/' . $path;
        }
        TrainingService::create($data);
        return redirect()->route('admin.training-service.index')->with('success', 'Layanan pelatihan ditambahkan.');
    }

    public function edit(TrainingService $training_service)
    {
        return view('admin.training_service.form', [
            'service' => $training_service,
            'action' => route('admin.training-service.update', $training_service->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, TrainingService $training_service)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('gambar')) {
            if ($training_service->gambar && Storage::exists(str_replace('/storage/', 'public/', $training_service->gambar))) {
                // Storage::delete(str_replace('/storage/', 'public/', $training_service->gambar));
            }
            $path = $request->file('gambar')->store('training_services', 'public');
            $data['gambar'] = '/storage/' . $path;
        }
        $training_service->update($data);
        return redirect()->route('admin.training-service.index')->with('success', 'Layanan pelatihan diperbarui.');
    }

    public function destroy(TrainingService $training_service)
    {
        $training_service->delete();
        return redirect()->route('admin.training-service.index')->with('success', 'Layanan pelatihan dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'fasilitas' => 'nullable|string',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'gambar' => 'nullable|image|max:2048',
        ]);
    }
}
