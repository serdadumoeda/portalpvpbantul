@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>{{ $item->exists ? 'Edit' : 'Tambah' }} Item Publikasi</h3>
    <a href="{{ route('admin.publication-item.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($method === 'PUT')
                @method('PUT')
            @endif
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Kategori</label>
                    <select name="publication_category_id" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $id => $label)
                            <option value="{{ $id }}" {{ old('publication_category_id', $item->publication_category_id) == $id ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Judul</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Subjudul</label>
                    <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $item->subtitle) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" rows="3" class="form-control">{{ old('description', $item->description) }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Badge</label>
                    <input type="text" name="badge" class="form-control" value="{{ old('badge', $item->badge) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Teks Tombol</label>
                    <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $item->button_text) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Link Tombol</label>
                    <input type="text" name="button_link" class="form-control" value="{{ old('button_link', $item->button_link) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $item->urutan ?? 0) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Gambar</label>
                    <input type="file" name="image" class="form-control">
                    @if($item->image)
                        <img src="{{ asset($item->image) }}" class="img-fluid rounded mt-2" style="max-height:150px;">
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="form-label">Data Tambahan (opsional, pisahkan per baris)</label>
                    <textarea name="extra" rows="4" class="form-control">{{ old('extra', isset($item->extra) ? implode("\n", (array) $item->extra) : '') }}</textarea>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="aktif" {{ old('is_active', $item->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="aktif">Tampilkan item</label>
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
