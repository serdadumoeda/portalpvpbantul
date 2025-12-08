<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Galeri; // Pastikan Model Galeri diimport
use Illuminate\Support\Facades\Storage; // Penting untuk menghapus file fisik

class GaleriController extends Controller
{
    /**
     * Menampilkan daftar galeri foto.
     */
    public function index()
    {
        // Mengambil data terbaru dengan pagination (10 per halaman)
        $galeri = Galeri::latest()->paginate(10);
        return view('admin.galeri.index', compact('galeri'));
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
        ]);

        // 2. Proses Upload Gambar
        $imagePath = null;
        if ($request->hasFile('gambar')) {
            // Upload ke folder public/storage/galeri
            $path = $request->file('gambar')->store('galeri', 'public');
            $imagePath = '/storage/' . $path; // Path yang disimpan ke DB
        }

        // 3. Simpan ke Database
        Galeri::create([
            'judul' => $request->judul,
            'gambar' => $imagePath,
        ]);

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
        $galeri = Galeri::findOrFail($id);

        // 1. Validasi
        $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Gambar boleh kosong jika tidak diganti
        ]);

        // 2. Persiapkan data update
        $data = [
            'judul' => $request->judul,
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

        // 4. Update Database
        $galeri->update($data);

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

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto berhasil dihapus!');
    }
}