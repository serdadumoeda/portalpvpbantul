@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Skema Sertifikasi</h3>
    <a href="{{ route('admin.certification-scheme.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Tambah Skema
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <table class="table table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th width="5%">#</th>
                    <th>Kategori</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><span class="badge bg-info text-dark">{{ ucfirst($item->category) }}</span></td>
                    <td>{{ $item->title }}</td>
                    <td>{{ Str::limit($item->description, 60) }}</td>
                    <td>{{ $item->urutan }}</td>
                    <td>{!! $item->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Nonaktif</span>' !!}</td>
                    <td>
                        <a href="{{ route('admin.certification-scheme.edit', $item->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.certification-scheme.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus skema ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Belum ada data skema.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
