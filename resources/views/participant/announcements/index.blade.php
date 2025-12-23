@extends('layouts.participant')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Pengumuman: {{ $class->title }}</h4>
        <small class="text-muted">Informasi terbaru dari pengelola kelas.</small>
    </div>
    <a href="{{ route('participant.classes') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="list-group">
            @forelse($announcements as $item)
                <a href="{{ route('participant.class.announcements.show', [$class, $item]) }}" class="list-group-item list-group-item-action">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">{{ $item->title }}</div>
                            <div class="small text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($item->body), 120) }}</div>
                        </div>
                        <span class="small text-muted">{{ $item->published_at?->format('d M Y H:i') }}</span>
                    </div>
                </a>
            @empty
                <div class="text-muted">Belum ada pengumuman.</div>
            @endforelse
        </div>
        <div class="mt-3">{{ $announcements->links() }}</div>
    </div>
</div>
@endsection
