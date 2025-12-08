@extends('layouts.admin')

@php
    $isEdit = $category->exists;
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">{{ $isEdit ? 'Edit' : 'Tambah' }} Kategori FAQ</h4>
        <small class="text-muted">Atur judul, deskripsi, dan icon agar sejalan dengan tampilan publik.</small>
    </div>
    <a href="{{ route('admin.faq-category.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
</div>

<form action="{{ $isEdit ? route('admin.faq-category.update', $category) : route('admin.faq-category.store') }}" method="POST" class="bg-white rounded shadow-sm p-4">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="mb-3">
        <label class="form-label">Judul</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $category->title) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Subjudul / Deskripsi Singkat</label>
        <textarea name="subtitle" class="form-control" rows="3">{{ old('subtitle', $category->subtitle) }}</textarea>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Icon (opsional)</label>
            <input type="text" name="icon" class="form-control" value="{{ old('icon', $category->icon) }}" placeholder="mis: fas fa-question">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Urutan</label>
            <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $category->urutan) }}">
        </div>
        <div class="col-md-4 mb-3 d-flex align-items-center">
            <div class="form-check mt-4">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Aktif</label>
            </div>
        </div>
    </div>

    <div class="text-end">
        <button class="btn btn-primary px-4">{{ $isEdit ? 'Update' : 'Simpan' }}</button>
    </div>
</form>
@endsection
