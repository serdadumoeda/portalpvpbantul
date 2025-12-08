@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>{{ $category->exists ? 'Edit' : 'Tambah' }} Kategori Publikasi</h3>
    <a href="{{ route('admin.publication-category.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ $action }}" method="POST">
            @csrf
            @if($method === 'PUT')
                @method('PUT')
            @endif
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug (opsional)</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $category->slug) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Layout</label>
                    <select name="layout" class="form-select" required>
                        @php $layouts = ['cards' => 'Grid Kartu', 'infographic' => 'Infografis', 'list' => 'Daftar', 'downloads' => 'Download List', 'alumni' => 'Alumni']; @endphp
                        @foreach($layouts as $key => $label)
                            <option value="{{ $key }}" {{ old('layout', $category->layout) == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jumlah Kolom</label>
                    <input type="number" name="columns" class="form-control" value="{{ old('columns', $category->columns ?? 4) }}" min="1" max="6">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $category->urutan ?? 0) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Subjudul</label>
                    <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $category->subtitle) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" rows="3" class="form-control">{{ old('description', $category->description) }}</textarea>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="aktif" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="aktif">Tampilkan kategori ini</label>
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
