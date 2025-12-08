<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Program; // Import Model Program
use Illuminate\Support\Facades\Storage; // Import Storage untuk hapus gambar

class ProgramController extends Controller
{
    /**
     * Menampilkan daftar semua program pelatihan.
     */
    public function index()
    {
        // Mengambil data terbaru dengan pagination (10 per halaman)
        $programs = Program::latest()->paginate(10);
        return view('admin.program.index', compact('programs'));
    }

    /**
     * Menampilkan form untuk menambah program baru.
     */
    public function create()
    {
        return view('admin.program.create');
    }

    /**
     * Menyimpan program baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'judul'     => 'required|min:5|max:255',
            'deskripsi' => 'required|min:10',
            'gambar'    => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Wajib ada gambar, maks 2MB
        ]);

        // 2. Upload Gambar
        $imagePath = null;
        if ($request->hasFile('gambar')) {
            // Simpan di folder: storage/app/public/programs
            $path = $request->file('gambar')->store('programs', 'public');
            $imagePath = '/storage/' . $path; // Path yang bisa diakses publik
        }

        // 3. Simpan ke Database
        Program::create([
            'judul'     => $request->judul,
            'deskripsi' => $request->deskripsi,
            'gambar'    => $imagePath
        ]);

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->route('admin.program.index')->with('success', 'Program pelatihan berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit program.
     */
    public function edit($id)
    {
        $program = Program::findOrFail($id);
        return view('admin.program.edit', compact('program'));
    }

    /**
     * Memperbarui data program di database.
     */
    public function update(Request $request, $id)
    {
        // 1. Validasi Input
        $request->validate([
            'judul'     => 'required|min:5|max:255',
            'deskripsi' => 'required|min:10',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Gambar boleh kosong (nullable) saat edit
        ]);

        // Ambil data program lama
        $program = Program::findOrFail($id);
        $dataToUpdate = [
            'judul'     => $request->judul,
            'deskripsi' => $request->deskripsi,
        ];

        // 2. Cek apakah user mengupload gambar baru
        if ($request->hasFile('gambar')) {
            
            // Hapus gambar lama dari penyimpanan server jika ada
            if ($program->gambar) {
                // Ubah path URL '/storage/...' kembali menjadi path relatif 'programs/...'
                $oldPath = str_replace('/storage/', '', $program->gambar);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Upload gambar baru
            $path = $request->file('gambar')->store('programs', 'public');
            $dataToUpdate['gambar'] = '/storage/' . $path;
        }

        // 3. Update Database
        $program->update($dataToUpdate);

        return redirect()->route('admin.program.index')->with('success', 'Program pelatihan berhasil diperbarui!');
    }

    /**
     * Menghapus program dari database.
     */
    public function destroy($id)
    {
        $program = Program::findOrFail($id);

        // Hapus file gambar dari server agar tidak memenuhi penyimpanan
        if ($program->gambar) {
            $oldPath = str_replace('/storage/', '', $program->gambar);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        // Hapus data dari tabel database
        $program->delete();

        return redirect()->route('admin.program.index')->with('success', 'Program pelatihan berhasil dihapus.');
    }
}