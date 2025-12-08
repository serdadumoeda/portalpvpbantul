@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>{{ $year->exists ? 'Edit' : 'Tambah' }} Tahun Infografis</h3>
    <a href="{{ route('admin.infographic-year.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($method === 'PUT')
                @method('PUT')
            @endif
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tahun</label>
                    <input type="text" name="tahun" class="form-control" value="{{ old('tahun', $year->tahun) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $year->urutan ?? 0) }}">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="aktif" {{ old('is_active', $year->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="aktif">Tampilkan di halaman</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Judul Singkat</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $year->title) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Headline</label>
                    <input type="text" name="headline" class="form-control" value="{{ old('headline', $year->headline) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" rows="3" class="form-control">{{ old('description', $year->description) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Teks Tombol Hero</label>
                    <input type="text" name="hero_button_text" class="form-control" value="{{ old('hero_button_text', $year->hero_button_text) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Link Tombol Hero</label>
                    <input type="text" name="hero_button_link" class="form-control" value="{{ old('hero_button_link', $year->hero_button_link) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Gambar Hero</label>
                    <input type="file" name="hero_image" class="form-control">
                    @if($year->hero_image)
                        <img src="{{ asset($year->hero_image) }}" class="img-fluid rounded mt-2" style="max-height:150px;">
                    @endif
                </div>
            </div>
            <div class="mt-4">
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
