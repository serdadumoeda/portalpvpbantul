@extends('layouts.admin')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Edit Program: {{ $program->judul }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.program.update', $program->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Nama Kejuruan/Program</label>
                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $program->judul) }}" required>
                @error('judul')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar (Biarkan kosong jika tidak diganti)</label>
                <br>
                @if($program->gambar)
                    <img src="{{ asset($program->gambar) }}" width="150" class="mb-2 rounded border">
                @endif
                <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/gif">
                @error('gambar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" rows="6" class="form-control @error('deskripsi') is-invalid @enderror" required>{{ old('deskripsi', $program->deskripsi) }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Kode Unit Kompetensi</label>
                <textarea name="kode_unit_kompetensi" rows="4" class="form-control @error('kode_unit_kompetensi') is-invalid @enderror" placeholder="Daftar kode unit kompetensi">{{ old('kode_unit_kompetensi', $program->kode_unit_kompetensi) }}</textarea>
                @error('kode_unit_kompetensi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Fasilitas & Keunggulan</label>
                <textarea name="fasilitas_keunggulan" rows="4" class="form-control @error('fasilitas_keunggulan') is-invalid @enderror" placeholder="Fasilitas workshop, mentor, dll.">{{ old('fasilitas_keunggulan', $program->fasilitas_keunggulan) }}</textarea>
                @error('fasilitas_keunggulan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Info Tambahan</label>
                <textarea name="info_tambahan" rows="4" class="form-control @error('info_tambahan') is-invalid @enderror" placeholder="Catatan penting atau jadwal khusus">{{ old('info_tambahan', $program->info_tambahan) }}</textarea>
                @error('info_tambahan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui Program</button>
            <a href="{{ route('admin.program.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
