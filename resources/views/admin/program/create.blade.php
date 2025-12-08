@extends('layouts.admin')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Tambah Program Pelatihan Baru</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.program.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Nama Kejuruan/Program</label>
                <input type="text" name="judul" class="form-control" placeholder="Contoh: Teknik Las Listrik" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar Ilustrasi</label>
                <input type="file" name="gambar" class="form-control" accept="image/*" required>
                <small class="text-muted">Disarankan rasio 4:3 (Landscape).</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi & Fasilitas</label>
                <textarea name="deskripsi" rows="6" class="form-control" placeholder="Jelaskan tentang kejuruan ini..." required></textarea>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Program</button>
            <a href="{{ route('admin.program.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection