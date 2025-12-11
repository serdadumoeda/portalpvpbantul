@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .catalog-hero{
        background:linear-gradient(135deg, rgba(7,60,108,.92), rgba(7,108,103,.85));
        border-bottom-left-radius:48px;
        border-bottom-right-radius:48px;
        padding:4rem 0;
        color:#fff;
    }
    .catalog-hero-card{
        border-radius:32px;
        background:rgba(255,255,255,.08);
        border:1px solid rgba(255,255,255,.2);
        padding:3rem;
        min-height:360px;
        box-shadow:0 40px 110px -60px rgba(0,0,0,.6);
    }
    .catalog-hero-card p{color:rgba(255,255,255,.75);}
    .catalog-hero-image{
        border-radius:32px;
        overflow:hidden;
        height:360px;
        box-shadow:0 35px 80px -55px rgba(0,0,0,.5);
    }
    .catalog-hero-image img{
        width:100%;
        height:100%;
        object-fit:cover;
    }
</style>
@endpush
<section class="catalog-hero">
    <div class="container">
        <div class="row align-items-stretch g-4">
            <div class="col-lg-7">
                <div class="catalog-hero-card h-100">
                    <span class="badge bg-white text-primary fw-semibold mb-3 shadow-sm">Program Pelatihan</span>
                    <h1 class="fw-bold text-white mb-3">Membangun Keahlian Unggulan Melalui Program Pelatihan Terkini</h1>
                    <p>Pilih program pelatihan, kembangkan potensi diri, dan raih peluang kerja atau wirausaha.</p>
                    <a href="{{ route('pelatihan.katalog') }}" class="btn btn-outline-light rounded-pill px-4">Lihat Pelatihan Lainnya</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="catalog-hero-image">
                    <img src="https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=1200&q=70" alt="Hero Pelatihan">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h3 class="fw-bold mb-1">Jelajahi Pelatihan</h3>
                <p class="text-muted mb-0">Jelajahi pelatihan terbaru yang sesuai dengan tujuan dan minat Anda.</p>
            </div>
            <div class="nav nav-pills gap-2">
                <button class="btn btn-outline-primary btn-sm rounded-pill active">Pelatihan</button>
                <button class="btn btn-outline-primary btn-sm rounded-pill">Pemagangan</button>
                <button class="btn btn-outline-primary btn-sm rounded-pill">Pelatihan</button>
                <button class="btn btn-outline-primary btn-sm rounded-pill">Vokasional</button>
                <button class="btn btn-outline-primary btn-sm rounded-pill">Teknologi Pengolahan</button>
                <button class="btn btn-outline-primary btn-sm rounded-pill">Pertanian</button>
            </div>
        </div>
        <div class="row g-3">
            @forelse($programs as $program)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="{{ $program->gambar ? asset($program->gambar) : 'https://placehold.co/400x240?text=Program' }}" class="card-img-top" style="height:200px; object-fit:cover;" alt="{{ $program->judul }}">
                    <div class="card-body">
                        <h6 class="fw-bold">{{ $program->judul }}</h6>
                        <p class="text-muted small">{{ Str::limit($program->deskripsi ?? '', 80) }}</p>
                        <a href="{{ route('program.show', $program->id) }}" class="btn btn-sm btn-primary rounded-pill">Ikut Pelatihan</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted">Belum ada data program.</div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $programs->links() }}
        </div>
    </div>
</section>

@include('components.announcement-widget', [
    'announcements' => $announcementWidget ?? collect(),
    'title' => 'Pengumuman Terkait Pelatihan',
    'subtitle' => 'Pastikan mengikuti setiap jadwal dan ketentuan terbaru.'
])
@endsection
