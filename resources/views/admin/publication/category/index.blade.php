@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Kategori Publikasi</h3>
        <p class="text-muted mb-0">Kelompokkan publikasi berdasarkan jenis konten (pencapaian, majalah, laporan, dll).</p>
    </div>
    <a href="{{ route('admin.publication-category.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah Kategori</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Layout</th>
                    <th>Kolom</th>
                    <th>Status</th>
                    <th>Urutan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $category->name }}</td>
                        <td>{{ ucfirst($category->layout) }}</td>
                        <td>{{ $category->columns }}</td>
                        <td>{!! $category->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Arsip</span>' !!}</td>
                        <td>{{ $category->urutan }}</td>
                        <td>
                            <a href="{{ route('admin.publication-category.edit', $category->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.publication-category.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
