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
                <input type="text" name="judul" class="form-control" value="{{ $program->judul }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar (Biarkan kosong jika tidak diganti)</label>
                <br>
                @if($program->gambar)
                    <img src="{{ asset($program->gambar) }}" width="150" class="mb-2 rounded border">
                @endif
                <input type="file" name="gambar" class="form-control" accept="image/*">
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" rows="6" class="form-control" required>{{ $program->deskripsi }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui Program</button>
            <a href="{{ route('admin.program.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection