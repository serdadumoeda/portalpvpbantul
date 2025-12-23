@extends('layouts.admin')

@php
    $statusOptions = $statusOptions ?? \App\Models\CourseClass::statuses();
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Kelas Pelatihan</h4>
        <small class="text-muted">Manajemen kelas sinkron/asinkron dengan workflow review/publish.</small>
    </div>
    <a href="{{ route('admin.course-class.create') }}" class="btn btn-primary btn-sm">Tambah Kelas</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end mb-3">
            <div class="col-sm-4 col-md-3">
                <label class="form-label mb-1">Filter Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($statusOptions as $key => $label)
                        <option value="{{ $key }}" @selected(request('status', $statusFilter ?? null) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-outline-primary">Terapkan</button>
            </div>
            @if(request('status'))
                <div class="col-auto">
                    <a href="{{ route('admin.course-class.index') }}" class="btn btn-sm btn-link text-decoration-none">Reset</a>
                </div>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Format</th>
                        <th>Instruktur</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $class)
                        <tr>
                            <td>{{ $classes->firstItem() + $loop->index }}</td>
                            <td>
                                <strong>{{ $class->title }}</strong>
                                <div class="text-muted small">{{ \Illuminate\Support\Str::limit(strip_tags($class->description), 80) }}</div>
                            </td>
                            <td><span class="badge bg-info text-dark text-uppercase">{{ $class->format }}</span></td>
                            <td>{{ $class->instructor?->name ?? '-' }}</td>
                            <td class="text-nowrap">
                                @php
                                    $status = $class->status ?? 'draft';
                                    $badgeClass = [
                                        'draft' => 'bg-secondary',
                                        'pending' => 'bg-warning text-dark',
                                        'published' => 'bg-success',
                                    ][$status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $statusOptions[$status] ?? ucfirst($status) }}</span>
                                <span class="badge {{ $class->is_active ? 'bg-success' : 'bg-dark' }}">{{ $class->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.course-class.edit', $class->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.course-class.destroy', $class->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kelas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada kelas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $classes->links() }}
        </div>
    </div>
</div>
@endsection
