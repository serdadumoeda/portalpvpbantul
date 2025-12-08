@extends('layouts.app')

@php
    $resolveImage = function ($path, $fallback = null) {
        if (!$path) {
            return $fallback;
        }
        return \Illuminate\Support\Str::startsWith($path, ['http', 'https']) ? $path : asset($path);
    };
    $heroBg = $resolveImage($setting->hero_image, 'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?auto=format&fit=crop&w=1400&q=70');
@endphp
@push('styles')
<style>
    .pub-hero {
        background: linear-gradient(135deg, rgba(7,60,108,.9), rgba(7,108,103,.8)), url('{{ $heroBg }}') center/cover;
        border-bottom-left-radius: 48px;
        border-bottom-right-radius: 48px;
        color: #fff;
    }
    .pub-card {
        border-radius: 20px;
        border: 1px solid #e4eef4;
        padding: 24px;
        background: #fff;
        box-shadow: 0 18px 45px -35px rgba(15,45,72,.7);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .pub-card img {
        border-radius: 12px;
        width: 100%;
        height: 140px;
        object-fit: cover;
        margin-bottom: 12px;
    }
    .pub-badge { font-size:.75rem; letter-spacing:.1em; }
    .pub-infographic {
        border-radius: 18px;
        padding: 28px;
        background: #0f5252;
        color: #fff;
        min-height: 260px;
    }
    .pub-downloads li { border-bottom:1px dashed #d6dfe7; padding:12px 0; }
    .pub-downloads li:last-child { border-bottom: none; }
    .pub-section + .pub-section { margin-top: 60px; }
    .video-wrapper { border-radius: 20px; overflow:hidden; box-shadow: 0 25px 50px -35px rgba(0,0,0,.5); }
</style>
@endpush

@section('content')
@php
    $heroTitle = $setting->hero_title ?? 'Publikasi';
    $heroDescription = $setting->hero_description ?? 'Publikasi terkini mengenai layanan, program, dan capaian Satpel PVP Bantul.';
    $heroButtonText = $setting->hero_button_text ?? 'Lihat Semua';
    $heroButtonLink = $setting->hero_button_link ?? '#publikasi';
@endphp
<section class="pub-hero py-5">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="badge bg-white text-primary fw-semibold mb-3">Publikasi</span>
                <h1 class="display-5 fw-bold text-white">{{ $heroTitle }}</h1>
                <p class="text-white-50 mb-4">{{ $heroDescription }}</p>
                <a href="{{ $heroButtonLink }}" class="btn btn-outline-light rounded-pill px-4">{{ $heroButtonText }}</a>
            </div>
            <div class="col-lg-5 text-center">
                <img src="{{ $resolveImage($setting->hero_image, 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1100&q=70') }}" class="img-fluid rounded-4 shadow" alt="Publikasi">
            </div>
        </div>
    </div>
</section>

<section id="publikasi" class="py-5">
    <div class="container">
        <div class="mb-5">
            <h2 class="fw-bold mb-2">{{ $setting->intro_title ?? 'Pencapaian Satpel PVP Bantul' }}</h2>
            <p class="text-muted mb-0">{{ $setting->intro_description ?? 'Dokumentasi penghargaan dan publikasi resmi yang menunjukan kinerja layanan kami.' }}</p>
        </div>

        @foreach($categories as $category)
            <div class="pub-section">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
                    <div>
                        <span class="text-uppercase text-muted small fw-semibold">{{ $category->subtitle }}</span>
                        <h3 class="fw-bold mb-1">{{ $category->name }}</h3>
                        <p class="text-muted mb-0">{{ $category->description }}</p>
                    </div>
                </div>
                @php $layout = $category->layout; @endphp
                @if($layout === 'cards')
                    @php
                        $columnCount = max(1, min(4, (int) $category->columns ?: 4));
                        $colMap = [1 => 12, 2 => 6, 3 => 4, 4 => 3];
                        $colClass = $colMap[$columnCount] ?? 3;
                    @endphp
                    <div class="row g-4">
                        @forelse($category->items as $item)
                            <div class="col-md-6 col-lg-{{ $colClass }}">
                                <div class="pub-card">
                                    @if($item->image)
                                        <img src="{{ $resolveImage($item->image, 'https://placehold.co/400x240?text=Publikasi') }}" alt="{{ $item->title }}">
                                    @endif
                                    @if($item->badge)
                                        <span class="pub-badge text-primary fw-semibold mb-2">{{ $item->badge }}</span>
                                    @endif
                                    <h5 class="fw-bold">{{ $item->title }}</h5>
                                    <p class="text-muted small flex-grow-1">{{ $item->description }}</p>
                                    @if($item->button_text && $item->button_link)
                                        <a href="{{ $item->button_link }}" class="btn btn-sm btn-outline-primary rounded-pill mt-2" target="_blank">{{ $item->button_text }}</a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-muted">Belum ada item.</div>
                        @endforelse
                    </div>
                @elseif($layout === 'infographic')
                    <div class="row g-4">
                        @forelse($category->items as $item)
                            <div class="col-md-6">
                                <div class="pub-infographic h-100">
                                    <div class="text-uppercase small text-warning mb-2">{{ $item->badge }}</div>
                                    <h4 class="fw-bold">{{ $item->title }}</h4>
                                    <p class="mb-3">{{ $item->description }}</p>
                                    @if($item->extra)
                                        <div class="row g-2">
                                            @foreach($item->extra as $extra)
                                                <div class="col-6">
                                                    <div class="bg-white bg-opacity-10 rounded-3 p-2 text-center">{{ $extra }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-muted">Belum ada infografis.</div>
                        @endforelse
                    </div>
                @elseif($layout === 'downloads')
                    <ul class="list-unstyled pub-downloads">
                        @forelse($category->items as $item)
                            <li class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $item->title }}</strong>
                                    <p class="text-muted small mb-0">{{ $item->description }}</p>
                                </div>
                                @if($item->button_link)
                                    <a href="{{ $item->button_link }}" class="btn btn-sm btn-outline-primary rounded-pill" target="_blank">{{ $item->button_text ?? 'Unduh' }}</a>
                                @endif
                            </li>
                        @empty
                            <li class="text-muted">Belum ada materi.</li>
                        @endforelse
                    </ul>
                @elseif($layout === 'alumni')
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-7">
                            <div class="video-wrapper">
                                <iframe src="{{ $setting->alumni_video_url ?? 'https://www.youtube.com/embed/dQw4w9WgXcQ' }}" width="100%" height="360" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="list-group shadow-sm">
                                @forelse($category->items as $item)
                                    <div class="list-group-item">
                                        <h6 class="fw-bold mb-1">{{ $item->title }}</h6>
                                        <p class="mb-1 text-muted small">{{ $item->description }}</p>
                                        @if($item->button_link)
                                            <a href="{{ $item->button_link }}" target="_blank" class="text-primary small">{{ $item->button_text ?? 'Lihat Detail' }}</a>
                                        @endif
                                    </div>
                                @empty
                                    <div class="list-group-item text-muted">Belum ada cerita alumni.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row g-4">
                        @forelse($category->items as $item)
                            <div class="col-md-6">
                                <div class="pub-card">
                                    <h5 class="fw-bold">{{ $item->title }}</h5>
                                    <p class="text-muted">{{ $item->description }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-muted">Belum ada konten.</div>
                        @endforelse
                    </div>
                @endif
            </div>
        @endforeach

        <div class="mt-5 p-4 rounded-4" style="background:#e0f6f3;">
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <h4 class="fw-bold text-primary mb-1">Anda siap tingkatkan skill dengan kami?</h4>
                    <p class="text-muted mb-0">Eksplorasi pelatihan unggulan, media publikasi, dan materi resmi yang disiapkan Satpel PVP Bantul.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('pelatihan.katalog') }}" class="btn btn-primary rounded-pill px-4 me-2">Katalog Pelatihan</a>
                    <a href="{{ route('sertifikasi') }}" class="btn btn-outline-primary rounded-pill px-4">Info Sertifikasi</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
