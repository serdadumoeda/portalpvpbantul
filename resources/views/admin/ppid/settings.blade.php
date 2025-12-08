@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Pengaturan PPID</h4>
        <small class="text-muted">Atur hero, deskripsi profil, serta embed form PPID.</small>
    </div>
    <a href="{{ route('ppid') }}" target="_blank" class="btn btn-outline-primary btn-sm">Lihat Halaman</a>
</div>

<form action="{{ route('admin.ppid.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded shadow-sm p-4">
    @csrf
    @method('PUT')
    <div class="row g-4">
        <div class="col-lg-6">
            <h5 class="fw-bold">Hero Section</h5>
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" name="hero_title" class="form-control" value="{{ old('hero_title', $setting->hero_title) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Subjudul</label>
                <input type="text" name="hero_subtitle" class="form-control" value="{{ old('hero_subtitle', $setting->hero_subtitle) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="hero_description" class="form-control" rows="4">{{ old('hero_description', $setting->hero_description) }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Teks Tombol</label>
                    <input type="text" name="hero_button_text" class="form-control" value="{{ old('hero_button_text', $setting->hero_button_text) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Link Tombol</label>
                    <input type="text" name="hero_button_link" class="form-control" value="{{ old('hero_button_link', $setting->hero_button_link) }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Gambar Hero</label>
                <input type="file" name="hero_image" class="form-control">
                @if($setting->hero_image)
                    <small class="text-muted d-block mt-1">Saat ini: {{ $setting->hero_image }}</small>
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <h5 class="fw-bold">Profil PPID</h5>
            <div class="mb-3">
                <label class="form-label">Judul Profil</label>
                <input type="text" name="profile_title" class="form-control" value="{{ old('profile_title', $setting->profile_title) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi Profil</label>
                <textarea name="profile_description" class="form-control" rows="6">{{ old('profile_description', $setting->profile_description) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Judul Formulir</label>
                <input type="text" name="form_title" class="form-control" value="{{ old('form_title', $setting->form_title) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi Formulir</label>
                <textarea name="form_description" class="form-control" rows="3">{{ old('form_description', $setting->form_description) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Embed Form (iframe)</label>
                <textarea name="form_embed" class="form-control" rows="4">{{ old('form_embed', $setting->form_embed) }}</textarea>
            </div>
        </div>
    </div>

    <div class="text-end mt-4">
        <button class="btn btn-primary px-4">Simpan Pengaturan</button>
    </div>
</form>
@endsection
