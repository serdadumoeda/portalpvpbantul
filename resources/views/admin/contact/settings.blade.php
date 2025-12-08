@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Pengaturan Hubungi Kami</h4>
        <small class="text-muted">Atur hero, peta, dan CTA agar sesuai dengan template halaman kontak.</small>
    </div>
    <a href="{{ route('resource.hubungi') }}" target="_blank" class="btn btn-outline-primary btn-sm">Lihat Halaman</a>
</div>

<form action="{{ route('admin.contact.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded shadow-sm p-4">
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
            <h5 class="fw-bold">Peta & Informasi</h5>
            <div class="mb-3">
                <label class="form-label">Judul Peta</label>
                <input type="text" name="map_title" class="form-control" value="{{ old('map_title', $setting->map_title) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi Peta</label>
                <textarea name="map_description" class="form-control" rows="3">{{ old('map_description', $setting->map_description) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Embed Map (iframe)</label>
                <textarea name="map_embed" class="form-control" rows="4">{{ old('map_embed', $setting->map_embed) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Judul Layanan Info</label>
                <input type="text" name="info_section_title" class="form-control" value="{{ old('info_section_title', $setting->info_section_title) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi Layanan Info</label>
                <textarea name="info_section_description" class="form-control" rows="3">{{ old('info_section_description', $setting->info_section_description) }}</textarea>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <div class="row">
        <div class="col-lg-8">
            <h5 class="fw-bold">Call To Action</h5>
            <div class="mb-3">
                <label class="form-label">Judul CTA</label>
                <input type="text" name="cta_title" class="form-control" value="{{ old('cta_title', $setting->cta_title) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi CTA</label>
                <textarea name="cta_description" class="form-control" rows="3">{{ old('cta_description', $setting->cta_description) }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tombol Utama</label>
                    <input type="text" name="cta_primary_text" class="form-control mb-2" value="{{ old('cta_primary_text', $setting->cta_primary_text) }}">
                    <input type="text" name="cta_primary_link" class="form-control" value="{{ old('cta_primary_link', $setting->cta_primary_link) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tombol Kedua</label>
                    <input type="text" name="cta_secondary_text" class="form-control mb-2" value="{{ old('cta_secondary_text', $setting->cta_secondary_text) }}">
                    <input type="text" name="cta_secondary_link" class="form-control" value="{{ old('cta_secondary_link', $setting->cta_secondary_link) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mt-4">
        <button class="btn btn-primary px-4">Simpan Pengaturan</button>
    </div>
</form>
@endsection
