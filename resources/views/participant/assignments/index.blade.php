@extends('layouts.participant')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Tugas/Kuis Anda</h4>
        <small class="text-muted">Pilih kelas untuk memfilter tugas yang relevan.</small>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end mb-3">
            <div class="col-sm-4 col-md-3">
                <label class="form-label mb-1">Filter Kelas</label>
                <select name="class_id" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($classes as $id => $title)
                        <option value="{{ $id }}" @selected(request('class_id', $classId ?? null) === $id)>{{ $title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-outline-primary">Terapkan</button>
            </div>
            @if(request('class_id'))
                <div class="col-auto">
                    <a href="{{ route('participant.assignments') }}" class="btn btn-sm btn-link text-decoration-none">Reset</a>
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
                        <th>Aksi</th>
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
                            <td>
                                <a href="{{ route('participant.assignments.show', $assignment) }}" class="btn btn-sm btn-primary">Kumpulkan</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada tugas.</td>
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
