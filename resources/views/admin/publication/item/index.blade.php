@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Item Publikasi</h3>
        <p class="text-muted mb-0">Konten kartu, laporan, majalah, hingga download materi.</p>
    </div>
    <a href="{{ route('admin.publication-item.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah Item</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Kategori</th>
                    <th>Judul</th>
                    <th>Badge</th>
                    <th>Tombol</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->category->name ?? '-' }}</td>
                        <td class="fw-semibold">{{ $item->title }}</td>
                        <td>{{ $item->badge }}</td>
                        <td>{{ $item->button_text }}</td>
                        <td>{{ $item->urutan }}</td>
                        <td>{!! $item->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Arsip</span>' !!}</td>
                        <td>
                            <a href="{{ route('admin.publication-item.edit', $item->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.publication-item.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus item ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada item publikasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
