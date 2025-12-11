@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .product-hero{
        background:linear-gradient(135deg, rgba(7,60,108,.92), rgba(7,108,103,.85));
        border-bottom-left-radius:48px;
        border-bottom-right-radius:48px;
        padding:4rem 0;
        color:#fff;
    }
    .product-hero-card{
        border-radius:32px;
        background:rgba(255,255,255,.08);
        border:1px solid rgba(255,255,255,.2);
        padding:3rem;
        min-height:360px;
        box-shadow:0 40px 110px -60px rgba(0,0,0,.6);
    }
    .product-hero-card p{color:rgba(255,255,255,.75);}
    .product-hero-image{
        height:360px;
        border-radius:32px;
        overflow:hidden;
        box-shadow:0 35px 80px -55px rgba(0,0,0,.5);
    }
    .product-hero-image img{
        width:100%;
        height:100%;
        object-fit:cover;
    }
</style>
@endpush
<section class="product-hero">
    <div class="container">
        <div class="row align-items-stretch g-4">
            <div class="col-lg-8">
                <div class="product-hero-card h-100">
                    <span class="badge bg-white text-primary fw-semibold mb-3 shadow-sm">Produktivitas</span>
                    <h1 class="fw-bold text-white mb-2">Peningkatan Produktivitas</h1>
                    <p>Meningkatkan produktivitas dan kesejahteraan melalui peninjauan strategi dan efisiensi di Satpel PVP Bantul.</p>
                    <a href="#konten" class="btn btn-outline-light rounded-pill px-4">Lihat Selengkapnya</a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="product-hero-image">
                    <img src="https://images.unsplash.com/photo-1506765515384-028b60a970df?auto=format&fit=crop&w=1100&q=70" alt="Produktivitas">
                </div>
            </div>
        </div>
    </div>
</section>

<section id="konten" class="py-5">
    <div class="container">
        <div class="row g-4">
            @forelse($productivities as $index => $item)
            <div class="col-12">
                <div class="row align-items-center g-3">
                    @if($index % 2 === 0)
                        <div class="col-md-7 order-1 order-md-0">
                            <h4 class="fw-bold">{{ $item->judul }}</h4>
                            <p class="text-muted" style="line-height:1.7;">{{ $item->deskripsi }}</p>
                        </div>
                        <div class="col-md-5">
                            <div class="rounded-4 overflow-hidden shadow-sm">
                                <img src="{{ $item->gambar ? asset($item->gambar) : 'https://placehold.co/520x260?text=Produktivitas' }}" class="img-fluid w-100" alt="{{ $item->judul }}">
                            </div>
                        </div>
                    @else
                        <div class="col-md-5 order-0 order-md-0">
                            <div class="rounded-4 overflow-hidden shadow-sm">
                                <img src="{{ $item->gambar ? asset($item->gambar) : 'https://placehold.co/520x260?text=Produktivitas' }}" class="img-fluid w-100" alt="{{ $item->judul }}">
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
            <div class="col-12 text-center text-muted">Belum ada data produktivitas.</div>
            @endforelse
        </div>
    </div>
</section>

@include('components.announcement-widget', [
    'announcements' => $announcementWidget ?? collect(),
    'title' => 'Pengumuman Produktivitas',
    'subtitle' => 'Ikuti arahan peningkatan produktivitas terkini.'
])
@endsection
