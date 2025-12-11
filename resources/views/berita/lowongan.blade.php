@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .vacancy-hero {
        background: linear-gradient(135deg, rgba(7,60,108,.92), rgba(7,108,103,.85));
        border-bottom-left-radius: 48px;
        border-bottom-right-radius: 48px;
        padding: 4rem 0;
        color: #fff;
    }
    .vacancy-hero-card{
        border-radius:32px;
        background:rgba(255,255,255,.08);
        border:1px solid rgba(255,255,255,.2);
        padding:3rem;
        min-height:360px;
        box-shadow:0 40px 110px -60px rgba(0,0,0,.65);
        backdrop-filter:blur(4px);
    }
    .vacancy-hero-card p{color:rgba(255,255,255,.75);}
    .vacancy-hero-image{
        height:360px;
        border-radius:32px;
        overflow:hidden;
        box-shadow:0 35px 80px -55px rgba(0,0,0,.5);
    }
    .vacancy-hero-image img{
        width:100%;
        height:100%;
        object-fit:cover;
    }
</style>
@endpush
@php
    $imageUrl = function ($path) {
        if (!$path) {
            return null;
        }
        return \Illuminate\Support\Str::startsWith($path, ['http', 'https']) ? $path : asset($path);
    };
@endphp
<section class="vacancy-hero">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6">
                <div class="vacancy-hero-card h-100">
                    <span class="badge bg-white text-success fw-semibold mb-3 shadow-sm">Portal Karier</span>
                    <h1 class="fw-bold text-white">Lowongan Kerja Terbaru</h1>
                    <p class="mb-4">Temukan peluang kerja terkini dari mitra industri dan perusahaan rekanan Satpel PVP Bantul.</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="vacancy-hero-image">
                    <img src="https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=1200&q=70" alt="Lowongan">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        @if($vacancies instanceof \Illuminate\Pagination\LengthAwarePaginator && $vacancies->count())
        <div class="row g-4">
            @foreach($vacancies as $vacancy)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        @if($vacancy->gambar)
                            <img src="{{ $imageUrl($vacancy->gambar) }}" class="card-img-top" alt="{{ $vacancy->judul }}" style="height:180px;object-fit:cover;">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-success bg-opacity-10 text-success mb-2">{{ $vacancy->tipe_pekerjaan ?? 'Full Time' }}</span>
                            <h5 class="fw-bold">{{ $vacancy->judul }}</h5>
                            <p class="text-muted small mb-1">{{ $vacancy->perusahaan ?? 'Mitra Industri' }}</p>
                            <p class="text-muted small"><i class="fas fa-map-marker-alt me-1"></i>{{ $vacancy->lokasi ?? 'Lokasi fleksibel' }}</p>
                            <p class="text-muted flex-grow-1 small">{{ \Illuminate\Support\Str::limit(strip_tags($vacancy->deskripsi), 120) }}</p>
                            @php
                                $qualifications = collect(preg_split('/\r\n|\r|\n/', (string) $vacancy->kualifikasi))
                                    ->map(fn ($item) => trim($item, "-• \t\r\n"))
                                    ->filter()
                                    ->values();
                            @endphp
                            @if($qualifications->isNotEmpty())
                                <ul class="list-unstyled small text-muted mb-0">
                                    @foreach($qualifications->take(3) as $point)
                                        <li class="mb-1"><i class="fas fa-check text-success me-1"></i>{{ $point }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">Deadline: {{ $vacancy->deadline ? $vacancy->deadline->format('d M Y') : '-' }}</small>
                                <a href="{{ route('berita.lowongan.detail', $vacancy->id) }}" class="btn btn-outline-primary btn-sm rounded-pill">Detail Lowongan</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $vacancies->links() }}
        </div>
        @else
        <div class="text-center text-muted">Belum ada lowongan aktif saat ini.</div>
        @endif
    </div>
</section>

<section class="py-5" style="background:#e3f6f1;">
    <div class="container text-center">
        <h3 class="fw-bold text-primary mb-3">Tingkatkan Kompetensi Sebelum Melamar</h3>
        <p class="text-muted mb-4">Ikuti pelatihan vokasi unggulan Satpel PVP Bantul dan raih sertifikasi resmi untuk meningkatkan daya saing Anda.</p>
        <a href="{{ route('pelatihan.katalog') }}" class="btn btn-primary rounded-pill px-4 me-2">Lihat Pelatihan</a>
        <a href="{{ route('sertifikasi') }}" class="btn btn-outline-primary rounded-pill px-4">Info Sertifikasi</a>
    </div>
</section>
@endsection
