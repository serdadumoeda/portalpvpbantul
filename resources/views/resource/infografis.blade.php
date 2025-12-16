@extends('layouts.app')

@push('styles')
<style>
    .infografis-hero {
        background: linear-gradient(135deg, rgba(5,54,94,.9), rgba(17,112,142,.8)), url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=70') center/cover;
        border-bottom-left-radius: 48px;
        border-bottom-right-radius: 48px;
        color: #fff;
    }
    .infografis-hero .btn-outline-light {
        border-radius: 30px;
        padding: 0.75rem 2.5rem;
    }
    .metric-card {
        border-radius: 18px;
        padding: 24px;
        background: #fff;
        box-shadow: 0 20px 45px -30px rgba(14,36,64,.6);
        min-height: 130px;
    }
    .metric-card h4 { font-size: 2rem; margin-bottom: .25rem; }
    .dashboard-frame {
        border-radius: 18px;
        border: 1px solid #dfe7ee;
        overflow: hidden;
        background: #f7f7f7;
    }
    .dashboard-header {
        background: #f0f0f0;
        padding: 20px;
        border-bottom: 1px solid #e0e0e0;
        text-transform: uppercase;
        letter-spacing: .1rem;
        font-weight: 600;
    }
    .dashboard-body { padding: 30px; }
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 16px;
    }
    .dashboard-grid .card {
        border-radius: 14px;
        border: 1px solid #e6e6e6;
        background: #fff;
        padding: 16px;
        min-height: 140px;
    }
    .section-divider {
        height: 2px;
        background: linear-gradient(to right, transparent, #d9e2ec, transparent);
        margin: 40px 0;
    }
</style>
@endpush

@section('content')
@php
    $resolveImage = function ($path) {
        if (!$path) return null;
        return \Illuminate\Support\Str::startsWith($path, ['http', 'https']) ? $path : asset($path);
    };
    $heroYear = $years->first();
    $defaultHero = 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=70';
    $heroImage = $resolveImage($heroYear?->hero_image) ?? $defaultHero;
    $heroTitle = $heroYear?->headline ?? 'Infografis Data Alumni';
    $heroDescription = $heroYear?->description ?? 'Akses gambaran lengkap persebaran lulusan, penempatan kerja, dan capaian Satpel PVP Bantul setiap tahun.';
@endphp
<section class="infografis-hero py-5" style="background-image: linear-gradient(135deg, rgba(5,54,94,.9), rgba(17,112,142,.8)), url('{{ $heroImage }}');">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <span class="badge bg-white text-primary fw-semibold mb-2">Infografis Data Alumni</span>
                @if($heroYear?->title)
                    <p class="text-uppercase small text-white-50 mb-2">{{ $heroYear->title }}</p>
                @endif
                <h1 class="display-5 fw-bold text-white">{{ $heroTitle }}</h1>
                <p class="text-white-50 mb-4">{{ $heroDescription }}</p>
                @if($heroYear?->hero_button_link)
                    <a href="{{ $heroYear->hero_button_link }}" class="btn btn-outline-light" target="_blank">{{ $heroYear->hero_button_text ?? 'Lihat Selengkapnya' }}</a>
                @else
                    <a href="#infografis" class="btn btn-outline-light">Lihat Selengkapnya</a>
                @endif
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://images.unsplash.com/photo-1531297484001-80022131f5a1?auto=format&fit=crop&w=900&q=70" class="img-fluid rounded-4 shadow" alt="Infografis">
            </div>
        </div>
    </div>
</section>

<section id="infografis" class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-3">Infografis Alumni</h2>
        <p class="text-muted">Ringkasan per tahun mengenai kinerja pelatihan, penempatan, dan pengembangan kompetensi.</p>

        @forelse($years as $block)
            <div class="section-divider"></div>
            <h4 class="fw-bold mb-1">Tahun {{ $block->tahun }}</h4>
            @if($block->title)
                <p class="text-muted mb-3">{{ $block->title }}</p>
            @endif
            <div class="dashboard-frame mb-4">
                <div class="dashboard-header text-center">
                    DASHBOARD INFOGRAFIS PELATIHAN BERBASIS KOMPETENSI (PBK) BPVP BANTUL {{ $block->tahun }}
                </div>
                <div class="dashboard-body">
                    <div class="row g-4">
                        @forelse($block->metrics as $metric)
                            <div class="col-md-4 col-lg-2">
                                <div class="metric-card text-center">
                                    <h4>{{ $metric->value }}</h4>
                                    <p class="text-muted small mb-0">{{ $metric->label }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted">Belum ada data metric.</div>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        <img src="{{ $resolveImage($block->hero_image) ?? $heroImage }}" class="img-fluid rounded shadow-sm" alt="Dashboard {{ $block->tahun }}">
                    </div>
                    @if($block->embeds->count())
                        <div class="mt-4">
                            @foreach($block->embeds as $embed)
                                <h6 class="fw-bold mb-2">{{ $embed->title }}</h6>
                                <div class="ratio ratio-16x9 mb-4" style="min-height: {{ $embed->height ?? 600 }}px;">
                                    <iframe src="{{ $embed->url }}" style="border:0;" allowfullscreen height="{{ $embed->height ?? 600 }}"></iframe>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="dashboard-grid mt-4">
                        @forelse($block->cards as $card)
                            <div class="card">
                                <h6 class="fw-bold mb-2">{{ $card->title }}</h6>
                                <ul class="list-unstyled small text-muted mb-0">
                                    @forelse((array) $card->entries as $entry)
                                        <li>• {{ $entry }}</li>
                                    @empty
                                        <li>Data belum tersedia.</li>
                                    @endforelse
                                </ul>
                            </div>
                        @empty
                            <div class="card">
                                <p class="text-muted mb-0">Belum ada kartu ringkasan untuk tahun ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info mt-4">Belum ada data infografis. Silakan tambahkan melalui panel admin.</div>
        @endforelse
    </div>
</section>
@endsection
