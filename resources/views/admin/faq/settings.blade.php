@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Pengaturan FAQ</h4>
        <small class="text-muted">Atur hero, highlight informasi, dan CTA agar sesuai dengan template FAQ publik.</small>
    </div>
    <a href="{{ route('resource.faq') }}" target="_blank" class="btn btn-outline-primary btn-sm">Lihat Halaman</a>
</div>

<form action="{{ route('admin.faq.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded shadow-sm p-4">
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
            <h5 class="fw-bold">Intro & Info</h5>
            <div class="mb-3">
                <label class="form-label">Judul Intro</label>
                <input type="text" name="intro_title" class="form-control" value="{{ old('intro_title', $setting->intro_title) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi Intro</label>
                <textarea name="intro_description" class="form-control" rows="4">{{ old('intro_description', $setting->intro_description) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Judul Info</label>
                <input type="text" name="info_title" class="form-control" value="{{ old('info_title', $setting->info_title) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi Info</label>
                <textarea name="info_description" class="form-control" rows="3">{{ old('info_description', $setting->info_description) }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Statistik Utama (Label)</label>
                    <input type="text" name="info_stat_primary_label" class="form-control" value="{{ old('info_stat_primary_label', $setting->info_stat_primary_label) }}">
                    <input type="text" name="info_stat_primary_value" class="form-control mt-2" placeholder="Nilai" value="{{ old('info_stat_primary_value', $setting->info_stat_primary_value) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Statistik Kedua (Label)</label>
                    <input type="text" name="info_stat_secondary_label" class="form-control" value="{{ old('info_stat_secondary_label', $setting->info_stat_secondary_label) }}">
                    <input type="text" name="info_stat_secondary_value" class="form-control mt-2" placeholder="Nilai" value="{{ old('info_stat_secondary_value', $setting->info_stat_secondary_value) }}">
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <div class="row g-4">
        <div class="col-lg-6">
            <h5 class="fw-bold">Kontak / CTA</h5>
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" name="contact_title" class="form-control" value="{{ old('contact_title', $setting->contact_title) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="contact_description" class="form-control" rows="4">{{ old('contact_description', $setting->contact_description) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Tombol</label>
                <input type="text" name="contact_button_text" class="form-control mb-2" value="{{ old('contact_button_text', $setting->contact_button_text) }}">
                <input type="text" name="contact_button_link" class="form-control" value="{{ old('contact_button_link', $setting->contact_button_link) }}">
            </div>
        </div>
    </div>

    <div class="text-end mt-4">
        <button class="btn btn-primary px-4">Simpan Pengaturan</button>
    </div>
</form>
@endsection
