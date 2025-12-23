@extends('layouts.admin')

@php
    $statusOptions = $statusOptions ?? \App\Models\CourseAssignment::statuses();
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Tugas / Kuis</h4>
        <small class="text-muted">Kelola tugas/quiz per kelas dengan workflow review/publish.</small>
    </div>
    <a href="{{ route('admin.course-assignment.create') }}" class="btn btn-primary btn-sm">Tambah Tugas</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end mb-3">
            <div class="col-sm-3">
                <label class="form-label mb-1">Filter Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($statusOptions as $key => $label)
                        <option value="{{ $key }}" @selected(request('status', $statusFilter ?? null) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <label class="form-label mb-1">Filter Kelas</label>
                <select name="class_id" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($classes as $id => $title)
                        <option value="{{ $id }}" @selected(request('class_id', $classFilter ?? null) === $id)>{{ $title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-outline-primary">Terapkan</button>
            </div>
            @if(request('status') || request('class_id'))
                <div class="col-auto">
                    <a href="{{ route('admin.course-assignment.index') }}" class="btn btn-sm btn-link text-decoration-none">Reset</a>
                </div>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Kelas</th>
                        <th>Tipe</th>
                        <th>Due</th>
                        <th>Bobot</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $assignment)
                        <tr>
                            <td>{{ $assignments->firstItem() + $loop->index }}</td>
                            <td>{{ $assignment->title }}</td>
                            <td>{{ $assignment->course->title ?? '-' }}</td>
                            <td><span class="badge bg-info text-dark text-uppercase">{{ $assignment->type }}</span></td>
                            <td>{{ $assignment->due_at ? $assignment->due_at->format('d M Y H:i') : '-' }}</td>
                            <td>{{ $assignment->weight }}%</td>
                            <td class="text-nowrap">
                                @php
                                    $status = $assignment->status ?? 'draft';
                                    $badgeClass = [
                                        'draft' => 'bg-secondary',
                                        'pending' => 'bg-warning text-dark',
                                        'published' => 'bg-success',
                                    ][$status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $statusOptions[$status] ?? ucfirst($status) }}</span>
                                <span class="badge {{ $assignment->is_active ? 'bg-success' : 'bg-dark' }}">{{ $assignment->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.course-assignment.edit', $assignment->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <a href="{{ route('admin.course-assignment.export', $assignment->id) }}" class="btn btn-sm btn-outline-secondary">Export Nilai</a>
                                <form action="{{ route('admin.course-assignment.destroy', $assignment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus tugas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada tugas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $assignments->links() }}
        </div>
    </div>
</div>
@endsection
