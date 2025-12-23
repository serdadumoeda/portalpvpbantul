@extends('layouts.admin')

@php
    $statusOptions = $statusOptions ?? \App\Models\CourseSubmission::statuses();
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Submission Tugas</h4>
        <small class="text-muted">Kelola submission peserta dan penilaian.</small>
    </div>
    <span class="text-muted small">Gunakan filter untuk mempercepat pencarian.</span>
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
                        <option value="{{ $id }}" @selected(request('class_id', $classFilter ?? null) === $id)>{{ $title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <label class="form-label mb-1">Tugas</label>
                <select name="assignment_id" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($assignments as $id => $title)
                        <option value="{{ $id }}" @selected(request('assignment_id', $assignmentFilter ?? null) === $id)>{{ $title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-outline-primary">Terapkan</button>
            </div>
            @if(request('status') || request('class_id') || request('assignment_id'))
                <div class="col-auto">
                    <a href="{{ route('admin.course-submission.index') }}" class="btn btn-sm btn-link text-decoration-none">Reset</a>
                </div>
            @endif
            <div class="col-auto ms-auto">
                <a href="{{ route('admin.course-submission.export.csv', request()->all()) }}" class="btn btn-sm btn-outline-primary">Export CSV</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Peserta</th>
                        <th>Kelas / Tugas</th>
                        <th>Dikirim</th>
                        <th>Nilai</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $sub)
                        <tr>
                            <td>{{ $submissions->firstItem() + $loop->index }}</td>
                            <td>{{ $sub->user->name ?? $sub->user_id }}</td>
                            <td>
                                <div class="small fw-bold">{{ $sub->assignment->course->title ?? '-' }}</div>
                                <div class="small text-muted">{{ $sub->assignment->title ?? '-' }}</div>
                            </td>
                            <td class="small">{{ $sub->submitted_at ? $sub->submitted_at->format('d M Y H:i') : '-' }}</td>
                            <td class="fw-bold">{{ $sub->total_score ?? '-' }}</td>
                            <td class="text-nowrap">
                                @php
                                    $badgeClass = [
                                        'submitted' => 'bg-warning text-dark',
                                        'graded' => 'bg-success',
                                        'reopened' => 'bg-secondary',
                                    ][$sub->status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $statusOptions[$sub->status] ?? $sub->status }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.course-submission.edit', $sub->id) }}" class="btn btn-sm btn-warning">Nilai/Edit</a>
                                <form action="{{ route('admin.course-submission.destroy', $sub->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus submission ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada submission.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $submissions->links() }}
        </div>
    </div>
</div>
@endsection
