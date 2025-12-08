@extends('layouts.admin')

@section('content')
<div class="card shadow-sm border-0 col-md-10 mx-auto">
    <div class="card-header bg-white">
        <h5 class="mb-0">Edit Halaman: {{ $profile->judul }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.profile.update', $profile->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label fw-bold">Judul Halaman</label>
                <input type="text" name="judul" class="form-control" value="{{ $profile->judul }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Gambar / Bagan (Opsional)</label>
                @if($profile->gambar)
                    <div class="mb-2">
                        <img src="{{ asset($profile->gambar) }}" width="200" class="img-thumbnail">
                    </div>
                @endif
                <input type="file" name="gambar" class="form-control" accept="image/*">
                <small class="text-muted">Upload jika ingin mengganti gambar struktur atau ilustrasi sejarah.</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Isi Konten</label>
                <textarea name="konten" rows="15" class="form-control" placeholder="Tulis isi halaman disini...">{{ $profile->konten }}</textarea>
                <small class="text-muted">Anda bisa menggunakan tag HTML sederhana seperti &lt;p&gt;, &lt;b&gt;, &lt;ul&gt;.</small>
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save me-2"></i> Simpan Perubahan</button>
            <a href="{{ route('admin.profile.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection