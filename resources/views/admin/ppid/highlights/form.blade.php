@extends('layouts.admin')

@php
    $isEdit = $highlight->exists;
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">{{ $isEdit ? 'Edit' : 'Tambah' }} Highlight PPID</h4>
        <small class="text-muted">Sesuaikan icon, judul, dan deskripsi highlight.</small>
    </div>
    <a href="{{ route('admin.ppid-highlight.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
</div>

<form action="{{ $isEdit ? route('admin.ppid-highlight.update', $highlight) : route('admin.ppid-highlight.store') }}" method="POST" class="bg-white rounded shadow-sm p-4">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="mb-3">
        <label class="form-label">Judul</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $highlight->title) }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control" rows="4">{{ old('description', $highlight->description) }}</textarea>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Ikon (Font Awesome)</label>
            <input type="text" name="icon" class="form-control" value="{{ old('icon', $highlight->icon) }}" placeholder="mis. fas fa-shield-alt">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Urutan</label>
            <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $highlight->urutan) }}">
        </div>
        <div class="col-md-4 mb-3 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="active" name="is_active" value="1" {{ old('is_active', $highlight->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="active">Aktif</label>
            </div>
        </div>
    </div>

    <div class="text-end">
        <button class="btn btn-primary px-4">{{ $isEdit ? 'Update' : 'Simpan' }}</button>
    </div>
</form>
@endsection
