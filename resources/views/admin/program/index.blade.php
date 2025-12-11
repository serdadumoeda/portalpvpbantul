@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Kelola Program Pelatihan</h3>
    <a href="{{ route('admin.program.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Program</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Gambar</th>
                    <th>Nama Kejuruan</th>
                    <th>Deskripsi Singkat</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($programs as $index => $p)
                <tr>
                    <td>{{ $loop->iteration }}</td> <td>
                        @if($p->gambar)
                            <img src="{{ asset($p->gambar) }}" width="80" class="rounded">
                        @else
                            <span class="text-muted small">No Image</span>
                        @endif
                    </td>
                    <td class="fw-bold">{{ $p->judul }}</td>
                    <td>{{ Str::limit($p->deskripsi, 100) }}</td>
                    <td>
                        <a href="{{ route('admin.program.edit', $p->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.program.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus program ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
