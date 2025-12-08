@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>{{ $vacancy->exists ? 'Edit' : 'Tambah' }} Lowongan</h3>
    <a href="{{ route('admin.lowongan.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($method === 'PUT')
                @method('PUT')
            @endif
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Judul</label>
                    <input type="text" name="judul" class="form-control" value="{{ old('judul', $vacancy->judul) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Perusahaan / Instansi</label>
                    <input type="text" name="perusahaan" class="form-control" value="{{ old('perusahaan', $vacancy->perusahaan) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $vacancy->lokasi) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tipe Pekerjaan</label>
                    <input type="text" name="tipe_pekerjaan" class="form-control" value="{{ old('tipe_pekerjaan', $vacancy->tipe_pekerjaan) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Deadline</label>
                    <input type="date" name="deadline" class="form-control" value="{{ old('deadline', optional($vacancy->deadline)->format('Y-m-d')) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi Singkat</label>
                    <textarea name="deskripsi" rows="4" class="form-control">{{ old('deskripsi', $vacancy->deskripsi) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Kualifikasi / Benefit</label>
                    <textarea name="kualifikasi" rows="4" class="form-control">{{ old('kualifikasi', $vacancy->kualifikasi) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Link Pendaftaran</label>
                    <input type="url" name="link_pendaftaran" class="form-control" value="{{ old('link_pendaftaran', $vacancy->link_pendaftaran) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Gambar / Banner</label>
                    <input type="file" name="gambar" class="form-control">
                    @if($vacancy->gambar)
                        <img src="{{ asset($vacancy->gambar) }}" class="img-fluid rounded mt-2" style="max-height:120px;">
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="is_active" id="aktif" value="1" {{ old('is_active', $vacancy->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="aktif">Tampilkan</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
