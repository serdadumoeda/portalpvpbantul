@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .news-hero {
        background: linear-gradient(135deg, rgba(7,60,108,.92), rgba(7,108,103,.85));
        border-bottom-left-radius: 48px;
        border-bottom-right-radius: 48px;
        padding:4rem 0;
        color:#fff;
    }
    .news-hero-card{
        border-radius:32px;
        background:rgba(255,255,255,.08);
        border:1px solid rgba(255,255,255,.2);
        padding:3rem;
        min-height:360px;
        box-shadow:0 40px 110px -60px rgba(0,0,0,.65);
        backdrop-filter:blur(4px);
    }
    .news-hero-card p{color:rgba(255,255,255,.75);}
    .news-hero-image{
        height:360px;
        border-radius:32px;
        overflow:hidden;
        box-shadow:0 35px 80px -55px rgba(0,0,0,.5);
    }
    .news-hero-image img{
        width:100%;
        height:100%;
        object-fit:cover;
    }
    .news-card {
        border-radius: 18px;
        border: 1px solid #e6ecf1;
        transition: transform .2s ease, box-shadow .2s ease;
        min-height: 180px;
    }
    .news-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 35px -18px rgba(15,40,70,.4);
    }
    .news-card img {
        width: 160px;
        height: 110px;
        object-fit: cover;
        border-radius: 14px;
    }
    .section-wrapper {
        border-radius: 32px;
        padding: 48px;
        background: #fff;
        box-shadow: 0 28px 60px -40px rgba(14,61,77,.35);
    }
    .section-wrapper + .section-wrapper {
        margin-top: 40px;
    }
    .custom-pagination .page-link {
        border-radius: 50px !important;
        border: none;
        color: #0f6b7a;
        font-weight: 600;
    }
    .custom-pagination .active>.page-link {
        background: #0f6b7a;
        color: #fff;
    }
    .custom-pagination .page-item.disabled .page-link {
        background: #f1f5f9;
        color: #8ea0b1;
    }
    .category-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #0e3950;
    }
    @media(min-width:992px){
        .news-hero .rounded-4 {
            min-height: 340px;
        }
    }
</style>
@endpush
@php
    $descriptions = [
        'berita' => 'Berita seputar kegiatan pelatihan, program, dan kolaborasi terbaru Satpel PVP Bantul.',
        'pers_release' => 'Informasi resmi dan rilis pers mengenai kebijakan atau layanan Satpel PVP Bantul.',
        'informasi_pelatihan' => 'Kabar mengenai jadwal, pendaftaran, dan highlight pelatihan yang sedang berjalan.',
        'just_relax' => 'Artikel ringan dan inspiratif terkait pengembangan karier dan produktivitas.',
    ];
    $imageUrl = function ($path) {
        if (!$path) {
            return null;
        }
        return \Illuminate\Support\Str::startsWith($path, ['http', 'https']) ? $path : asset($path);
    };
@endphp

<section class="news-hero">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6">
                <div class="news-hero-card h-100">
                    <span class="badge bg-white text-primary fw-semibold mb-3 shadow-sm">Update Satpel PVP Bantul</span>
                    <h1 class="fw-bold text-white mb-1">{{ $hero?->judul ?? 'Berita Terkini' }}</h1>
                    <p class="mb-1 text-white-50">{{ optional($hero?->published_at ?? $hero?->created_at)->translatedFormat('d F Y') }}</p>
                    <p class="mb-4">{{ $hero?->excerpt ?? 'Sorotan berita terkini seputar pelatihan, inovasi layanan, hingga pengakuan kompetensi.' }}</p>
                    <a href="{{ $hero ? route('berita.show', $hero->slug) : '#section-berita' }}" class="btn btn-outline-light rounded-pill px-4">Lihat Selengkapnya</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="news-hero-image">
                    <img src="{{ $imageUrl($hero?->gambar_utama) ?? 'https://placehold.co/960x540?text=Berita+Terkini' }}" alt="Hero Berita">
                </div>
            </div>
        </div>
    </div>
</section>

@foreach($categories as $key => $label)
    @php $collection = $newsCollections[$key] ?? collect(); @endphp
    <section id="section-{{ $key }}" class="py-5" style="{{ $loop->odd ? 'background:#f6fbff;' : '' }}">
        <div class="container">
            <div class="section-wrapper">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
                    <div>
                        <div class="text-uppercase text-muted small fw-semibold">{{ $label }}</div>
                        <h3 class="category-title mb-2">{{ $label }}</h3>
                        <p class="text-muted mb-0">{{ $descriptions[$key] ?? '' }}</p>
                    </div>
                </div>
                <div class="row g-4">
                    @forelse($collection as $item)
                        <div class="col-md-6">
                            <div class="d-flex gap-3 p-3 news-card bg-white h-100">
                                <div class="flex-shrink-0">
                                    <img src="{{ $imageUrl($item->gambar_utama) ?? 'https://placehold.co/160x120?text=Berita' }}" alt="{{ $item->judul }}">
                                </div>
                                <div class="flex-grow-1 d-flex flex-column">
                                    <h5 class="fw-semibold mb-1">
                                        <a href="{{ route('berita.show', $item->slug) }}" class="text-decoration-none text-dark">{{ $item->judul }}</a>
                                    </h5>
                                    <div class="text-muted small mb-2">
                                        {{ optional($item->published_at ?? $item->created_at)->format('d M Y') }} &middot; {{ $item->author ?? 'Tim Satpel PVP Bantul' }}
                                    </div>
                                    <p class="text-muted small flex-grow-1">{{ $item->excerpt }}</p>
                                    <a href="{{ route('berita.show', $item->slug) }}" class="text-primary fw-semibold small">Baca Selengkapnya &rarr;</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted">Belum ada berita pada kategori ini.</div>
                    @endforelse
                </div>
                @if($collection instanceof \Illuminate\Pagination\LengthAwarePaginator && $collection->count())
                    <div class="mt-4 custom-pagination">
                        {{ $collection->withQueryString()->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </section>
@endforeach

<section class="py-5" style="background:#e0f6f3;">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <h3 class="fw-bold text-primary mb-2">Anda Siap Tingkatkan Skill dengan Kami?</h3>
                <p class="text-muted mb-0">Eksplorasi berbagai program pelatihan unggulan dan layanan sertifikasi yang siap membantu karier Anda.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('pelatihan.katalog') }}" class="btn btn-primary rounded-pill px-4 me-2">Cek Katalog Pelatihan</a>
                <a href="{{ route('sertifikasi') }}" class="btn btn-outline-primary rounded-pill px-4">Info Sertifikasi</a>
            </div>
        </div>
    </div>
</section>
@endsection
