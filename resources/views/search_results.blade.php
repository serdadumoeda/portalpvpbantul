@extends('layouts.app')

@section('content')

<div class="bg-light py-5 mb-5 border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold text-primary mb-1">Hasil Pencarian</h2>
                <p class="text-muted mb-0 lead">
                    Menampilkan hasil untuk kata kunci: <span class="fw-bold text-dark">"{{ $keyword }}"</span>
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <span class="badge bg-secondary fs-6">
                    Total Ditemukan: {{ $beritaResults->count() + $programResults->count() + $pengumumanResults->count() }} Data
                </span>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        
        <div class="col-lg-4 order-lg-last mb-5">
            
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom fw-bold text-uppercase text-primary">
                    <i class="fas fa-graduation-cap me-2"></i> Program Pelatihan
                </div>
                <div class="list-group list-group-flush">
                    @forelse($programResults as $program)
                        <a href="{{ route('program.show', $program->id) }}" class="list-group-item list-group-item-action py-3">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1 fw-bold">{{ $program->judul }}</h6>
                            </div>
                            <small class="text-muted">{{ Str::limit($program->deskripsi, 60) }}</small>
                        </a>
                    @empty
                        <div class="list-group-item text-center text-muted py-4 small">
                            Tidak ditemukan program terkait.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom fw-bold text-uppercase text-warning">
                    <i class="fas fa-bullhorn me-2"></i> Pengumuman
                </div>
                <div class="list-group list-group-flush">
                    @forelse($pengumumanResults as $umum)
                        <div class="list-group-item py-3">
                            <h6 class="mb-1 fw-bold text-dark">{{ $umum->judul }}</h6>
                            <p class="mb-1 small text-muted">{{ Str::limit($umum->isi, 80) }}</p>
                            @if($umum->file_download)
                                <a href="{{ asset($umum->file_download) }}" target="_blank" class="badge bg-danger text-decoration-none mt-1">
                                    <i class="fas fa-file-pdf"></i> Unduh Lampiran
                                </a>
                            @endif
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted py-4 small">
                            Tidak ditemukan pengumuman terkait.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <div class="col-lg-8 order-lg-first">
            <h4 class="fw-bold mb-4 border-bottom pb-2 border-primary d-inline-block">
                <i class="fas fa-newspaper me-2"></i> Artikel & Berita
            </h4>

            @forelse($beritaResults as $news)
                <div class="card mb-4 border-0 shadow-sm hover-shadow">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="{{ $news->gambar_utama ? asset($news->gambar_utama) : 'https://placehold.co/300x200' }}" 
                                 class="img-fluid rounded-start h-100 w-100" 
                                 style="object-fit: cover; min-height: 180px;" alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">
                                    <a href="{{ route('berita.show', $news->slug) }}" class="text-dark text-decoration-none text-highlight">
                                        {{-- Optional: Fungsi highlight kata kunci (bisa ditambahkan manual) --}}
                                        {{ $news->judul }}
                                    </a>
                                </h5>
                                <p class="card-text text-muted mb-2">
                                    {{ Str::limit(strip_tags($news->konten), 130) }}
                                </p>
                                <p class="card-text">
                                    <small class="text-muted"><i class="far fa-clock me-1"></i> {{ $news->created_at->format('d M Y') }}</small>
                                </p>
                                <a href="{{ route('berita.show', $news->slug) }}" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-light text-center py-5 border border-dashed">
                    <img src="https://cdn-icons-png.flaticon.com/512/6134/6134065.png" width="80" class="mb-3 opacity-50">
                    <h5 class="text-muted">Maaf, tidak ada berita yang ditemukan.</h5>
                    <p class="text-muted small">Coba gunakan kata kunci lain yang lebih umum.</p>
                    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm mt-2">Kembali ke Beranda</a>
                </div>
            @endforelse

        </div>

    </div>
</div>

<style>
    /* Styling tambahan untuk hasil pencarian */
    .hover-shadow:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; transition: 0.3s; }
    .border-dashed { border-style: dashed !important; }
</style>
@endsection