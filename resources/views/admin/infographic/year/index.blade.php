@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Infografis Alumni</h3>
        <p class="text-muted mb-0">Kelola tahun, hero section, dan deskripsi sesuai kebutuhan landing page.</p>
    </div>
    <a href="{{ route('admin.infographic-year.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah Tahun</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Tahun</th>
                    <th>Judul</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($years as $year)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $year->tahun }}</td>
                        <td>{{ $year->headline }}</td>
                        <td>{{ $year->urutan }}</td>
                        <td>{!! $year->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Arsip</span>' !!}</td>
                        <td>
                            <a href="{{ route('admin.infographic-year.edit', $year->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.infographic-year.destroy', $year->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data tahun infografis.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
