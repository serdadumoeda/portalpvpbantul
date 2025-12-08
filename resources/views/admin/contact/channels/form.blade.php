@extends('layouts.admin')

@php
    $isEdit = $channel->exists;
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">{{ $isEdit ? 'Edit' : 'Tambah' }} Channel Kontak</h4>
        <small class="text-muted">Isi informasi sesuai dengan kartu yang ingin ditampilkan pada halaman Hubungi Kami.</small>
    </div>
    <a href="{{ route('admin.contact-channel.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
</div>

<form action="{{ $isEdit ? route('admin.contact-channel.update', $channel) : route('admin.contact-channel.store') }}" method="POST" class="bg-white rounded shadow-sm p-4">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label">Judul</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $channel->title) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Subjudul</label>
            <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $channel->subtitle) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Label / Konten</label>
            <input type="text" name="label" class="form-control" value="{{ old('label', $channel->label) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Link (opsional)</label>
            <input type="text" name="link" class="form-control" value="{{ old('link', $channel->link) }}" placeholder="https://...">
        </div>
        <div class="col-md-6">
            <label class="form-label">Icon (Font Awesome)</label>
            <input type="text" name="icon" class="form-control" value="{{ old('icon', $channel->icon) }}" placeholder="mis. fas fa-phone">
        </div>
        <div class="col-md-3">
            <label class="form-label">Urutan</label>
            <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $channel->urutan) }}">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="active" {{ old('is_active', $channel->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="active">Aktif</label>
            </div>
        </div>
    </div>

    <div class="text-end mt-4">
        <button class="btn btn-primary px-4">{{ $isEdit ? 'Update' : 'Simpan' }}</button>
    </div>
</form>
@endsection
