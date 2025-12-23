@extends('layouts.admin')

@php
    $statusOptions = $statusOptions ?? \App\Models\CourseEnrollment::statuses();
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Enrollment Peserta</h4>
        <small class="text-muted">Kelola peserta yang terdaftar di tiap kelas.</small>
    </div>
    <a href="{{ route('admin.course-enrollment.create') }}" class="btn btn-primary btn-sm">Tambah Enrollment</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end mb-3">
            <div class="col-sm-3">
                <label class="form-label mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($statusOptions as $key => $label)
                        <option value="{{ $key }}" @selected(request('status', $statusFilter ?? null) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <label class="form-label mb-1">Kelas</label>
                <select name="class_id" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($classes as $id => $title)
                        <option value="{{ $id }}" @selected(request('class_id', $classFilter ?? null) == $id)>{{ $title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <label class="form-label mb-1">Peserta</label>
                <select name="user_id" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($users as $id => $name)
                        <option value="{{ $id }}" @selected(request('user_id', $userFilter ?? null) == $id)>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-outline-primary">Terapkan</button>
            </div>
            @if(request('status') || request('class_id') || request('user_id'))
                <div class="col-auto">
                    <a href="{{ route('admin.course-enrollment.index') }}" class="btn btn-sm btn-link text-decoration-none">Reset</a>
                </div>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Peserta</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th>Batas Forum</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollments as $enroll)
                        <tr>
                            <td>{{ $enrollments->firstItem() + $loop->index }}</td>
                            <td>{{ $enroll->user->name ?? $enroll->user_id }}</td>
                            <td>{{ $enroll->course->title ?? '-' }}</td>
                            <td>
                                @php
                                    $badgeClass = $enroll->status === 'active' ? 'bg-success' : 'bg-danger';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $statusOptions[$enroll->status] ?? $enroll->status }}</span>
                            </td>
                            <td class="small">
                                @if($enroll->muted_until && $enroll->muted_until->isFuture())
                                    <span class="badge bg-warning text-dark">Muted s/d {{ $enroll->muted_until->format('d M Y H:i') }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.course-enrollment.edit', $enroll->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.course-enrollment.destroy', $enroll->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus enrollment ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada enrollment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $enrollments->links() }}
        </div>
    </div>
</div>
@endsection
