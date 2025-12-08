@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Pengaturan Pelayanan Publik</h4>
        <small class="text-muted">Atur hero, maklumat, standar, serta CTA agar seragam dengan template UI.</small>
    </div>
    <a href="{{ route('resource.pelayanan') }}" target="_blank" class="btn btn-outline-primary btn-sm">Lihat Halaman</a>
</div>

<form action="{{ route('admin.public-service.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded shadow-sm p-4">
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
                <textarea name="hero_description" rows="3" class="form-control">{{ old('hero_description', $setting->hero_description) }}</textarea>
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
            <h5 class="fw-bold">Pelayanan Publik</h5>
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" name="intro_title" class="form-control" value="{{ old('intro_title', $setting->intro_title) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi Pendek</label>
                <input type="text" name="intro_description" class="form-control" value="{{ old('intro_description', $setting->intro_description) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Konten Lengkap</label>
                <textarea name="intro_content" class="form-control" rows="5">{{ old('intro_content', $setting->intro_content) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Judul Regulasi</label>
                <input type="text" name="regulation_title" class="form-control" value="{{ old('regulation_title', $setting->regulation_title) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Daftar Regulasi (pisahkan per baris)</label>
                <textarea name="regulation_items" class="form-control" rows="4">{{ old('regulation_items', implode(PHP_EOL, $setting->regulation_list ?? [])) }}</textarea>
            </div>
        </div>
    </div>

    <hr class="my-4">
    <div class="row g-4">
        <div class="col-lg-6">
            <h5 class="fw-bold">Maklumat Pelayanan</h5>
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" name="policy_title" class="form-control" value="{{ old('policy_title', $setting->policy_title) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Subjudul</label>
                <input type="text" name="policy_subtitle" class="form-control" value="{{ old('policy_subtitle', $setting->policy_subtitle) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Isi Maklumat</label>
                <textarea name="policy_description" class="form-control" rows="6">{{ old('policy_description', $setting->policy_description) }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Penandatangan</label>
                    <input type="text" name="policy_signature" class="form-control" value="{{ old('policy_signature', $setting->policy_signature) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="policy_position" class="form-control" value="{{ old('policy_position', $setting->policy_position) }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Gambar Banner</label>
                <input type="file" name="policy_image" class="form-control">
                @if($setting->policy_image)
                    <small class="text-muted d-block mt-1">Saat ini: {{ $setting->policy_image }}</small>
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <h5 class="fw-bold">Standar Pelayanan</h5>
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" name="standard_title" class="form-control" value="{{ old('standard_title', $setting->standard_title) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="standard_description" class="form-control" rows="3">{{ old('standard_description', $setting->standard_description) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Judul Dokumen</label>
                <input type="text" name="standard_document_title" class="form-control" value="{{ old('standard_document_title', $setting->standard_document_title) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi Dokumen</label>
                <textarea name="standard_document_description" class="form-control" rows="4">{{ old('standard_document_description', $setting->standard_document_description) }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Label Badge</label>
                    <input type="text" name="standard_document_badge" class="form-control" value="{{ old('standard_document_badge', $setting->standard_document_badge) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Upload Dokumen (PDF)</label>
                    <input type="file" name="standard_document_file" class="form-control">
                    @if($setting->standard_document_file)
                        <small class="text-muted d-block mt-1">Saat ini: {{ $setting->standard_document_file }}</small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">
    <div class="row g-4">
        <div class="col-lg-6">
            <h5 class="fw-bold">Alur Layanan</h5>
            <div class="mb-3">
                <label class="form-label">Judul Section</label>
                <input type="text" name="flow_section_title" class="form-control" value="{{ old('flow_section_title', $setting->flow_section_title) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="flow_section_description" class="form-control" rows="3">{{ old('flow_section_description', $setting->flow_section_description) }}</textarea>
            </div>
        </div>
        <div class="col-lg-6">
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
                    <input type="text" name="cta_primary_text" class="form-control mb-2" placeholder="Teks" value="{{ old('cta_primary_text', $setting->cta_primary_text) }}">
                    <input type="text" name="cta_primary_link" class="form-control" placeholder="Link" value="{{ old('cta_primary_link', $setting->cta_primary_link) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tombol Kedua</label>
                    <input type="text" name="cta_secondary_text" class="form-control mb-2" placeholder="Teks" value="{{ old('cta_secondary_text', $setting->cta_secondary_text) }}">
                    <input type="text" name="cta_secondary_link" class="form-control" placeholder="Link" value="{{ old('cta_secondary_link', $setting->cta_secondary_link) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mt-4">
        <button class="btn btn-primary px-4">Simpan Pengaturan</button>
    </div>
</form>
@endsection
