@extends('layouts.admin')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Edit Pengumuman</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Judul Pengumuman</label>
                <input type="text" name="judul" class="form-control" value="{{ $pengumuman->judul }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Isi Pengumuman</label>
                <textarea name="isi" rows="5" class="form-control" required>{{ $pengumuman->isi }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">File Lampiran</label>
                @if($pengumuman->file_download)
                    <div class="mb-2">
                        <a href="{{ asset($pengumuman->file_download) }}" target="_blank" class="text-primary"><i class="fas fa-file"></i> Lihat File Saat Ini</a>
                    </div>
                @endif
                <input type="file" name="file_download" class="form-control">
                <small class="text-muted">Biarkan kosong jika file tidak ingin diubah.</small>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection