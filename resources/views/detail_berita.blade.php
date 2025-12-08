@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Berita</li>
                </ol>
            </nav>

            <h1 class="fw-bold mb-3">{{ $berita->judul }}</h1>
            <div class="text-muted mb-4">
                <i class="far fa-calendar-alt me-2"></i> {{ $berita->created_at->format('d M Y') }}
                <span class="mx-2">|</span>
                <i class="far fa-user me-2"></i> Admin PVP
            </div>

            <img src="{{ $berita->gambar_utama }}" class="img-fluid rounded mb-4 w-100" alt="{{ $berita->judul }}">

            <div class="content-body" style="line-height: 1.8; font-size: 1.1rem; text-align: justify;">
                {!! nl2br(e($berita->konten)) !!}
            </div>
            
            <hr class="my-5">
            <a href="{{ route('home') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>

        <div class="col-lg-4 mt-5 mt-lg-0">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold py-3 border-bottom-0">
                    <i class="fas fa-newspaper text-primary me-2"></i> Berita Lainnya
                </div>
                <div class="list-group list-group-flush">
                    @foreach($beritaLain as $item)
                        <a href="{{ route('berita.show', $item->slug) }}" class="list-group-item list-group-item-action py-3">
                            <div class="d-flex align-items-center">
                                <img src="{{ $item->gambar_utama }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-1" style="font-size: 0.9rem; font-weight: 600;">{{ Str::limit($item->judul, 40) }}</h6>
                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $item->created_at->format('d M Y') }}</small>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Pendaftaran Pelatihan</h5>
                    <p class="card-text text-muted small">Ingin mengikuti pelatihan di Satpel PVP Bantul? Cek jadwal dan daftarkan diri Anda segera melalui akun SIAPkerja.</p>
                    <a href="https://siapkerja.kemnaker.go.id" target="_blank" class="btn btn-primary w-100">Daftar Sekarang</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
