@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Lowongan Kerja</h3>
    <a href="{{ route('admin.lowongan.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Lowongan</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Judul</th>
                    <th>Perusahaan</th>
                    <th>Lokasi</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vacancies as $vacancy)
                    <tr>
                        <td>{{ $vacancies->firstItem() + $loop->index }}</td>
                        <td class="fw-semibold">{{ $vacancy->judul }}</td>
                        <td>{{ $vacancy->perusahaan ?? '-' }}</td>
                        <td>{{ $vacancy->lokasi ?? '-' }}</td>
                        <td>{{ $vacancy->deadline ? $vacancy->deadline->format('d M Y') : '-' }}</td>
                        <td>{!! $vacancy->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Arsip</span>' !!}</td>
                        <td>
                            <a href="{{ route('admin.lowongan.edit', $vacancy->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.lowongan.destroy', $vacancy->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus lowongan ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada data lowongan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $vacancies->links() }}
    </div>
</div>
@endsection
