@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .cert-hero{
        background:linear-gradient(135deg, rgba(7,60,108,.92), rgba(7,108,103,.85));
        border-bottom-left-radius:48px;
        border-bottom-right-radius:48px;
        padding:4rem 0;
        color:#fff;
    }
    .cert-hero-card{
        border-radius:32px;
        background:rgba(255,255,255,.08);
        border:1px solid rgba(255,255,255,.2);
        padding:3rem;
        min-height:360px;
        box-shadow:0 40px 110px -60px rgba(0,0,0,.6);
    }
    .cert-hero-card p{
        color:rgba(255,255,255,.75);
    }
    .cert-hero-image{
        height:360px;
        border-radius:32px;
        overflow:hidden;
        box-shadow:0 35px 80px -55px rgba(0,0,0,.5);
    }
    .cert-hero-image img{
        width:100%;
        height:100%;
        object-fit:cover;
    }
</style>
@endpush
@php
    $hero = optional($sections->get('hero'))->first();
    $intro = optional($sections->get('intro'))->first();
    $visi = optional($sections->get('visi'))->first();
    $misi = optional($sections->get('misi'))->first();
    $tujuan = optional($sections->get('tujuan'))->first();
    $highlight = optional($sections->get('highlight'))->first();
    $kluster = $schemes->get('kluster') ?? collect();
    $okupasi = $schemes->get('okupasi') ?? collect();
@endphp

<section class="cert-hero">
    <div class="container">
        <div class="row align-items-stretch g-4">
            <div class="col-lg-7">
                <div class="cert-hero-card h-100">
                    @if(!empty($hero?->badge))
                        <span class="badge rounded-pill bg-white text-primary px-3 py-2 mb-3" style="font-weight:600;">{{ $hero->badge }}</span>
                    @endif
                    <h1 class="display-5 fw-bold text-white mb-1">{{ $hero->title ?? 'Sertifikasi' }}</h1>
                    @if(!empty($hero?->subtitle))
                        <p class="fw-semibold text-white-75 mb-3">{{ $hero->subtitle }}</p>
                    @endif
                    <p class="lead mb-4">{{ $hero->description ?? 'Pastikan kompetensi Anda diakui melalui skema sertifikasi LSP Satpel PVP Bantul.' }}</p>
                    @if($hero?->button_text)
                        <a href="{{ $hero->button_url ?? '#' }}" class="btn btn-outline-light rounded-pill px-4">{{ $hero->button_text }}</a>
                    @endif
                </div>
            </div>
            <div class="col-lg-5">
                <div class="cert-hero-image">
                    <img src="{{ $hero?->image_path ? asset($hero->image_path) : 'https://placehold.co/480x360?text=Sertifikasi' }}" alt="Hero Sertifikasi">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <div class="rounded-4 overflow-hidden shadow-sm">
                    <img src="{{ $intro?->image_path ? asset($intro->image_path) : 'https://placehold.co/640x420?text=LSP' }}" class="img-fluid" alt="LSP BPVP">
                </div>
            </div>
            <div class="col-lg-6">
                <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold mb-3">{{ $intro->badge ?? 'LSP Satpel PVP Bantul' }}</span>
                <h2 class="fw-bold mb-1">{{ $intro->title ?? 'LSP Satpel PVP Bantul' }}</h2>
                @if($intro?->subtitle)
                    <p class="text-muted">{{ $intro->subtitle }}</p>
                @endif
                <p class="text-muted" style="line-height:1.7;">{{ $intro->description ?? 'Lembaga Sertifikasi Profesi Satpel PVP Bantul menghadirkan layanan uji kompetensi yang kredibel dengan fasilitas lengkap.' }}</p>
                @if(!empty($intro?->list_items))
                    <ul class="list-unstyled mt-3">
                        @foreach($intro->list_items as $point)
                            <li class="d-flex mb-2"><i class="fas fa-check text-success me-2 mt-1"></i><span>{{ $point }}</span></li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="py-5" style="background:#f5fbfb;">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                    <span class="badge bg-success bg-opacity-10 text-success fw-semibold mb-3">{{ $visi->badge ?? 'Visi' }}</span>
                    <h3 class="fw-bold mb-1">{{ $visi->title ?? 'Visi LSP Satpel PVP Bantul' }}</h3>
                    @if($visi?->subtitle)
                        <p class="text-muted">{{ $visi->subtitle }}</p>
                    @endif
                    <p class="text-muted">{{ $visi->description ?? 'Menjadi lembaga sertifikasi kompetensi terpercaya dalam menyiapkan SDM unggul dan berdaya saing.' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                    <span class="badge bg-info bg-opacity-10 text-info fw-semibold mb-3">{{ $misi->badge ?? 'Misi' }}</span>
                    <h3 class="fw-bold mb-1">{{ $misi->title ?? 'Misi Kami' }}</h3>
                    @if($misi?->subtitle)
                        <p class="text-muted">{{ $misi->subtitle }}</p>
                    @endif
                    @if(!empty($misi?->list_items))
                        <ul class="list-unstyled mb-0">
                            @foreach($misi->list_items as $point)
                                <li class="d-flex mb-2"><i class="fas fa-check-circle text-primary me-2 mt-1"></i><span>{{ $point }}</span></li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">{{ $misi->description ?? 'Memastikan standar kompetensi diterapkan secara konsisten melalui pelayanan uji yang profesional.' }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <h2 class="fw-bold mb-1">{{ $tujuan->title ?? 'Tujuan LSP Satpel PVP Bantul' }}</h2>
                @if($tujuan?->subtitle)
                    <p class="text-muted mb-2">{{ $tujuan->subtitle }}</p>
                @endif
                <p class="text-muted mb-4">{{ $tujuan->description ?? 'Menguatkan pengakuan kompetensi dan mendukung percepatan penempatan kerja bagi peserta pelatihan.' }}</p>
                @if(!empty($tujuan?->list_items))
                    <ul class="list-unstyled">
                        @foreach($tujuan->list_items as $point)
                            <li class="d-flex mb-2"><i class="fas fa-check text-success me-2 mt-1"></i><span>{{ $point }}</span></li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="col-lg-6">
                <div class="rounded-4 overflow-hidden shadow-sm">
                    <img src="{{ $tujuan?->image_path ? asset($tujuan->image_path) : 'https://placehold.co/620x420?text=Tujuan' }}" class="img-fluid" alt="Tujuan Sertifikasi">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5" style="background:#f5fbfb;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold mb-2">Skema Sertifikasi</span>
            <h2 class="fw-bold">Pilih Skema yang Sesuai</h2>
            <p class="text-muted">Skema disusun berdasarkan kebutuhan industri terbaru. Fokus pada kluster atau okupasi yang relevan dengan Anda.</p>
        </div>
        <div class="mb-4">
            <h4 class="fw-bold mb-3">Kluster</h4>
            <div class="row g-4">
                @forelse($kluster as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <img src="{{ $item->image_path ? asset($item->image_path) : 'https://placehold.co/350x220?text=Skema' }}" class="card-img-top" alt="{{ $item->title }}">
                            <div class="card-body d-flex flex-column">
                                <h5 class="fw-semibold mb-1">{{ $item->title }}</h5>
                                @if($item->subtitle)
                                    <p class="text-muted small mb-2">{{ $item->subtitle }}</p>
                                @endif
                                <p class="text-muted flex-grow-1">{{ $item->description }}</p>
                                @if($item->cta_text)
                                    <a href="{{ $item->cta_url ?? '#' }}" class="btn btn-outline-success rounded-pill mt-2">{{ $item->cta_text }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted">Belum ada data skema kluster.</div>
                @endforelse
            </div>
        </div>
        <div class="mt-5">
            <h4 class="fw-bold mb-3">Okupasi</h4>
            <div class="row g-4">
                @forelse($okupasi as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <img src="{{ $item->image_path ? asset($item->image_path) : 'https://placehold.co/350x220?text=Skema' }}" class="card-img-top" alt="{{ $item->title }}">
                            <div class="card-body d-flex flex-column">
                                <h5 class="fw-semibold mb-1">{{ $item->title }}</h5>
                                @if($item->subtitle)
                                    <p class="text-muted small mb-2">{{ $item->subtitle }}</p>
                                @endif
                                <p class="text-muted flex-grow-1">{{ $item->description }}</p>
                                @if($item->cta_text)
                                    <a href="{{ $item->cta_url ?? '#' }}" class="btn btn-outline-success rounded-pill mt-2">{{ $item->cta_text }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted">Belum ada data skema okupasi.</div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<section class="py-5" style="background:{{ $highlight->background ?? '#0b4f6c' }};">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8 text-white">
                <span class="badge bg-white text-primary fw-semibold mb-2">{{ $highlight->badge ?? 'Cek Hasil' }}</span>
                <h2 class="fw-bold text-white mb-1">{{ $highlight->title ?? 'Sudah Ikut Uji Kompetensi?' }}</h2>
                @if($highlight?->subtitle)
                    <p class="text-white-75">{{ $highlight->subtitle }}</p>
                @endif
                <p class="mb-0">{{ $highlight->description ?? 'Pantau hasil uji kompetensi Anda secara daring dan dapatkan sertifikat resmi.' }}</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                @if($highlight?->button_text)
                    <a href="{{ $highlight->button_url ?? '#' }}" class="btn btn-light rounded-pill px-4">{{ $highlight->button_text }}</a>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
