<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Program; // Import Model Program

class ProgramController extends Controller
{
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

    public function show(Program $program)
    {
        return view('admin.program.show', compact('program'));
    }

    /**
     * Menampilkan form untuk menambah program baru.
     */
    public function create()
    {
        return redirect()->route('admin.program.index')->with('error', 'Penambahan program manual dinonaktifkan. Gunakan tombol sinkronisasi.');
    }

    /**
     * Menyimpan program baru ke database.
     */
    public function store(Request $request)
    {
        return redirect()->route('admin.program.index')->with('error', 'Program dikelola dari pusat. Tidak dapat menambah secara manual.');
    }

    /**
     * Menampilkan form untuk mengedit program.
     */
    public function edit(Program $program)
    {
        return view('admin.program.show', compact('program'));
    }

    /**
     * Memperbarui data program di database.
     */
    public function update(Request $request, Program $program)
    {
        return redirect()->route('admin.program.index')->with('error', 'Perubahan program manual dinonaktifkan.');
    }

    /**
     * Menghapus program dari database.
     */
    public function destroy(Program $program)
    {
        return redirect()->route('admin.program.index')->with('error', 'Penghapusan program manual dinonaktifkan.');
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
