<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    // 1. Tampilkan Daftar Berita
    public function index()
    {
        $berita = Berita::latest()->paginate(10);
        return view('admin.berita.index', compact('berita'));
    }

    // 2. Tampilkan Form Tambah Berita
    public function create()
    {
        return view('admin.berita.create');
    }

    // 3. Simpan Berita ke Database
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required|max:255',
            'kategori' => 'required|string',
            'author' => 'nullable|string|max:255',
            'konten' => 'required',
            'excerpt' => 'nullable|string',
            'published_at' => 'nullable|date',
            'gambar_utama' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('gambar_utama')) {
            $data['gambar_utama'] = '/storage/' . $request->file('gambar_utama')->store('berita', 'public');
        }

        $data['slug'] = Str::slug($request->judul) . '-' . time();
        Berita::create($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan!');
    }

    // 4. Hapus Berita
    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        $berita->delete();
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }

    // 5. Tampilkan Form Edit (dengan data lama)
    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.berita.edit', compact('berita'));
    }

    // 6. Simpan Perubahan (Update)
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'judul' => 'required|max:255',
            'kategori' => 'required|string',
            'author' => 'nullable|string|max:255',
            'konten' => 'required',
            'excerpt' => 'nullable|string',
            'published_at' => 'nullable|date',
            'gambar_utama' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $berita = Berita::findOrFail($id);

        // Cek jika ada gambar baru yang diupload
        if ($request->hasFile('gambar_utama')) {
            $path = $request->file('gambar_utama')->store('berita', 'public');
            $data['gambar_utama'] = '/storage/' . $path;
        }

        $data['slug'] = Str::slug($request->judul) . '-' . $berita->id;
        $berita->update($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui!');
    }
}
