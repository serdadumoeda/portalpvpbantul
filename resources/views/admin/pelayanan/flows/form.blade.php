@extends('layouts.admin')

@php
    $isEdit = $flow->exists;
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">{{ $isEdit ? 'Edit Alur Pelayanan' : 'Tambah Alur Pelayanan' }}</h4>
        <small class="text-muted">Isi detail langkah, gambar, dan urutan untuk menyesuaikan dengan UI.</small>
    </div>
    <a href="{{ route('admin.public-service-flow.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
</div>

<form action="{{ $isEdit ? route('admin.public-service-flow.update', $flow) : route('admin.public-service-flow.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded shadow-sm p-4">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="row g-4">
        <div class="col-md-4">
            <label class="form-label">Kategori</label>
            <input type="text" name="category" class="form-control" value="{{ old('category', $flow->category) }}" placeholder="mis. pelatihan, pengaduan">
        </div>
        <div class="col-md-4">
            <label class="form-label">Urutan</label>
            <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $flow->urutan) }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="is_active" value="1" id="status" {{ old('is_active', $flow->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="status">Aktif</label>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <label class="form-label">Judul</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $flow->title) }}" required>
    </div>

    <div class="mt-3">
        <label class="form-label">Subjudul / Penjelasan singkat</label>
        <textarea name="subtitle" rows="3" class="form-control">{{ old('subtitle', $flow->subtitle) }}</textarea>
    </div>

    <div class="mt-3">
        <label class="form-label">Langkah (pisahkan per baris)</label>
        <textarea name="steps" rows="7" class="form-control" placeholder="1. Isi baris pertama&#10;2. Baris kedua">{{ old('steps', implode(PHP_EOL, ($flow->steps_list ?? []))) }}</textarea>
    </div>

    <div class="mt-3">
        <label class="form-label">Gambar</label>
        <input type="file" name="image" class="form-control">
        @if($flow->image)
            <small class="text-muted d-block mt-1">Saat ini: {{ $flow->image }}</small>
        @endif
    </div>

    <div class="text-end mt-4">
        <button class="btn btn-primary px-4">{{ $isEdit ? 'Update' : 'Simpan' }}</button>
    </div>
</form>
@endsection
