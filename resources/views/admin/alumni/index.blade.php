@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Data Alumni</h3>
        <p class="text-muted mb-0">Kelola data alumni resmi serta status keaktifan mereka.</p>
    </div>
    <a href="{{ route('admin.alumni.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-user-plus me-1"></i> Tambah alumni</a>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <p class="text-muted small mb-1">Total alumni</p>
                <h4 class="fw-bold mb-0">{{ $stats['total'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <p class="text-muted small mb-1">Aktif</p>
                <h4 class="fw-bold mb-0 text-success">{{ $stats['active'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <p class="text-muted small mb-1">Tidak aktif</p>
                <h4 class="fw-bold mb-0 text-warning">{{ $stats['inactive'] }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.alumni.index') }}" method="GET" class="d-flex gap-2 flex-wrap mb-3">
            <input type="search" name="q" class="form-control form-control-sm" placeholder="Cari nama, email, jurusan" value="{{ $search }}">
            <button class="btn btn-outline-secondary btn-sm" type="submit">Cari</button>
        </form>
        <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jurusan</th>
                        <th>Tahun Lulus</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alumni as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->field_of_study ?? '-' }}</td>
                            <td>{{ $item->graduation_year ?? '-' }}</td>
                            <td>
                                <span class="badge rounded-pill {{ $item->is_active ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $item->is_active ? 'Aktif' : 'Non aktif' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.alumni.edit', $item) }}" class="btn btn-sm btn-outline-warning me-1"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.alumni.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data alumni.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="admin-pagination-wrapper mt-3">
            <div class="text-muted small">
                Menampilkan {{ $alumni->firstItem() ?? 0 }} - {{ $alumni->lastItem() ?? 0 }} dari {{ $alumni->total() }} alumni
            </div>
            <div>
                {{ $alumni->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
