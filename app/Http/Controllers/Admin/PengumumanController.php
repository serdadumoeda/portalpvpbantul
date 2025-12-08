<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengumuman;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    /**
     * 1. Menampilkan daftar pengumuman (Read)
     */
    public function index()
    {
        // Mengambil data terbaru dengan pagination (10 per halaman)
        $pengumuman = Pengumuman::latest()->paginate(10);
        return view('admin.pengumuman.index', compact('pengumuman'));
    }

    /**
     * 2. Menampilkan form tambah pengumuman (Create View)
     */
    public function create()
    {
        return view('admin.pengumuman.create');
    }

    /**
     * 3. Menyimpan data baru ke database (Store)
     */
    public function store(Request $request)
    {
        // Validasi Input
        $request->validate([
            'judul' => 'required|max:255',
            'isi'   => 'required',
            'file_download' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120', // Maks 5MB, format dokumen
        ]);

        $filePath = null;

        // Logika Upload File
        if ($request->hasFile('file_download')) {
            // Simpan ke folder 'storage/app/public/files'
            $path = $request->file('file_download')->store('files', 'public');
            // Simpan path yang bisa diakses publik
            $filePath = '/storage/' . $path;
        }

        // Simpan ke Database
        Pengumuman::create([
            'judul' => $request->judul,
            'slug'  => Str::slug($request->judul) . '-' . time(), // Slug unik
            'isi'   => $request->isi,
            'file_download' => $filePath,
        ]);

        return redirect()->route('admin.pengumuman.index')
                         ->with('success', 'Pengumuman berhasil ditambahkan!');
    }

    /**
     * 4. Menampilkan form edit (Edit View)
     */
    public function edit($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    /**
     * 5. Mengupdate data yang sudah ada (Update)
     */
    public function update(Request $request, $id)
    {
        // Validasi
        $request->validate([
            'judul' => 'required|max:255',
            'isi'   => 'required',
            'file_download' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $pengumuman = Pengumuman::findOrFail($id);

        // Data yang akan diupdate
        $dataToUpdate = [
            'judul' => $request->judul,
            // Update slug jika judul berubah (opsional, tapi baik untuk SEO)
            'slug'  => Str::slug($request->judul) . '-' . $pengumuman->id, 
            'isi'   => $request->isi,
        ];

        // Logika Ganti File
        if ($request->hasFile('file_download')) {
            // 1. Hapus file lama jika ada (bersihkan storage)
            if ($pengumuman->file_download) {
                // Konversi path URL (/storage/files/...) kembali ke path disk (files/...)
                $oldPath = str_replace('/storage/', '', $pengumuman->file_download);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // 2. Upload file baru
            $path = $request->file('file_download')->store('files', 'public');
            $dataToUpdate['file_download'] = '/storage/' . $path;
        }

        // Update database
        $pengumuman->update($dataToUpdate);

        return redirect()->route('admin.pengumuman.index')
                         ->with('success', 'Pengumuman berhasil diperbarui!');
    }

    /**
     * 6. Menghapus data dan file (Delete)
     */
    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        // Hapus file fisik dari penyimpanan jika ada
        if ($pengumuman->file_download) {
            $path = str_replace('/storage/', '', $pengumuman->file_download);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        // Hapus record dari database
        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')
                         ->with('success', 'Pengumuman berhasil dihapus.');
    }
}