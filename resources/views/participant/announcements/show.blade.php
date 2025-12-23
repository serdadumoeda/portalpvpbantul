@extends('layouts.participant')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">{{ $announcement->title }}</h4>
        <small class="text-muted">{{ $class->title }} • {{ $announcement->published_at?->format('d M Y H:i') }}</small>
    </div>
    <a href="{{ route('participant.class.announcements.index', $class) }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        {!! nl2br(e($announcement->body)) !!}
        <div class="mt-3">
            <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">Cetak</button>
        </div>
    </div>
</div>
@endsection
