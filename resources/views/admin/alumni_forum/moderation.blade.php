@extends('layouts.admin')

@php
use App\Services\ForumRenderer;
use Illuminate\Support\Str;
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Moderasi Forum Alumni</h4>
        <p class="text-muted small mb-0">Setujui topik dan balasan baru agar hanya konten yang sesuai yang terlihat publik.</p>
    </div>
    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm" target="_blank">
        <i class="fas fa-globe"></i> Lihat forum
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="small text-muted mb-1">Topik menunggu</p>
                <h4 class="mb-0">{{ $stats['pending_topics'] }}</h4>
                <small class="text-muted">Per hari ini</small>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="small text-muted mb-1">Balasan menunggu</p>
                <h4 class="mb-0">{{ $stats['pending_posts'] }}</h4>
                <small class="text-muted">Per hari ini</small>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="small text-muted mb-1">Topik disetujui</p>
                <h4 class="mb-0">{{ $stats['topics_approved'] }}</h4>
                <small class="text-muted">7 hari terakhir</small>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="small text-muted mb-1">Balasan disetujui</p>
                <h4 class="mb-0">{{ $stats['posts_approved'] }}</h4>
                <small class="text-muted">7 hari terakhir</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">Topik menunggu verifikasi</h5>
            </div>
            <div class="card-body">
                @forelse($pendingTopics as $topic)
                    <div class="border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-1">{{ $topic->title }}</h6>
                                <small class="text-muted">{{ $topic->user->name }} · {{ $topic->created_at->translatedFormat('d M Y H:i') }}</small>
                            </div>
                            <div class="d-flex gap-2">
                                <form action="{{ route('admin.alumni-forum.topic.approve', $topic) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                </form>
                                <form action="{{ route('admin.alumni-forum.topic.reject', $topic) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">Tolak</button>
                                </form>
                            </div>
                        </div>
                        <p class="small text-muted mb-0">{!! ForumRenderer::renderWithMentions(Str::limit($topic->content, 120)) !!}</p>
                    </div>
                @empty
                    <div class="text-center text-muted small py-4">
                        Tidak ada topik yang menunggu verifikasi.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">Balasan menunggu verifikasi</h5>
            </div>
            <div class="card-body">
                @forelse($pendingPosts as $post)
                    <div class="border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <p class="fw-semibold mb-0">{{ $post->user->name }}</p>
                                <small class="text-muted">
                                    Topik: {{ Str::limit(optional($post->topic)->title ?? 'Topik tidak tersedia', 30) }} · {{ $post->created_at->translatedFormat('d M Y H:i') }}
                                </small>
                            </div>
                            <div class="d-flex gap-2">
                                <form action="{{ route('admin.alumni-forum.post.approve', $post) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                </form>
                                <form action="{{ route('admin.alumni-forum.post.reject', $post) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">Tolak</button>
                                </form>
                            </div>
                        </div>
                        <p class="small text-muted mb-0">{!! ForumRenderer::renderWithMentions(Str::limit($post->content, 160)) !!}</p>
                    </div>
                @empty
                    <div class="text-center text-muted small py-4">
                        Tidak ada balasan menunggu verifikasi.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-4">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Analytics keterlibatan</h5>
                <ul class="list-unstyled small mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span>Topik dibuat (7 hari)</span>
                        <strong>{{ $engagementSummary['topics_created'] }}</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span>Topik disetujui</span>
                        <strong>{{ $engagementSummary['topics_approved'] }}</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span>Balasan dibuat</span>
                        <strong>{{ $engagementSummary['posts_created'] }}</strong>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span>Balasan disetujui</span>
                        <strong>{{ $engagementSummary['posts_approved'] }}</strong>
                    </li>
                </ul>
                <div class="text-muted small mt-3">Data diperbarui setiap kali topik/balasan baru masuk.</div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Audit moderasi terbaru</h5>
                <div class="list-group list-group-flush">
                    @forelse($recentActivities as $activity)
                        <div class="list-group-item border-0 px-0 py-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong class="text-dark">{{ $activity->action }}</strong>
                                    <div class="small text-muted">{{ $activity->description }}</div>
                                </div>
                                <small class="text-muted">{{ optional($activity->user)->name ?? 'Sistem' }}</small>
                            </div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</small>
                        </div>
                    @empty
                        <div class="text-center text-muted small py-3">
                            Belum ada aktivitas moderasi.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Badge favorit alumni</h5>
                <div class="list-group list-group-flush">
                    @forelse($topBadges as $badge)
                        <div class="list-group-item border-0 px-0 py-2 d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $badge->label }}</strong>
                                <div class="small text-muted">{{ $badge->description }}</div>
                            </div>
                            <span class="badge bg-primary-subtle text-primary">{{ $badge->users_count }} pemegang</span>
                        </div>
                    @empty
                        <div class="text-center text-muted small py-3">
                            Belum ada badge yang diberikan.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@if($challenge)
    <div class="row g-4 mt-4">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-semibold mb-1">Challenge Mingguan</h5>
                            <small class="text-muted">Tema {{ $challenge->start_date->translatedFormat('d M') }} s/d {{ $challenge->end_date->translatedFormat('d M') }}</small>
                        </div>
                        <span class="badge bg-success-subtle text-success">Aktif</span>
                    </div>
                    <p class="mb-2"><strong>{{ $challenge->title }}</strong></p>
                    <p class="mb-3 small text-muted">{{ $challenge->question }}</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('alumni.forum.index') }}" class="btn btn-outline-primary btn-sm">
                            Lihat challenge & diskusi
                        </a>
                        <span class="small text-muted align-self-center">Berakhir {{ $challenge->end_date->translatedFormat('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 mt-3">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h6 class="fw-semibold mb-1">Kelola Challenge Mingguan</h6>
                        <p class="small text-muted mb-0">
                            Buat, sunting, atau matikan challenge aktif langsung dari panel ini.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.alumni-forum.challenge.index') }}" class="btn btn-primary btn-sm">
                            Kelola challenge
                        </a>
                        <a href="{{ route('admin.alumni-forum.challenge.create') }}" class="btn btn-outline-secondary btn-sm">
                            Tambah baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
