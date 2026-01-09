<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Program; // Import Model Program
use Illuminate\Support\Facades\Storage; // Import Storage untuk hapus gambar
use App\Services\ActivityLogger;

class ProgramController extends Controller
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    /**
     * Menampilkan daftar semua program pelatihan.
     */
    public function index(Request $request)
    {
        $statusOptions = Program::statuses();
        $query = Program::query()->latest();

        $statusFilter = $request->input('status');
        if ($statusFilter && array_key_exists($statusFilter, $statusOptions)) {
            $query->where('status', $statusFilter);
        }

        // Mengambil data terbaru dengan pagination (10 per halaman)
        $programs = $query->paginate(10)->withQueryString();

        return view('admin.program.index', [
            'programs' => $programs,
            'statusOptions' => $statusOptions,
            'statusFilter' => $statusFilter,
        ]);
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
            'pendaftaran_link' => 'nullable|url|max:500',
            'biaya_label' => 'nullable|string|max:255',
            'sertifikat_label' => 'nullable|string|max:255',
            'bahasa_label' => 'nullable|string|max:255',
            'kode_unit_kompetensi' => 'nullable|string|max:2000',
            'fasilitas_keunggulan' => 'nullable|string|max:2000',
            'info_tambahan' => 'nullable|string|max:2000',
            'gambar'    => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Wajib ada gambar, maks 2MB
            'status'    => 'nullable|in:' . implode(',', array_keys(Program::statuses())),
        ]);

        // 2. Upload Gambar
        $imagePath = null;
        if ($request->hasFile('gambar')) {
            // Simpan di folder: storage/app/public/programs
            $path = $request->file('gambar')->store('programs', 'public');
            $imagePath = '/storage/' . $path; // Path yang bisa diakses publik
        }

        // 3. Simpan ke Database
        $data = [
            'judul'     => $this->sanitizePlain($request->judul),
            'deskripsi' => $this->sanitizeRich($request->deskripsi),
            'pendaftaran_link' => $this->sanitizePlain($request->pendaftaran_link),
            'biaya_label' => $this->sanitizePlain($request->biaya_label ?: 'Gratis'),
            'sertifikat_label' => $this->sanitizePlain($request->sertifikat_label ?: 'Sertifikat Mengikuti Pelatihan'),
            'bahasa_label' => $this->sanitizePlain($request->bahasa_label ?: 'Bahasa Indonesia'),
            'kode_unit_kompetensi' => $this->sanitizeRich($request->kode_unit_kompetensi),
            'fasilitas_keunggulan' => $this->sanitizeRich($request->fasilitas_keunggulan),
            'info_tambahan' => $this->sanitizeRich($request->info_tambahan),
            'gambar'    => $imagePath,
            'status'    => $request->input('status'),
        ];

        $this->applyWorkflow($request, $data);

        $program = Program::create($data);

        $this->logger->log(
            $request->user(),
            'program.created',
            "Program '{$request->judul}' ditambahkan",
            $program
        );

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
            'pendaftaran_link' => 'nullable|url|max:500',
            'biaya_label' => 'nullable|string|max:255',
            'sertifikat_label' => 'nullable|string|max:255',
            'bahasa_label' => 'nullable|string|max:255',
            'kode_unit_kompetensi' => 'nullable|string|max:2000',
            'fasilitas_keunggulan' => 'nullable|string|max:2000',
            'info_tambahan' => 'nullable|string|max:2000',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Gambar boleh kosong (nullable) saat edit
        ]);

        // Ambil data program lama
        $program = Program::findOrFail($id);
        $dataToUpdate = [
            'judul'     => $this->sanitizePlain($request->judul),
            'deskripsi' => $this->sanitizeRich($request->deskripsi),
            'pendaftaran_link' => $this->sanitizePlain($request->pendaftaran_link),
            'biaya_label' => $this->sanitizePlain($request->biaya_label ?: ($program->biaya_label ?? 'Gratis')),
            'sertifikat_label' => $this->sanitizePlain($request->sertifikat_label ?: ($program->sertifikat_label ?? 'Sertifikat Mengikuti Pelatihan')),
            'bahasa_label' => $this->sanitizePlain($request->bahasa_label ?: ($program->bahasa_label ?? 'Bahasa Indonesia')),
            'kode_unit_kompetensi' => $this->sanitizeRich($request->kode_unit_kompetensi),
            'fasilitas_keunggulan' => $this->sanitizeRich($request->fasilitas_keunggulan),
            'info_tambahan' => $this->sanitizeRich($request->info_tambahan),
            'status'    => $request->input('status', $program->status),
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
        $this->applyWorkflow($request, $dataToUpdate, $program);

        $program->update($dataToUpdate);

        $this->logger->log(
            $request->user(),
            'program.updated',
            "Program '{$program->judul}' diperbarui",
            $program
        );

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

        $this->logger->log(
            request()->user(),
            'program.deleted',
            "Program '{$program->judul}' dihapus",
            $program
        );

        return redirect()->route('admin.program.index')->with('success', 'Program pelatihan berhasil dihapus.');
    }

    private function sanitizeRich(?string $text): ?string
    {
        if ($text === null) {
            return null;
        }

        return strip_tags($text, '<p><br><strong><em><ul><ol><li><a>');
    }

    private function sanitizePlain(?string $text): ?string
    {
        return $text !== null ? strip_tags($text) : null;
    }

    private function applyWorkflow(Request $request, array &$data, ?Program $program = null): void
    {
        $defaultStatus = $request->user()->hasPermission('approve-content') ? 'published' : 'draft';
        $currentStatus = $program ? $program->status : $defaultStatus;
        $requestedStatus = $data['status'] ?? $currentStatus;

        if (! $request->user()->hasPermission('approve-content')) {
            $requestedStatus = $requestedStatus === 'published' ? 'pending' : $requestedStatus;
        }

        $data['status'] = $requestedStatus ?: $currentStatus;

        if ($data['status'] === 'published') {
            $data['approved_by'] = $request->user()->id;
            $data['approved_at'] = now();
            $data['published_at'] = $program?->published_at ?? now();
        } else {
            $data['approved_by'] = null;
            $data['approved_at'] = null;
        }
    }
}
