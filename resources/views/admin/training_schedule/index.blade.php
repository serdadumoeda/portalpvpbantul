@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Jadwal Pelatihan</h3>
    <form action="{{ route('admin.skillhub.sync') }}" method="POST" class="mb-0">
        @csrf
        <button class="btn btn-primary"><i class="fas fa-sync-alt me-1"></i> Sinkronisasi dari Pusat</button>
    </form>
</div>
@if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
<div class="card shadow-sm border-0">
    <div class="card-body table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Judul</th>
                    <th>External ID</th>
                    <th>Batch ID</th>
                    <th>Bulan</th>
                    <th>Tahun</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $schedule)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $schedule->judul }}</td>
                    <td>{{ $schedule->external_id ?? '-' }}</td>
                    <td>{{ $schedule->batch_id ?? '-' }}</td>
                    <td>{{ $schedule->bulan }}</td>
                    <td>{{ $schedule->tahun }}</td>
                    <td>{{ $schedule->mulai ? $schedule->mulai->format('d M Y') : '-' }}</td>
                    <td>{{ $schedule->selesai ? $schedule->selesai->format('d M Y') : '-' }}</td>
                    <td><span class="badge {{ $schedule->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $schedule->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td class="d-flex gap-2">
                        <a href="{{ route('admin.training-schedule.show', $schedule->id) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="text-center text-muted py-4">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
