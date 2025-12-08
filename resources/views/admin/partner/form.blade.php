@extends('layouts.admin')

@section('content')
<div class="card shadow-sm border-0 col-lg-8">
    <div class="card-header bg-white">
        <h5 class="mb-0">{{ $partner->exists ? 'Edit' : 'Tambah' }} Partner</h5>
    </div>
    <div class="card-body">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($method === 'PUT')
                @method('PUT')
            @endif

            <div class="mb-3">
                <label class="form-label fw-bold">Nama Partner</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $partner->nama) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Logo</label>
                @if($partner->logo)
                    <div class="mb-2"><img src="{{ asset($partner->logo) }}" width="100" class="img-thumbnail"></div>
                @endif
                <input type="file" name="logo" class="form-control" accept="image/*">
                <small class="text-muted">PNG/JPG, maks 2MB.</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Tautan</label>
                <input type="url" name="tautan" class="form-control" placeholder="https://contoh.com" value="{{ old('tautan', $partner->tautan) }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Urutan</label>
                <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $partner->urutan ?? 0) }}">
            </div>

            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $partner->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Tampilkan di halaman utama</label>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('admin.partner.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
