@extends('layouts.admin')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Buat Pengumuman Baru</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.pengumuman.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Judul Pengumuman</label>
                <input type="text" name="judul" class="form-control" placeholder="Contoh: Hasil Seleksi Tahap 1" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Isi Pengumuman</label>
                <textarea name="isi" rows="5" class="form-control" placeholder="Tulis detail pengumuman..." required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">File Lampiran (PDF/DOC) - <span class="text-muted fw-normal">Opsional</span></label>
                <input type="file" name="file_download" class="form-control">
                <small class="text-muted">Upload file hasil seleksi atau jadwal jika ada.</small>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Terbitkan</button>
            <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection