@extends('layouts.admin')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        <h5 class="mb-0">Edit Berita</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Judul Berita</label>
                    <input type="text" name="judul" class="form-control" value="{{ old('judul', $berita->judul) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select" required>
                        @foreach(\App\Models\Berita::categories() as $key => $label)
                            <option value="{{ $key }}" {{ $berita->kategori === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Penulis</label>
                    <input type="text" name="author" class="form-control" value="{{ old('author', $berita->author) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Publikasi</label>
                    <input type="datetime-local" name="published_at" class="form-control" value="{{ optional($berita->published_at)->format('Y-m-d\TH:i') }}">
                </div>
            </div>

            <div class="mb-3 mt-3">
                <label class="form-label">Gambar Utama</label>
                <div class="mb-2">
                    <img src="{{ $berita->gambar_utama }}" width="150" class="rounded border">
                </div>
                <input type="file" name="gambar_utama" class="form-control" accept="image/*">
                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Ringkasan Singkat</label>
                <textarea name="excerpt" rows="3" class="form-control">{{ old('excerpt', $berita->excerpt) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Konten Berita</label>
                <textarea name="konten" rows="10" class="form-control" required>{{ old('konten', $berita->konten) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Berita</button>
            <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
