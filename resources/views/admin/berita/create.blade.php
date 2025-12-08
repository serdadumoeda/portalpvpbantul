@extends('layouts.admin')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        <h5 class="mb-0">Tambah Berita Baru</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Judul Berita</label>
                    <input type="text" name="judul" class="form-control" placeholder="Masukkan judul berita..." required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select" required>
                        @foreach(\App\Models\Berita::categories() as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Penulis</label>
                    <input type="text" name="author" class="form-control" placeholder="Nama penulis">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Publikasi</label>
                    <input type="datetime-local" name="published_at" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar Utama</label>
                <input type="file" name="gambar_utama" class="form-control" accept="image/*" required>
                <small class="text-muted">Format: JPG, PNG. Maks: 2MB.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Ringkasan Singkat</label>
                <textarea name="excerpt" rows="3" class="form-control" placeholder="Opsional, akan otomatis dibuat jika dikosongkan."></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Konten Berita</label>
                <textarea name="konten" rows="10" class="form-control" placeholder="Tulis isi berita di sini..." required></textarea>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Berita</button>
            <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
