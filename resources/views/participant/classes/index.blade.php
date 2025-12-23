@extends('layouts.participant')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Kelas Saya</h4>
        <small class="text-muted">Daftar kelas yang Anda ikuti.</small>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="list-group">
            @forelse($classes as $class)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">{{ $class->title }}</div>
                            <div class="small text-muted">{{ $class->instructor?->name ?? '-' }} • Tugas: {{ $class->assignments_count ?? 0 }} • Sesi: {{ $class->sessions_count ?? 0 }}</div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('participant.class.announcements.index', $class) }}" class="btn btn-sm btn-outline-secondary">Pengumuman</a>
                            <a href="{{ route('participant.class.forum.index', $class) }}" class="btn btn-sm btn-outline-primary">Forum</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-muted">Anda belum terdaftar pada kelas mana pun.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
