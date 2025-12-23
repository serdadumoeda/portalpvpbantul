<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Galeri; // Pastikan Model Galeri diimport
use Illuminate\Support\Facades\Storage; // Penting untuk menghapus file fisik
use App\Services\ActivityLogger;

class GaleriController extends Controller
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    /**
     * Menampilkan daftar galeri foto.
     */
    public function index(Request $request)
    {
        $statusOptions = Galeri::statuses();
        $statusFilter = $request->input('status');

        $query = Galeri::query()->latest();
        if ($statusFilter && array_key_exists($statusFilter, $statusOptions)) {
            $query->where('status', $statusFilter);
        }

        // Mengambil data terbaru dengan pagination (10 per halaman)
        $galeri = $query->paginate(10)->withQueryString();

        return view('admin.galeri.index', [
            'galeri' => $galeri,
            'statusOptions' => $statusOptions,
            'statusFilter' => $statusFilter,
        ]);
    }

    /**
     * Menampilkan form tambah foto baru.
     */
    public function create()
    {
        return view('admin.galeri.create');
    }

    /**
     * Menyimpan foto baru ke database dan folder storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Wajib ada gambar, maks 2MB
            'status' => 'nullable|in:' . implode(',', array_keys(Galeri::statuses())),
        ]);

        // 2. Proses Upload Gambar
        $imagePath = null;
        if ($request->hasFile('gambar')) {
            // Upload ke folder public/storage/galeri
            $path = $request->file('gambar')->store('galeri', 'public');
            $imagePath = '/storage/' . $path; // Path yang disimpan ke DB
        }

        // 3. Simpan ke Database
        $data = [
            'judul' => $request->judul,
            'gambar' => $imagePath,
            'status' => $request->input('status'),
        ];

        $this->applyWorkflow($request, $data);

        $galeri = Galeri::create($data);

        $this->logger->log(
            $request->user(),
            'galeri.created',
            "Galeri '{$request->judul}' ditambahkan",
            $galeri
        );

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto berhasil ditambahkan ke galeri!');
    }

    /**
     * Menampilkan form edit foto.
     */
    public function edit($id)
    {
        $galeri = Galeri::findOrFail($id);
        return view('admin.galeri.edit', compact('galeri'));
    }

    /**
     * Memperbarui data foto (Update).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'nullable|in:' . implode(',', array_keys(Galeri::statuses())),
        ]);

        $galeri = Galeri::findOrFail($id);

        // 1. Validasi
        $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Gambar boleh kosong jika tidak diganti
        ]);

        // 2. Persiapkan data update
        $data = [
            'judul' => $request->judul,
            'status' => $request->input('status', $galeri->status),
        ];

        // 3. Cek apakah user mengupload gambar baru
        if ($request->hasFile('gambar')) {
            
            // Hapus gambar lama fisik dari server agar tidak menumpuk
            // Kita perlu menghapus '/storage/' dari path database untuk mendapatkan path relatif storage
            $oldPath = str_replace('/storage/', '', $galeri->gambar);
            
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            // Upload gambar baru
            $path = $request->file('gambar')->store('galeri', 'public');
            $data['gambar'] = '/storage/' . $path;
        }

        $this->applyWorkflow($request, $data, $galeri);
        // 4. Update Database
        $galeri->update($data);

        $this->logger->log(
            $request->user(),
            'galeri.updated',
            "Galeri '{$galeri->judul}' diperbarui",
            $galeri
        );

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Data galeri berhasil diperbarui!');
    }

    /**
     * Menghapus foto dari database dan storage.
     */
    public function destroy($id)
    {
        $galeri = Galeri::findOrFail($id);

        // 1. Hapus File Fisik Gambar
        if ($galeri->gambar) {
            $path = str_replace('/storage/', '', $galeri->gambar);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        // 2. Hapus Record Database
        $galeri->delete();

        $this->logger->log(
            request()->user(),
            'galeri.deleted',
            "Galeri '{$galeri->judul}' dihapus",
            $galeri
        );

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto berhasil dihapus!');
    }

    private function applyWorkflow(Request $request, array &$data, ?Galeri $galeri = null): void
    {
        $defaultStatus = $request->user()->hasPermission('approve-content') ? 'published' : 'draft';
        $currentStatus = $galeri ? $galeri->status : $defaultStatus;
        $requestedStatus = $data['status'] ?? $currentStatus;

        if (! $request->user()->hasPermission('approve-content') && $requestedStatus === 'published') {
            $requestedStatus = 'pending';
        }

        $data['status'] = $requestedStatus ?: $currentStatus;

        if ($data['status'] === 'published') {
            $data['approved_by'] = $request->user()->id;
            $data['approved_at'] = now();
            $data['published_at'] = $galeri?->published_at ?? now();
        } else {
            $data['approved_by'] = null;
            $data['approved_at'] = null;
        }
    }
}
