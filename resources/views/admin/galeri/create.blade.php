@extends('layouts.admin')

@section('content')
<div class="card border-0 shadow-sm col-md-8 mx-auto">
    <div class="card-header bg-white">
        <h5 class="mb-0">Upload Foto Galeri</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Keterangan Foto (Caption)</label>
                <input type="text" name="judul" class="form-control" placeholder="Contoh: Kegiatan Upacara Pembukaan" required>
            </div>

            <div class="mb-3">
                <label class="form-label">File Foto</label>
                <input type="file" name="gambar" class="form-control" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-success">Upload</button>
            <a href="{{ route('admin.galeri.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection