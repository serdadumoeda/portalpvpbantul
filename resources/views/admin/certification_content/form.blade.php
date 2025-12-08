@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>{{ $item->exists ? 'Edit' : 'Tambah' }} Konten Sertifikasi</h3>
    <a href="{{ route('admin.certification-content.index') }}" class="btn btn-secondary">Kembali</a>
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
                    <label class="form-label">Section</label>
                    <select name="section" class="form-select" required>
                        <option value="">Pilih Section</option>
                        @foreach($sections as $key => $label)
                            <option value="{{ $key }}" {{ old('section', $item->section) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $item->urutan ?? 0) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Judul</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Subjudul</label>
                    <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $item->subtitle) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Badge / Label</label>
                    <input type="text" name="badge" class="form-control" value="{{ old('badge', $item->badge) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Teks Tombol</label>
                    <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $item->button_text) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Link Tombol</label>
                    <input type="text" name="button_url" class="form-control" value="{{ old('button_url', $item->button_url) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Warna / Background (opsional)</label>
                    <input type="text" name="background" class="form-control" value="{{ old('background', $item->background) }}" placeholder="#d9f3f3">
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $item->description) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Daftar Poin (pisahkan per baris)</label>
                    <textarea name="list_items" class="form-control" rows="4">{{ old('list_items', $item->list_items_text ?? '') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Gambar</label>
                    <input type="file" name="gambar" class="form-control">
                    @if($item->image_path)
                        <small class="text-muted">Gambar saat ini:</small>
                        <img src="{{ asset($item->image_path) }}" class="img-fluid rounded mt-2" style="max-height:120px;">
                    @endif
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="aktif" {{ old('is_active', $item->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="aktif">Tandai sebagai aktif</label>
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
