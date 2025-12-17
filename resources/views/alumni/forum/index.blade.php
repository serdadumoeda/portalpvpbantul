@extends('layouts.app')

@php
use App\Services\ForumRenderer;
use Illuminate\Support\Str;
@endphp

@section('title', 'Forum Alumni')

@section('content')
<section class="forum-hero">
    <div class="container">
        <div class="d-flex flex-column gap-3">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                <div class="d-flex flex-column">
                    <span class="badge bg-white text-primary border-0 mb-2">Komunitas Alumni</span>
                    <h1 class="fw-bold mb-1">Forum Alumni Satpel PVP Bantul</h1>
                    <p class="mb-0" style="max-width:640px;">Berbagi pengalaman, tips karier, dan diskusi hangat dengan mention @nama teman alumni. Semua topik diverifikasi agar nyaman dibaca.</p>
                </div>
                <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                    <span class="chip bg-white text-dark border-0"><i class="fa-regular fa-bookmark"></i> {{ number_format($stats['topics']) }} topik terbit</span>
                    <span class="chip bg-white text-dark border-0"><i class="fa-regular fa-comments"></i> {{ number_format($stats['responses']) }} balasan disetujui</span>
                    <span class="chip bg-white text-dark border-0"><i class="fa-regular fa-user"></i> {{ number_format($stats['alumni']) }} alumni</span>
                </div>
            </div>
            @if($pendingTopicsCount)
                <div class="alert alert-warning rounded-4 mb-0" role="status">
                    <strong>{{ $pendingTopicsCount }}</strong> topik baru sedang menunggu verifikasi admin sebelum dipublikasikan.
                </div>
            @endif
        </div>
    </div>
</section>

<section class="section-shell">
    <div class="container">

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card forum-card border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Minggu ini</h6>
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="fs-5 fw-bold">{{ $engagementSummary['topics_created'] }}</div>
                                <small class="text-muted">Topik dibuat</small>
                            </div>
                            <div>
                                <div class="fs-5 fw-bold">{{ $engagementSummary['topics_approved'] }}</div>
                                <small class="text-muted">Topik disetujui</small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <div>
                                <div class="fs-5 fw-bold">{{ $engagementSummary['posts_created'] }}</div>
                                <small class="text-muted">Balasan dibuat</small>
                            </div>
                            <div>
                                <div class="fs-5 fw-bold">{{ $engagementSummary['posts_approved'] }}</div>
                                <small class="text-muted">Balasan disetujui</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card forum-card border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-semibold">Tren harian</h6>
                            <small class="text-muted">7 hari terakhir</small>
                        </div>
                        <div class="d-flex flex-wrap gap-3">
                            @forelse($engagementTrends as $entry)
                                <div class="border rounded-3 px-3 py-2 text-center" style="min-width:100px;">
                                    <div class="fw-semibold">{{ $entry->topics_approved }} / {{ $entry->posts_approved }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($entry->date)->format('d M') }}</small>
                                </div>
                            @empty
                                <div class="text-muted small px-3 py-2 border rounded-3">Belum ada data analytics.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="d-grid gap-4">
                    @if($popularTopics->isNotEmpty())
                        <div class="card forum-card border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-semibold mb-0">Topik Populer Saat Ini</h5>
                                    <small class="text-muted">Berdasarkan reaksi & balasan</small>
                                </div>
                                <div class="list-group list-group-flush">
                                    @foreach($popularTopics as $topic)
                                        <a href="{{ route('alumni.forum.show', $topic) }}" class="list-group-item list-group-item-action border-0 px-0 py-3 d-flex justify-content-between align-items-start gap-3">
                                            <div>
                                                <div class="fw-semibold text-dark">{{ $topic->title }}</div>
                                                <small class="text-muted d-block">{{ $topic->user->name }} · {{ $topic->reaction_count }} suka</small>
                                            </div>
                                            <span class="badge bg-secondary-subtle text-secondary">{{ $topic->posts_count ?? 0 }} balasan</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    @forelse($topics as $topic)
                        <article class="card forum-card border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-3 gap-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:48px; height:48px;">
                                            <span class="fw-bold">{{ Str::upper(Str::substr($topic->user->name, 0, 2)) }}</span>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold">
                                                <a href="{{ route('alumni.forum.show', $topic) }}" class="text-dark text-decoration-none">
                                                    {{ $topic->title }}
                                                </a>
                                            </h5>
                                            <small class="text-muted">
                                                {{ $topic->user->name }} · {{ $topic->updated_at->translatedFormat('d M Y H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        @if($topic->is_pinned)
                                            <span class="badge bg-success">Dipin</span>
                                        @endif
                                        <span class="badge bg-light text-muted">{{ $topic->replies_count }} balasan</span>
                                    </div>
                                </div>
                                <p class="text-muted mb-3">
                                    {!! ForumRenderer::renderWithMentions(Str::limit($topic->content, 200)) !!}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="small text-muted">
                                        Dibuat {{ $topic->created_at->diffForHumans() }}
                                    </div>
                                    <a href="{{ route('alumni.forum.show', $topic) }}" class="btn btn-sm btn-outline-secondary rounded-pill" aria-label="Baca selengkapnya tentang {{ $topic->title }}">Baca selengkapnya</a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="alert alert-info empty-state">
                            Belum ada topik terverifikasi. Yuk, buat topik pertamamu!
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $topics->links('pagination::bootstrap-5') }}
                </div>
            </div>

            <div class="col-lg-4">
                <div class="sticky-top forum-sidebar" style="top:80px;">
                    <div class="card forum-card border-0 mb-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Bagikan cerita singkat</h5>
                            <p class="text-muted small">Gunakan @nama untuk mention teman alumni. Topik kamu akan tampil setelah admin memverifikasi.</p>
                            <form action="{{ route('alumni.forum.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="topic-title" class="form-label small text-muted fw-semibold">Judul topik</label>
                                    <input id="topic-title" type="text" name="title" class="form-control form-control-sm" value="{{ old('title') }}" required placeholder="Contoh: Pengalaman magang di industri lokal">
                                    @error('title')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="topic-content" class="form-label small text-muted fw-semibold">Isi cerita</label>
                                    <textarea id="topic-content" name="content" rows="4" class="form-control form-control-sm" required placeholder="Bagikan pengalaman, sertakan @nama untuk mention.">{{ old('content') }}</textarea>
                                    @error('content')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary w-100 btn-sm" aria-label="Kirim topik untuk moderasi admin">Kirim ke admin</button>
                            </form>
                        </div>
                    </div>

                    <div class="card forum-card border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0 fw-semibold">Balasan terbaru</h6>
                                <small class="text-muted">{{ $latestReplies->count() }} item</small>
                            </div>
                            <div class="list-group list-group-flush">
                                @forelse($latestReplies as $reply)
                                    @php $topic = $reply->topic; @endphp
                                    @if(! $topic)
                                        @continue
                                    @endif
                                    <a href="{{ route('alumni.forum.show', $topic) }}" class="list-group-item list-group-item-action rounded-3 mb-2 p-3 border-0 shadow-sm">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>{{ $reply->user->name }}</strong>
                                                <small class="text-muted d-block">{{ $reply->created_at->translatedFormat('d M Y H:i') }}</small>
                                            </div>
                                            <span class="badge bg-info-subtle text-info">{{ Str::limit($topic->title, 25) }}</span>
                                        </div>
                                        <p class="text-muted small mb-0">{!! ForumRenderer::renderWithMentions(Str::limit($reply->content, 140)) !!}</p>
                                    </a>
                                @empty
                                    <div class="text-center text-muted small py-4 empty-state">Belum ada balasan.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="card forum-card border-0 mb-4">
                        <div class="card-body">
                            <h5 class="fw-semibold mb-3">Leaderboard Kontribusi</h5>
                            <ul class="list-group list-group-flush">
                                @forelse($leaderboard as $champion)
                                    <li class="list-group-item border-0 px-0 py-2 d-flex justify-content-between">
                                        <div>
                                            <strong>{{ $champion->name }}</strong>
                                            <div class="small text-muted">Topik {{ $champion->approved_topics_count }}, Balasan {{ $champion->approved_posts_count }}</div>
                                        </div>
                                        <span class="badge bg-primary-subtle">{{ $champion->approved_topics_count + $champion->approved_posts_count }}</span>
                                    </li>
                                @empty
                                    <li class="text-center text-muted small py-3">Ajak alumni lain untuk berkontribusi!</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    @if($challenge)
                        <div class="card forum-card border-0 mb-4">
                            <div class="card-body">
                                <h5 class="fw-semibold mb-2">Challenge Mingguan</h5>
                                <p class="small text-muted mb-3">Tema saat ini: {{ $challenge->title }}</p>
                                <p class="mb-3">{{ $challenge->question }}</p>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-success-subtle text-success">Tutup {{ $challenge->end_date->translatedFormat('d M Y') }}</span>
                                    <a href="{{ route('alumni.forum.index') }}" class="btn btn-outline-primary btn-sm">Gabung diskusi</a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="card forum-card border-0">
                        <div class="card-body">
                            <h6 class="fw-semibold">Mini Social Feed</h6>
                            <p class="small text-muted mb-1">Jaga percakapan tetap ramah, hormati mention, dan bantu satu sama lain.</p>
                            <div class="d-flex justify-content-between">
                                <span class="badge bg-success-subtle text-success">Verified only</span>
                                <span class="badge bg-secondary-subtle text-secondary">Mentions highlight</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
