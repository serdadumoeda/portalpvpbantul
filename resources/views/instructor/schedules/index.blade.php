@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">Jadwal Instruktur</h4>
        <small class="text-muted">Kelola draft jadwal cetak yang akan dipreview/diunduh.</small>
    </div>
    <a href="{{ route('instructor.schedules.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Jadwal baru
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Judul</th>
                    <th>Diperbarui</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $schedule)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $schedule->title }}</td>
                        <td>{{ $schedule->updated_at->format('d M Y H:i') }}</td>
                        <td class="d-flex gap-2">
                            <a class="btn btn-sm btn-outline-secondary" target="_blank" href="{{ route('instructor.schedules.preview', $schedule) }}">Preview</a>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('instructor.schedules.edit', $schedule) }}">Edit</a>
                            <form action="{{ route('instructor.schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada jadwal. Klik “Jadwal baru”.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
