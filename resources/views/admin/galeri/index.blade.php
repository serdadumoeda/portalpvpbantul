@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Kelola Galeri Foto</h3>
    <a href="{{ route('admin.galeri.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Upload Foto</a>
</div>

<div class="row">
    @foreach($galeri as $foto)
    <div class="col-md-3 mb-4">
        <div class="card h-100 shadow-sm border-0">
            <img src="{{ asset($foto->gambar) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
            <div class="card-body p-2 text-center">
                <p class="card-text small fw-bold mb-2">{{ $foto->judul }}</p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('admin.galeri.edit', $foto->id) }}" class="btn btn-xs btn-outline-warning btn-sm"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('admin.galeri.destroy', $foto->id) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-xs btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="d-flex justify-content-center">
    {{ $galeri->links() }}
</div>
@endsection