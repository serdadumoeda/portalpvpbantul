@extends('layouts.app')

@section('content')
<section class="py-5" style="background: linear-gradient(135deg, #f7fbff 0%, #f1f7ff 50%, #e6f4f1 100%);">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6 position-relative">
                <img src="https://placehold.co/300x220?text=PVP+Brand" class="mb-3" alt="Brand">
                <h1 class="fw-bold lh-sm text-primary" style="color:#1b364d !important;">Tingkatkan Potensi Dirimu Dapatkan Kesempatan untuk Sukses<br>Ayo Ikut Pelatihan Sekarang!</h1>
                <p class="text-secondary mt-3">Mari berangkat, ayo kita selesaikan pelatihan bersama Satpel PVP Bantul. Dengan program yang selangkah lebih maju.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="https://siapkerja.kemnaker.go.id/app/pelatihan" target="_blank" class="btn btn-primary rounded-pill px-4">Daftar Pelatihan</a>
                    <a href="{{ route('program') }}" class="btn btn-outline-primary rounded-pill px-4">Baca Selengkapnya</a>
                </div>
                <img src="https://placehold.co/240x160?text=Shapes" class="hero-shape d-none d-lg-block" alt="">
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://placehold.co/620x420?text=Hero+Image" class="img-fluid rounded-4 shadow-soft" alt="Hero">
            </div>
        </div>
    </div>
</section>

@if($latestAnnouncements->isNotEmpty())
<section class="bg-white border-top border-bottom py-3">
    <div class="container d-flex flex-wrap align-items-center gap-3">
        <div class="fw-bold text-primary d-flex align-items-center">
            <i class="fas fa-bullhorn me-2"></i> Pengumuman
        </div>
        <div class="flex-grow-1">
            <marquee class="text-muted small">
                @foreach($latestAnnouncements as $item)
                    <span class="me-4">{{ $item->created_at?->translatedFormat('d M Y') }} — {{ Str::limit($item->judul, 80) }}</span>
                @endforeach
            </marquee>
        </div>
        <a href="{{ route('pengumuman.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">Lihat Semua</a>
    </div>
</section>

@endif

<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Layanan Pelatihan di Satpel PVP Bantul</h3>
            <p class="text-muted">Pilihan skema pelatihan yang dilaksanakan dengan fasilitas dan layanan berbeda.</p>
        </div>
        <div class="row g-3">
            @forelse($trainingServices as $service)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    @if($service->gambar)
                        <img src="{{ asset($service->gambar) }}" class="card-img-top" alt="{{ $service->judul }}" style="object-fit:cover; height:200px;">
                    @else
                        <img src="https://placehold.co/520x200?text={{ urlencode($service->judul) }}" class="card-img-top" alt="{{ $service->judul }}" style="object-fit:cover; height:200px;">
                    @endif
                    <div class="card-body">
                        <h5 class="fw-bold">{{ $service->judul }}</h5>
                        <p class="text-muted small">{{ $service->deskripsi }}</p>
                        <div class="small text-muted">{!! $service->fasilitas !!}</div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted">Belum ada data layanan pelatihan.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-5" style="background: linear-gradient(90deg, #0f7b7b 0%, #0aa2a2 100%); color: white;">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <h3 class="fw-bold mb-3">Kenapa Harus Ikut Pelatihan dari PVP Bandung Barat?</h3>
                <div class="row g-3">
                    @forelse($benefits as $benefit)
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-2">
                            <img src="{{ $benefit->ikon ? asset($benefit->ikon) : 'https://placehold.co/40x40?text=Ikon' }}" class="rounded-circle" alt="" style="width:40px; height:40px; object-fit:cover;">
                            <div>
                                <h6 class="mb-1 fw-bold">{{ $benefit->judul }}</h6>
                                <p class="small mb-0">{{ $benefit->deskripsi }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-white">Belum ada data benefit.</div>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <img src="https://placehold.co/380x420?text=Instruktur" class="img-fluid rounded-4 shadow-soft" alt="">
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Jelajahi Pelatihan</h3>
            <p class="text-muted">Mulai perjalanan kariermu dengan pelatihan yang sesuai kebutuhan.</p>
        </div>
        <div class="row g-3">
            @foreach($programs as $program)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ $program->gambar ? asset($program->gambar) : 'https://placehold.co/400x240?text=Program' }}" class="card-img-top" style="height:180px; object-fit:cover;" alt="{{ $program->judul }}">
                    <div class="card-body">
                        <h6 class="fw-bold">{{ $program->judul }}</h6>
                        <p class="text-muted small">{{ Str::limit($program->deskripsi ?? '', 80) }}</p>
                        <a href="{{ route('program.show', $program->id) }}" class="btn btn-sm btn-primary rounded-pill">Lihat Detail</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-3">
            <a href="{{ route('program') }}" class="btn btn-outline-primary rounded-pill px-4">Lihat semua</a>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-5">
                <img src="https://placehold.co/420x440?text=Instruktur" class="img-fluid rounded-4 shadow-soft" alt="Instruktur">
            </div>
            <div class="col-lg-7">
                <h3 class="fw-bold">Kenapa Harus Ikut Pelatihan di Satpel PVP Bantul?</h3>
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-2">
                            <i class="fas fa-check-circle text-success mt-1"></i>
                            <div>
                                <h6 class="mb-1 fw-bold">Instruktur tersertifikasi</h6>
                                <p class="small text-muted mb-0">Berpengalaman di industri dan vokasi.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-2">
                            <i class="fas fa-check-circle text-success mt-1"></i>
                            <div>
                                <h6 class="mb-1 fw-bold">Fasilitas lengkap</h6>
                                <p class="small text-muted mb-0">Workshop dan laboratorium standar industri.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-2">
                            <i class="fas fa-check-circle text-success mt-1"></i>
                            <div>
                                <h6 class="mb-1 fw-bold">Gratis & bersertifikat</h6>
                                <p class="small text-muted mb-0">Mendapatkan sertifikat kompetensi.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-2">
                            <i class="fas fa-check-circle text-success mt-1"></i>
                            <div>
                                <h6 class="mb-1 fw-bold">Link ke industri</h6>
                                <p class="small text-muted mb-0">Kemitraan penempatan kerja.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-5 text-center">
                <img src="https://placehold.co/380x420?text=CTA" class="img-fluid rounded-4 shadow-soft" alt="CTA">
            </div>
            <div class="col-lg-7">
                <h3 class="fw-bold mb-3">Alur Pelatihan di PVP Bandung Barat</h3>
                <ol class="list-group list-group-numbered">
                    @forelse($flowSteps as $step)
                    <li class="list-group-item d-flex align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">{{ $step->judul }}</div>
                            {{ $step->deskripsi }}
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item">Belum ada data alur.</li>
                    @endforelse
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Berita Terkini</h3>
            <p class="text-muted">Informasi terbaru seputar kegiatan Satpel PVP Bantul.</p>
        </div>
        <div class="row g-3">
            @forelse($beritaTerbaru as $news)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ $news->gambar_utama ? asset($news->gambar_utama) : 'https://placehold.co/520x260?text=Berita' }}" class="card-img-top" style="height:200px; object-fit:cover;" alt="{{ $news->judul }}">
                    <div class="card-body">
                        <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> {{ $news->created_at->translatedFormat('d F Y') }}</small>
                        <h6 class="fw-bold mt-2">
                            <a href="{{ route('berita.show', $news->slug) }}" class="text-decoration-none text-dark">{{ Str::limit($news->judul, 70) }}</a>
                        </h6>
                        <p class="text-muted small mb-3">{{ Str::limit(strip_tags($news->konten), 90) }}</p>
                        <a href="{{ route('berita.show', $news->slug) }}" class="btn btn-sm btn-outline-primary">Baca</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted">Belum ada berita terbaru.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-5" style="background: linear-gradient(120deg, #0f7b7b 0%, #19a8a8 100%); color:white;">
    <div class="container text-center">
        <h3 class="fw-bold mb-2">{{ $settings['cta_title'] ?? 'Tunggu Apalagi? Yuk Langsung Daftar Kelasnya' }}</h3>
        <p class="mb-4">{{ $settings['cta_subtitle'] ?? 'Ikuti program pelatihan terbaik untuk meningkatkan kompetensi dan siap kerja.' }}</p>
        <div class="d-flex justify-content-center gap-2 flex-wrap">
            <a href="{{ $settings['cta_button_1_link'] ?? 'https://siapkerja.kemnaker.go.id/app/pelatihan' }}" class="btn btn-light text-primary rounded-pill px-4">{{ $settings['cta_button_1_text'] ?? 'Daftar Pelatihan' }}</a>
            <a href="{{ $settings['cta_button_2_link'] ?? route('kontak') }}" class="btn btn-outline-light rounded-pill px-4">{{ $settings['cta_button_2_text'] ?? 'Hubungi Kami' }}</a>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Kata Mereka Setelah Selesai Pelatihan</h3>
            <p class="text-muted">Testimoni peserta tentang pengalaman belajar di Satpel PVP Bantul.</p>
        </div>
        <div class="row g-3">
            @forelse($testimonials as $testi)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-1">{{ $testi->nama }}</h6>
                        <div class="text-muted small mb-2">{{ $testi->jabatan }}</div>
                        <p class="text-secondary small">{{ $testi->pesan }}</p>
                        @if($testi->video_url)
                            <div class="ratio ratio-16x9 mt-2">
                                <iframe src="{{ preg_replace('/watch\\?v=/', 'embed/', $testi->video_url) }}" title="testimoni" allowfullscreen></iframe>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted">Belum ada testimoni.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Partner Kami</h3>
            <p class="text-muted">Kolaborasi dengan para pelaku industri yang membantu mewujudkan sukses bersama.</p>
        </div>
        @if($partners->isEmpty())
            <div class="alert alert-info text-center">Belum ada data partner.</div>
        @else
            <div id="partnerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2500">
                <div class="carousel-inner">
                    @foreach($partners->chunk(6) as $chunkIndex => $chunk)
                    <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                        <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap">
                            @foreach($chunk as $partner)
                                <div class="p-3 bg-white rounded shadow-sm">
                                    @if($partner->logo)
                                        @if($partner->tautan)
                                            <a href="{{ $partner->tautan }}" target="_blank" rel="noopener">
                                                <img src="{{ asset($partner->logo) }}" alt="{{ $partner->nama }}" style="height:60px; object-fit:contain;">
                                            </a>
                                        @else
                                            <img src="{{ asset($partner->logo) }}" alt="{{ $partner->nama }}" style="height:60px; object-fit:contain;">
                                        @endif
                                    @else
                                        <span class="fw-bold">{{ $partner->nama }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#partnerCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#partnerCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
        @endif
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Instruktur Ahli dalam Bidangnya</h3>
            <p class="text-muted">Berpengalaman sebagai praktisi industri dan instruktur Satpel PVP Bantul.</p>
        </div>
        @if($instructors->isEmpty())
            <div class="alert alert-info text-center">Belum ada data instruktur.</div>
        @else
            <div id="instructorCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                <div class="carousel-inner">
                    @foreach($instructors->chunk(4) as $chunkIndex => $chunk)
                    <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                        <div class="row g-3 justify-content-center">
                            @foreach($chunk as $instruktur)
                            <div class="col-md-3 col-6">
                                <div class="card h-100 border-0 shadow-sm text-center p-3">
                                    <div class="d-flex justify-content-center mb-3">
                                        <div class="rounded-circle overflow-hidden" style="width:80px; height:80px;">
                                            <img src="{{ $instruktur->foto ? asset($instruktur->foto) : 'https://placehold.co/160x160' }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $instruktur->nama }}">
                                        </div>
                                    </div>
                                    <h6 class="fw-bold mb-1">{{ $instruktur->nama }}</h6>
                                    <div class="text-muted small mb-2">{{ $instruktur->keahlian }}</div>
                                    <p class="small text-secondary">{{ Str::limit($instruktur->deskripsi, 80) }}</p>
                                    <div class="d-flex justify-content-center gap-2">
                                        @if($instruktur->linkedin)
                                            <a href="{{ $instruktur->linkedin }}" target="_blank" class="text-primary"><i class="fab fa-linkedin fa-lg"></i></a>
                                        @endif
                                        @if($instruktur->whatsapp)
                                            <a href="https://wa.me/{{ preg_replace('/\\D+/', '', $instruktur->whatsapp) }}" target="_blank" class="text-success"><i class="fab fa-whatsapp fa-lg"></i></a>
                                        @endif
                                        @if($instruktur->email)
                                            <a href="mailto:{{ $instruktur->email }}" class="text-secondary"><i class="fas fa-envelope fa-lg"></i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#instructorCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#instructorCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
        @endif
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Galeri Kegiatan</h3>
            <p class="text-muted">Dokumentasi aktivitas pelatihan di Satpel PVP Bantul.</p>
        </div>
        <div class="row g-2">
            @forelse($galeris as $foto)
            <div class="col-lg-3 col-md-4 col-6">
                <a href="#" class="d-block overflow-hidden rounded position-relative group-hover">
                    <img src="{{ asset($foto->gambar) }}" class="img-fluid w-100 hover-zoom" alt="{{ $foto->judul }}" style="height: 200px; object-fit: cover;">
                    <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-75 text-white p-2 small text-truncate">
                        {{ $foto->judul }}
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center text-muted">Belum ada foto di galeri.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
