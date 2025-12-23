@extends('layouts.admin')

@section('content')
<div class="card shadow-sm border-0 col-lg-10">
    <div class="card-header bg-white">
        <h5 class="mb-0">{{ $service->exists ? 'Edit' : 'Tambah' }} Layanan Pelatihan</h5>
    </div>
    <div class="card-body">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($method === 'PUT') @method('PUT') @endif
            <div class="mb-3">
                <label class="form-label fw-bold">Judul</label>
                <input type="text" name="judul" class="form-control" value="{{ old('judul', $service->judul) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="form-control">{{ old('deskripsi', $service->deskripsi) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Fasilitas (boleh HTML / list)</label>
                <textarea name="fasilitas" rows="8" class="form-control" placeholder="Gunakan <ol><li>... untuk list">{{ old('fasilitas', $service->fasilitas) }}</textarea>
                <small class="text-muted">Contoh: gunakan &lt;ol&gt;&lt;li&gt;Fasilitas&lt;/li&gt;&lt;/ol&gt; untuk list bernomor.</small>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Gambar (opsional)</label>
                @if($service->gambar)
                    <div class="mb-2"><img src="{{ asset($service->gambar) }}" width="180" class="img-thumbnail"></div>
                @endif
                <input type="file" name="gambar" class="form-control" accept="image/*">
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Urutan</label>
                    <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $service->urutan ?? 0) }}">
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label">Aktif</label>
                    </div>
                </div>
            </div>
            <div class="mb-3 mt-2">
                <label class="form-label fw-bold">Status</label>
                <select name="status" class="form-select">
                    @foreach(\App\Models\TrainingService::statuses() as $key => $label)
                        <option value="{{ $key }}" @selected(old('status', $service->status ?? null) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success mt-3">Simpan</button>
            <a href="{{ route('admin.training-service.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </form>
    </div>
</div>
@endsection
