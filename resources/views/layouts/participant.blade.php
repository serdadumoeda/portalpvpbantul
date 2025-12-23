<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Peserta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f6f8fb; }
        .participant-nav { background: #0b5ed7; color: #fff; }
        .participant-nav a { color: #fff; text-decoration: none; }
        .participant-card { border: none; }
        .skip-link { position: absolute; left: -999px; top: -999px; background:#fff; color:#0b5ed7; padding:8px 12px; z-index:1000; }
        .skip-link:focus { left: 8px; top: 8px; outline: 2px solid #0b5ed7; }
    </style>
    @stack('styles')
</head>
<body>
    <a href="#main-content" class="skip-link">Lewati ke konten utama</a>
    <nav class="participant-nav py-3 mb-4" role="navigation" aria-label="Navigasi peserta">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-graduation-cap fa-lg"></i>
                <div>
                    <div class="fw-bold">Portal Peserta</div>
                    <small class="text-white-50">Kelas & Tugas</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('participant.classes') }}" class="fw-semibold {{ request()->routeIs('participant.classes') ? 'text-warning' : '' }}">Kelas Saya</a>
                <a href="{{ route('participant.assignments') }}" class="fw-semibold {{ request()->routeIs('participant.assignments*') ? 'text-warning' : '' }}">Tugas</a>
                <a href="{{ route('participant.classes') }}#forum" class="fw-semibold {{ request()->routeIs('participant.class.forum.*') ? 'text-warning' : '' }}">Forum</a>
                <a href="{{ route('participant.classes') }}#announcements" class="fw-semibold {{ request()->routeIs('participant.class.announcements*') ? 'text-warning' : '' }}">Pengumuman</a>
                <form action="{{ route('logout') }}" method="POST" class="mb-0">
                    @csrf
                    <button class="btn btn-sm btn-outline-light">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    @if(session('impersonator_id'))
        <div class="alert alert-warning rounded-0 mb-0">
            <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    Mode impersonasi: Anda sebagai <strong>{{ auth()->user()->name ?? 'peserta' }}</strong>.
                    <span class="text-muted">Akun asli: {{ session('impersonator_name') }}.</span>
                </div>
                <form action="{{ route('impersonate.stop') }}" method="POST" class="m-0">
                    @csrf
                    <button class="btn btn-sm btn-outline-dark">
                        <i class="fas fa-rotate-left"></i> Kembali ke akun asli
                    </button>
                </form>
            </div>
        </div>
    @endif

    <div class="container mb-5">
        <main id="main-content">
            @if(session('consent_required'))
                <div class="alert alert-warning d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Privasi & Rekaman:</strong> Dengan mengikuti kelas, Anda setuju pada tata tertib, kemungkinan dokumentasi foto/video untuk keperluan laporan, dan perlindungan data sesuai kebijakan.
                    </div>
                    <form action="{{ route('participant.consent') }}" method="POST" class="ms-3">
                        @csrf
                        <input type="hidden" name="class_id" value="{{ session('consent_class') }}">
                        <button class="btn btn-sm btn-primary">Saya Mengerti</button>
                    </form>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
