@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .instructor-hero{
        background:linear-gradient(135deg, rgba(7,60,108,.92), rgba(7,108,103,.85));
        border-bottom-left-radius:48px;
        border-bottom-right-radius:48px;
        padding:4rem 0;
        color:#fff;
    }
    .instructor-hero-card{
        border-radius:32px;
        background:rgba(255,255,255,.08);
        border:1px solid rgba(255,255,255,.2);
        padding:3rem;
        min-height:280px;
        box-shadow:0 40px 110px -60px rgba(0,0,0,.6);
    }
</style>
@endpush
<section class="instructor-hero">
    <div class="container text-center">
        <div class="instructor-hero-card mx-auto">
            <span class="badge bg-white text-success fw-semibold mb-3 shadow-sm">Tentang Kami</span>
            <h1 class="fw-bold text-white">Profil Instruktur Satpel PVP Bantul</h1>
            <p class="mb-0">Kenali para pengajar profesional yang siap mendampingi Anda mencapai kompetensi terbaik.</p>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4">
            @forelse($instructors as $instructor)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="{{ $instructor->foto ? asset($instructor->foto) : 'https://placehold.co/480x320?text=Instruktur' }}" class="card-img-top" alt="{{ $instructor->nama }}">
                        <div class="card-body">
                            <h5 class="fw-bold mb-1">{{ $instructor->nama }}</h5>
                            <p class="text-muted mb-2">{{ $instructor->bidang ?? 'Instruktur Kejuruan' }}</p>
                            <p class="small text-muted">{{ $instructor->deskripsi ?? 'Instruktur berpengalaman dalam mendampingi program pelatihan unggulan Satpel PVP Bantul.' }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted">Belum ada data instruktur aktif.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-5" style="background:#f0fbf8;">
    <div class="container text-center">
        <h3 class="fw-bold text-primary mb-3">Siap Belajar Bersama Instruktur Kami?</h3>
        <p class="text-muted mb-4">Jelajahi katalog pelatihan untuk menemukan kelas yang sesuai dengan minat dan kebutuhan karier Anda.</p>
        <a href="{{ route('pelatihan.katalog') }}" class="btn btn-primary rounded-pill px-4 me-2">Katalog Pelatihan</a>
        <a href="{{ route('sertifikasi') }}" class="btn btn-outline-primary rounded-pill px-4">Sertifikasi</a>
    </div>
</section>
@endsection
