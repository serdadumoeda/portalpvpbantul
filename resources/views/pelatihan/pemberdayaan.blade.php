@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .empower-hero{
        background:linear-gradient(135deg, rgba(7,60,108,.92), rgba(7,108,103,.85));
        border-bottom-left-radius:48px;
        border-bottom-right-radius:48px;
        padding:4rem 0;
        color:#fff;
    }
    .empower-hero-card{
        border-radius:32px;
        background:rgba(255,255,255,.08);
        border:1px solid rgba(255,255,255,.2);
        padding:3rem;
        min-height:360px;
        box-shadow:0 40px 110px -60px rgba(0,0,0,.6);
    }
    .empower-hero-card p{color:rgba(255,255,255,.75);}
    .empower-hero-image{
        height:360px;
        border-radius:32px;
        overflow:hidden;
        box-shadow:0 35px 80px -55px rgba(0,0,0,.5);
    }
    .empower-hero-image img{
        width:100%;
        height:100%;
        object-fit:cover;
    }
</style>
@endpush
<section class="empower-hero">
    <div class="container">
        <div class="row align-items-stretch g-4">
            <div class="col-lg-8">
                <div class="empower-hero-card h-100">
                    <span class="badge bg-white text-primary fw-semibold mb-3 shadow-sm">Program Sosial</span>
                    <h1 class="fw-bold text-white mb-2">Pemberdayaan</h1>
                    <p>Pemberdayaan masyarakat melalui inovasi, kolaborasi, dan transformasi sosial di Satpel PVP Bantul.</p>
                    <a href="#konten" class="btn btn-outline-light rounded-pill px-4">Lihat Selengkapnya</a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="empower-hero-image">
                    <img src="https://images.unsplash.com/photo-1485217988980-11786ced9454?auto=format&fit=crop&w=1100&q=70" alt="Pemberdayaan">
                </div>
            </div>
        </div>
    </div>
</section>

<section id="konten" class="py-5">
    <div class="container">
        <div class="row g-4">
            @forelse($empowerments as $index => $item)
            <div class="col-12">
                <div class="row align-items-center g-3">
                    @if($index % 2 === 0)
                        <div class="col-md-7 order-1 order-md-0">
                            <h4 class="fw-bold">{{ $item->judul }}</h4>
                            <p class="text-muted" style="line-height:1.7;">{{ $item->deskripsi }}</p>
                        </div>
                        <div class="col-md-5">
                            <div class="rounded-4 overflow-hidden shadow-sm">
                                <img src="{{ $item->gambar ? asset($item->gambar) : 'https://placehold.co/520x260?text=Pemberdayaan' }}" class="img-fluid w-100" alt="{{ $item->judul }}">
                            </div>
                        </div>
                    @else
                        <div class="col-md-5 order-0 order-md-0">
                            <div class="rounded-4 overflow-hidden shadow-sm">
                                <img src="{{ $item->gambar ? asset($item->gambar) : 'https://placehold.co/520x260?text=Pemberdayaan' }}" class="img-fluid w-100" alt="{{ $item->judul }}">
                            </div>
                        </div>
                        <div class="col-md-7 order-1 order-md-0">
                            <h4 class="fw-bold">{{ $item->judul }}</h4>
                            <p class="text-muted" style="line-height:1.7;">{{ $item->deskripsi }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted">Belum ada data pemberdayaan.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
