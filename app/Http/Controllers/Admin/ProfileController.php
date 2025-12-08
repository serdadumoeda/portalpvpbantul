<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // 1. Tampilkan Daftar Halaman Profil
    public function index()
    {
        $profiles = Profile::all();
        return view('admin.profile.index', compact('profiles'));
    }

    // 2. Form Edit
    public function edit($id)
    {
        $profile = Profile::findOrFail($id);
        return view('admin.profile.edit', compact('profile'));
    }

    // 3. Simpan Perubahan
    public function update(Request $request, $id)
    {
        $profile = Profile::findOrFail($id);

        $request->validate([
            'judul' => 'required',
            // Konten boleh kosong jika cuma mau upload gambar (misal struktur)
            'gambar' => 'image|max:2048'
        ]);

        // Update Gambar jika ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama (opsional, praktik yang baik)
            if ($profile->gambar && Storage::exists('public/' . $profile->gambar)) {
               // Storage::delete('public/' . $profile->gambar);
            }
            
            // Simpan path gambar
            $path = $request->file('gambar')->store('profiles', 'public');
            $profile->gambar = '/storage/' . $path;
        }

        // Update Data
        $profile->judul = $request->judul;
        $profile->konten = $request->konten; // Hati-hati, ini format HTML
        $profile->save();

        return redirect()->route('admin.profile.index')->with('success', 'Profil berhasil diperbarui!');
    }
}