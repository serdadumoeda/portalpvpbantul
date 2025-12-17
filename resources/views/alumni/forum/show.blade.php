@extends('layouts.app')

@php
use App\Services\ForumRenderer;
@endphp

@section('title', $topic->title)

@section('content')
<section class="forum-hero">
    <div class="container">
        @php
            $shareUrl = route('alumni.forum.show', $topic);
            $shareText = urlencode("Diskusi: {$topic->title} dari Forum Alumni PVP Bantul");
        @endphp
        <div class="d-flex flex-column gap-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('alumni.forum.index') }}">Forum Alumni</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ \Illuminate\Support\Str::limit($topic->title, 42) }}</li>
                    </ol>
                </nav>
                <div class="action-rail" role="group" aria-label="Aksi cepat forum">
                    <a href="{{ route('alumni.forum.index') }}" class="btn btn-light btn-sm d-inline-flex align-items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    <a href="{{ route('alumni.forum.create') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-2">
                        <i class="fas fa-pen"></i>
                        <span>Topik baru</span>
                    </a>
                </div>
            </div>
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
                <div class="w-100">
                    <h1 class="fw-bold mb-2">{{ $topic->title }}</h1>
                    <div class="d-flex align-items-center forum-meta">
                        <span class="meta-item text-white"><i class="fa-regular fa-user"></i><span>{{ $topic->user->name }}</span></span>
                        <span class="dot"></span>
                        <span class="meta-item text-white"><i class="fa-regular fa-clock"></i><span>{{ $topic->created_at->translatedFormat('d M Y H:i') }}</span></span>
                        <span class="dot"></span>
                        <span class="meta-item text-white"><i class="fa-regular fa-thumbs-up"></i><span>{{ $topic->reaction_count }} suka</span></span>
                    </div>
                </div>
                <div class="share-inline text-start text-lg-end">
                    <p class="mb-1 fw-semibold">Bagikan diskusi ini</p>
                    <div class="d-flex flex-wrap justify-content-lg-end gap-2">
                        <span class="visually-hidden">Media sosial</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-light btn-sm icon-only-share" aria-label="Bagikan ke Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://x.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ $shareText }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-light btn-sm icon-only-share text-dark border-secondary" aria-label="Bagikan ke X">
                            <i class="fa-brands fa-x"></i>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-light btn-sm icon-only-share text-dark border-secondary" aria-label="Bagikan ke LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="https://www.instagram.com/?url={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-light btn-sm icon-only-share text-dark border-secondary" aria-label="Bagikan ke Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.threads.net/share?text={{ $shareText }}%20{{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-light btn-sm icon-only-share text-dark border-secondary" aria-label="Bagikan ke Threads">
                            <i class="fa-brands fa-threads"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode("{$shareText} {$shareUrl}") }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-light btn-sm icon-only-share text-dark border-secondary" aria-label="Bagikan ke WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-shell">
    <div class="container">
        @if(! $topic->is_approved)
            <div class="alert alert-warning rounded-4" role="status">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-shield-check text-warning"></i>
                    <div>
                        Topik ini belum dipublikasikan. Admin akan meninjau dulu sebelum tampil di forum publik.
                    </div>
                </div>
            </div>
        @endif

        <article class="card forum-card border-0 mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap forum-divider pb-3">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <span class="meta-item"><i class="fa-regular fa-user"></i><span>{{ $topic->user->name }}</span></span>
                        <span class="meta-item"><i class="fa-regular fa-clock"></i><span>{{ $topic->created_at->translatedFormat('d M Y H:i') }}</span></span>
                    </div>
                    <span class="badge bg-success-subtle text-success">{{ $topic->reaction_count }} suka</span>
                </div>

                <div class="content-body lead">
                    {!! ForumRenderer::renderWithMentions($topic->content) !!}
                </div>

                <div class="d-flex gap-3 align-items-center mt-3">
                    <form action="{{ route('alumni.forum.react', $topic) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="type" value="like">
                        <button type="submit" class="btn btn-outline-success btn-sm" aria-label="Suka topik {{ $topic->title }}">
                            <i class="fa-regular fa-thumbs-up me-1"></i> Like
                        </button>
                    </form>
                </div>
            </div>
        </article>

        @if($pendingOwnPosts->isNotEmpty())
            <div class="alert alert-info rounded-4" role="status">
                Balasan kamu sedang menunggu verifikasi admin dan akan muncul setelah disetujui.
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-7 order-2 order-lg-1">
                <div class="card forum-card border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                            <h5 class="fw-bold mb-0">Percakapan</h5>
                            <span class="badge bg-secondary-subtle text-secondary">{{ $approvedPosts->count() }} balasan dipublikasikan</span>
                        </div>
                        @forelse($approvedPosts as $post)
                            <div class="post-item mb-3">
                                <div class="d-flex justify-content-between mb-2 flex-wrap gap-2">
                                    <div class="fw-semibold">{{ $post->user->name }}</div>
                                    <small class="text-muted">{{ $post->created_at->translatedFormat('d M Y H:i') }}</small>
                                </div>
                                <p class="text-muted mb-0">{!! ForumRenderer::renderWithMentions($post->content) !!}</p>
                                <div class="d-flex gap-2 align-items-center small mt-2">
                                    <span class="badge bg-primary-subtle">{{ $post->reaction_count }} suka</span>
                                    <form action="{{ route('alumni.forum.post.react', $post) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="type" value="like">
                                        <button type="submit" class="btn btn-outline-primary btn-sm" aria-label="Suka balasan dari {{ $post->user->name }}">
                                            Like
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5 empty-state">
                                Belum ada balasan yang dipublikasikan. Jadilah pelopor dengan menulis balasan.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-5 order-1 order-lg-2">
                <div class="card forum-card border-0">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Tulis balasan (mention alumni dengan @nama)</h5>
                        <form action="{{ route('alumni.forum.posts.store', $topic) }}" method="POST" class="reply-editor">
                            @csrf
                            <div class="mb-3">
                                <label for="reply-content" class="form-label fw-semibold">Konten balasan</label>
                                <textarea id="reply-content" name="content" rows="5" class="form-control" required placeholder="Tulis ide, pertanyaan, atau apresiasi untuk topik ini...">{{ old('content') }}</textarea>
                                @error('content')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Kirim untuk moderasi</button>
                        </form>
                        <div class="mt-3 small text-muted">
                            Mention menggunakan format <code>@nama</code>. Admin akan memverifikasi sebelum orang lain dapat membacanya.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
