<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Satpel PVP Bantul - Kemnaker RI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        html, body { height: 100%; }
        body { font-family: 'Inter', 'Segoe UI', Tahoma, sans-serif; background-color: #f5f7fb; color: #1f2d3d; display: flex; flex-direction: column; }
        main { flex: 1 0 auto; }
        .navbar-top { background-color: #0b2b4c; color: white; font-size: 0.85rem; }
        .main-navbar { background: #fff !important; box-shadow: 0 6px 18px -8px rgba(0,0,0,0.2); position: sticky; top: 0; z-index: 1030; }
        .nav-link { font-weight: 600; color: #1f2d3d; }
        .nav-link:hover, .nav-link.active { color: #0f7b7b !important; }
        .btn-primary { background: #1b877a; border-color: #1b877a; }
        .btn-outline-primary { color: #1b877a; border-color: #1b877a; }
        .btn-outline-primary:hover { background: #1b877a; color: #fff; }
        .badge-primary-soft { background: #e7f5f2; color: #1b877a; }
        .rounded-4 { border-radius: 1rem; }
        .shadow-soft { box-shadow: 0 12px 30px -12px rgba(0,0,0,0.18); }
        .hero-shape { position:absolute; top:-60px; right:-80px; width:280px; opacity:0.08; }
        .marquee-box { background:#f2fbf9; border:1px solid #d9f0ec; }
        .section-title { font-weight:700; }
        .card { border-radius: 14px; }
    </style>
    @stack('styles')

</head>
<body>

    <nav class="navbar navbar-expand-lg main-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('image/logo/logo_kemnaker.svg') }}" alt="Logo Kemnaker" height="42" class="me-2">
                <div class="fw-bold text-uppercase lh-sm" style="letter-spacing:0.5px;">
                    <span class="d-block text-secondary small">Kemnaker RI</span>
                    <span class="text-primary">Satpel PVP Bantul</span>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Beranda</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Tentang Kami</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('profil.instansi') }}">Profil Instansi</a></li>
                            <li><a class="dropdown-item" href="{{ route('profil.instruktur') }}">Profil Instruktur</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Pelatihan</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('pelatihan.katalog') }}">Katalog Pelatihan</a></li>
                            <li><a class="dropdown-item" href="{{ route('pelatihan.jadwal') }}">Jadwal Pelatihan</a></li>
                            <li><a class="dropdown-item" href="{{ route('pelatihan.pemberdayaan') }}">Pemberdayaan</a></li>
                            <li><a class="dropdown-item" href="{{ route('pelatihan.produktivitas') }}">Peningkatan Produktivitas</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('sertifikasi') }}">Sertifikasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('program') }}">Program</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Berita</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('berita.terkini') }}">Berita Terkini</a></li>
                            <li><a class="dropdown-item" href="{{ route('berita.lowongan') }}">Lowongan Kerja</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Resource</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('resource.infografis') }}">Infografis Alumni</a></li>
                            <li><a class="dropdown-item" href="{{ route('resource.publikasi') }}">Publikasi</a></li>
                            <li><a class="dropdown-item" href="{{ route('resource.pelayanan') }}">Pelayanan Publik</a></li>
                            <li><a class="dropdown-item" href="{{ route('resource.faq') }}">Frequently Asked Questions (FAQ)</a></li>
                            <li><a class="dropdown-item" href="{{ route('resource.hubungi') }}">Hubungi Kami</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('ppid') }}">PPID</a></li>
                </ul>

                <div class="d-flex align-items-center ms-lg-3 my-2 my-lg-0 gap-2 flex-wrap">
                    <form action="{{ route('search') }}" method="GET" class="d-flex" role="search">
                        <div class="input-group" style="border:1px solid #6c7a89; border-radius:12px; overflow:hidden;">
                            <input class="form-control form-control-sm border-0" type="search" name="q" placeholder="Cari info..." aria-label="Search" value="{{ request('q') }}" required>
                            <button class="btn btn-light border-0 text-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    @php
        $settings = ($footerSettings ?? collect([])) ?: ($settings ?? collect([]));
    @endphp
    @php
        $footerSpacingClass = request()->routeIs('sertifikasi') ? '' : 'mt-5';
    @endphp
    <footer class="{{ $footerSpacingClass }}" style="background:#178c80; color:white;">
        <div class="container py-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <h5 class="fw-bold">PVP BANTUL</h5>
                    <p class="mb-2">{{ $settings['footer_address'] ?? 'Jl. Parangtritis Km 10, Bantul, DI Yogyakarta.' }}</p>
                    <p class="mb-1">{{ $settings['footer_phone'] ?? '(0274) 367123' }} @if(!empty($settings['footer_phone_alt'])) / {{ $settings['footer_phone_alt'] }} @endif</p>
                    <p class="mb-1">{{ $settings['footer_email'] ?? 'info@bpvpbantul.kemnaker.go.id' }}</p>
                    <p class="mb-0">{{ $settings['footer_operasional'] ?? "Jam Operasional:\nSenin - Kamis: 07.30 - 16.00 WIB\nJumat: 07.30 - 16.30 WIB" }}</p>
                    <div class="mt-3 d-flex gap-3">
                        @if(!empty($settings['footer_instagram']))
                            <a class="text-white" href="{{ $settings['footer_instagram'] }}" target="_blank"><i class="fab fa-instagram fa-lg"></i></a>
                        @endif
                        @if(!empty($settings['footer_facebook']))
                            <a class="text-white" href="{{ $settings['footer_facebook'] }}" target="_blank"><i class="fab fa-facebook fa-lg"></i></a>
                        @endif
                        @if(!empty($settings['footer_twitter']))
                            <a class="text-white" href="{{ $settings['footer_twitter'] }}" target="_blank"><i class="fab fa-twitter fa-lg"></i></a>
                        @endif
                        @if(!empty($settings['footer_youtube']))
                            <a class="text-white" href="{{ $settings['footer_youtube'] }}" target="_blank"><i class="fab fa-youtube fa-lg"></i></a>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold">Lokasi</h5>
                    @if(!empty($settings['footer_embed_map']))
                        {!! $settings['footer_embed_map'] !!}
                    @else
                        <div class="bg-dark bg-opacity-25 text-center py-4 rounded">Embed peta</div>
                    @endif
                </div>
            </div>
            <div class="text-center mt-4 pt-3 border-top border-light">
                <small>&copy; {{ date('Y') }} Satpel PVP Bantul. All Rights Reserved.</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
