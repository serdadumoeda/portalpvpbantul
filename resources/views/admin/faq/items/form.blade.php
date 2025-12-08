@extends('layouts.admin')

@php
    $isEdit = $item->exists;
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">{{ $isEdit ? 'Edit' : 'Tambah' }} FAQ</h4>
        <small class="text-muted">Isi pertanyaan, jawaban, serta kategori agar tampil rapi pada UI.</small>
    </div>
    <a href="{{ route('admin.faq-item.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
</div>

<form action="{{ $isEdit ? route('admin.faq-item.update', $item) : route('admin.faq-item.store') }}" method="POST" class="bg-white rounded shadow-sm p-4">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label">Kategori</label>
            <select name="faq_category_id" class="form-select" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $id => $name)
                    <option value="{{ $id }}" {{ (string) old('faq_category_id', $item->faq_category_id) === (string) $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Urutan</label>
            <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $item->urutan) }}">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="faq-active" name="is_active" value="1" {{ old('is_active', $item->is_active) ? 'checked' : '' }}>
                <label for="faq-active" class="form-check-label">Aktif</label>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <label class="form-label">Pertanyaan</label>
        <input type="text" name="question" class="form-control" value="{{ old('question', $item->question) }}" required>
    </div>

    <div class="mt-3">
        <label class="form-label">Jawaban</label>
        <textarea name="answer" class="form-control" rows="6">{{ old('answer', $item->answer) }}</textarea>
        <small class="text-muted">Dapat menggunakan HTML dasar untuk list atau penekanan.</small>
    </div>

    <div class="text-end mt-4">
        <button class="btn btn-primary px-4">{{ $isEdit ? 'Update' : 'Simpan' }}</button>
    </div>
</form>
@endsection
