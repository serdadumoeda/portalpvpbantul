@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Str;

    $imageUrl = $vacancy->gambar
        ? (Str::startsWith($vacancy->gambar, ['http://', 'https://']) ? $vacancy->gambar : asset($vacancy->gambar))
        : 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=1200&q=70';

    $qualificationItems = collect(preg_split('/\r\n|\r|\n/', (string) $vacancy->kualifikasi))
        ->map(fn ($item) => trim($item, "-• \t\r\n"))
        ->filter();
@endphp

<section class="py-5" style="background:linear-gradient(135deg,#0f4c75,#3282b8);">
    <div class="container">
        <div class="row align-items-center g-4 text-white">
            <div class="col-lg-6">
                <span class="badge bg-white text-primary fw-semibold mb-3">{{ $vacancy->tipe_pekerjaan ?? 'Full Time' }}</span>
                <h1 class="fw-bold">{{ $vacancy->judul }}</h1>
                <p class="mb-2">{{ $vacancy->perusahaan ?? 'Mitra Industri' }}</p>
                <p class="mb-4"><i class="fas fa-map-marker-alt me-2"></i>{{ $vacancy->lokasi ?? 'Lokasi fleksibel' }}</p>
                @if($vacancy->link_pendaftaran)
                    <a href="{{ $vacancy->link_pendaftaran }}" target="_blank" rel="noopener" class="btn btn-light text-primary rounded-pill px-4">Lamar Sekarang</a>
                @endif
            </div>
            <div class="col-lg-6">
                <div class="rounded-4 overflow-hidden shadow-lg">
                    <img src="{{ $imageUrl }}" alt="{{ $vacancy->judul }}" class="img-fluid w-100" style="max-height:320px;object-fit:cover;">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Deskripsi</h5>
                        <div class="text-muted" style="line-height:1.7;">{!! nl2br(e($vacancy->deskripsi)) !!}</div>
                    </div>
                </div>

                @if($qualificationItems->isNotEmpty())
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Kualifikasi & Benefit</h5>
                            <ul class="mb-0">
                                @foreach($qualificationItems as $item)
                                    <li class="mb-2">{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Ringkasan</h6>
                        <p class="mb-2"><strong>Perusahaan</strong><br><span class="text-muted">{{ $vacancy->perusahaan ?? '-' }}</span></p>
                        <p class="mb-2"><strong>Lokasi</strong><br><span class="text-muted">{{ $vacancy->lokasi ?? '-' }}</span></p>
                        <p class="mb-2"><strong>Tipe</strong><br><span class="text-muted">{{ $vacancy->tipe_pekerjaan ?? 'Full Time' }}</span></p>
                        <p class="mb-2"><strong>Deadline</strong><br><span class="text-muted">{{ $vacancy->deadline ? $vacancy->deadline->format('d M Y') : 'Tidak ditentukan' }}</span></p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Aksi</h6>
                        @if($vacancy->link_pendaftaran)
                            <a href="{{ $vacancy->link_pendaftaran }}" target="_blank" rel="noopener" class="btn btn-primary w-100 mb-2">Lamar Sekarang</a>
                        @endif
                        <a href="{{ route('berita.lowongan') }}" class="btn btn-outline-secondary w-100">Kembali ke Daftar</a>
                    </div>
                </div>

                @if($relatedVacancies->isNotEmpty())
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Lowongan Lain</h6>
                            @foreach($relatedVacancies as $related)
                                <div class="mb-3">
                                    <a href="{{ route('berita.lowongan.detail', $related->id) }}" class="fw-semibold text-decoration-none">{{ $related->judul }}</a>
                                    <p class="text-muted small mb-0">{{ $related->perusahaan }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
