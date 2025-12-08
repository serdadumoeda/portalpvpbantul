@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Konten Halaman Sertifikasi</h3>
    <a href="{{ route('admin.certification-content.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Tambah Konten
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th width="5%">#</th>
                    <th>Section</th>
                    <th>Judul</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><span class="badge bg-secondary">{{ ucfirst($item->section) }}</span></td>
                    <td>{{ $item->title ?? '-' }}</td>
                    <td>{{ $item->urutan }}</td>
                    <td>
                        @if($item->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.certification-content.edit', $item->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.certification-content.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus konten ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada konten.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
