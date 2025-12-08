@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>{{ $item->exists ? 'Edit' : 'Tambah' }} Skema Sertifikasi</h3>
    <a href="{{ route('admin.certification-scheme.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($method === 'PUT')
                @method('PUT')
            @endif
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <select name="category" class="form-select" required>
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ old('category', $item->category) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $item->urutan ?? 0) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Judul</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Subjudul</label>
                    <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $item->subtitle) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $item->description) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Teks Tombol</label>
                    <input type="text" name="cta_text" class="form-control" value="{{ old('cta_text', $item->cta_text) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Link Tombol</label>
                    <input type="text" name="cta_url" class="form-control" value="{{ old('cta_url', $item->cta_url) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Gambar</label>
                    <input type="file" name="gambar" class="form-control">
                    @if($item->image_path)
                        <small class="text-muted d-block mt-1">Gambar saat ini:</small>
                        <img src="{{ asset($item->image_path) }}" class="img-fluid rounded" style="max-height:120px;">
                    @endif
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="aktif" {{ old('is_active', $item->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="aktif">Aktifkan skema</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
