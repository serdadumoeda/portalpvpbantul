<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Satpel PVP Bantul</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 280px;
            --primary: #2563eb;
            --primary-dark: #1e3a8a;
            --text-muted: #94a3b8;
        }
        html, body { height: 100%; }
        body {
            background: linear-gradient(135deg, #eef2ff, #e0f2fe);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: #1f2937;
            transition: background 0.3s ease;
        }
        .admin-wrapper { min-height: 100vh; display: flex; }
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #0f172a, #1f2937);
            color: #f8fafc;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            overflow-y: auto;
            padding-bottom: 2rem;
            box-shadow: 8px 0 30px rgba(15, 23, 42, 0.35);
            transition: transform 0.3s ease;
            z-index: 1045;
        }
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-thumb { background-color: rgba(255,255,255,0.25); border-radius: 50px; }
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .sidebar-header .brand-logo {
            height: 42px;
            width: auto;
        }
        .sidebar-header h4 {
            font-size: 1.15rem;
            letter-spacing: 0.04em;
            margin-bottom: 0.25rem;
        }
        .sidebar-header small { color: var(--text-muted); }
        .sidebar-menu { padding: 1rem 0.75rem; }
        .menu-group { margin-bottom: 1rem; border-radius: 12px; background-color: rgba(255,255,255,0.02); }
        .menu-group-header {
            width: 100%;
            text-align: left;
            border: none;
            background: transparent;
            color: #cbd5f5;
            font-size: 0.85rem;
            letter-spacing: 0.08em;
            padding: 0.85rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .menu-group-header:hover { color: white; }
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        .submenu.show { max-height: 1000px; }
        .submenu a {
            color: #e2e8f0;
            text-decoration: none;
            padding: 0.65rem 1.25rem;
            display: flex;
            align-items: center;
            border-left: 3px solid transparent;
            gap: 0.65rem;
            font-size: 0.95rem;
        }
        .submenu a i { width: 18px; text-align: center; }
        .submenu a:hover,
        .submenu a.active {
            background: rgba(37, 99, 235, 0.15);
            border-color: var(--primary);
            color: white;
        }
        .content-area {
            margin-left: var(--sidebar-width);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 2rem;
            gap: 1.5rem;
        }
        .content-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
            padding: 1.5rem;
            flex: 1;
        }
        .topbar {
            background: white;
            border-radius: 16px;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            gap: 1rem;
        }
        .topbar h1 {
            font-size: 1.35rem;
            margin: 0;
        }
        .btn-logout {
            background: linear-gradient(135deg, #ef4444, #b91c1c);
            border: none;
            color: white;
        }
        .sidebar-toggle {
            border: none;
            background: rgba(37, 99, 235, 0.15);
            color: var(--primary);
            border-radius: 999px;
            padding: 0.5rem 0.9rem;
        }
        .sidebar-toggle:focus { outline: none; box-shadow: 0 0 0 3px rgba(37,99,235,0.25); }
        @media (max-width: 991.98px) {
            .content-area { margin-left: 0; padding: 1.5rem 1rem; }
            .sidebar { transform: translateX(-100%); }
            body.sidebar-open .sidebar { transform: translateX(0); }
            .sidebar-overlay {
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, 0.5);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
                z-index: 1040;
            }
            body.sidebar-open .sidebar-overlay {
                opacity: 1;
                pointer-events: all;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar" id="adminSidebar">
        <div class="sidebar-header d-flex justify-content-between align-items-start">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ asset('image/logo/Kemnaker_Logo_White.png') }}" alt="Kemnaker" class="brand-logo">
                <div>
                    <h4 class="fw-bold mb-0">ADMIN PVP</h4>
                    <small>Satpel PVP Bantul</small>
                </div>
            </div>
            <button class="btn btn-sm btn-outline-light d-lg-none" id="sidebarClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="sidebar-menu">
            <div class="menu-group">
                <button class="menu-group-header" data-target="#group-dashboard">
                    <span>Overview</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="submenu show" id="group-dashboard">
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-gauge"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.pesan.index') }}" class="{{ request()->routeIs('admin.pesan.*') ? 'active' : '' }}">
                        <i class="fas fa-envelope-open-text"></i> Kotak Masuk
                    </a>
                </div>
            </div>

            <div class="menu-group">
                <button class="menu-group-header" data-target="#group-content">
                    <span>Konten & Publikasi</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="submenu show" id="group-content">
                    <a href="{{ route('admin.berita.index') }}" class="{{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper"></i> Berita / Artikel
                    </a>
                    <a href="{{ route('admin.pengumuman.index') }}" class="{{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}">
                        <i class="fas fa-bullhorn"></i> Pengumuman
                    </a>
                    <a href="{{ route('admin.galeri.index') }}" class="{{ request()->routeIs('admin.galeri.*') ? 'active' : '' }}">
                        <i class="fas fa-image"></i> Galeri Foto
                    </a>
                    <a href="{{ route('admin.program.index') }}" class="{{ request()->routeIs('admin.program.*') ? 'active' : '' }}">
                        <i class="fas fa-graduation-cap"></i> Program Pelatihan
                    </a>
                    <a href="{{ route('admin.lowongan.index') }}" class="{{ request()->routeIs('admin.lowongan.*') ? 'active' : '' }}">
                        <i class="fas fa-briefcase"></i> Lowongan Kerja
                    </a>
                    <a href="{{ route('admin.partner.index') }}" class="{{ request()->routeIs('admin.partner.*') ? 'active' : '' }}">
                        <i class="fas fa-handshake-angle"></i> Partner & Kolaborasi
                    </a>
                    <a href="{{ route('admin.testimonial.index') }}" class="{{ request()->routeIs('admin.testimonial.*') ? 'active' : '' }}">
                        <i class="fas fa-comment-dots"></i> Testimonial
                    </a>
                </div>
            </div>

            <div class="menu-group">
                <button class="menu-group-header" data-target="#group-publication">
                    <span>Publikasi Digital</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="submenu show" id="group-publication">
                    <a href="{{ route('admin.publication.settings') }}" class="{{ request()->routeIs('admin.publication.settings*') ? 'active' : '' }}">
                        <i class="fas fa-sliders"></i> Pengaturan Publikasi
                    </a>
                    <a href="{{ route('admin.publication-category.index') }}" class="{{ request()->routeIs('admin.publication-category.*') ? 'active' : '' }}">
                        <i class="fas fa-folder-tree"></i> Kategori Publikasi
                    </a>
                    <a href="{{ route('admin.publication-item.index') }}" class="{{ request()->routeIs('admin.publication-item.*') ? 'active' : '' }}">
                        <i class="fas fa-book-open"></i> Item Publikasi
                    </a>
                    <a href="{{ route('admin.certification-content.index') }}" class="{{ request()->routeIs('admin.certification-content.*') ? 'active' : '' }}">
                        <i class="fas fa-layer-group"></i> Konten Sertifikasi
                    </a>
                    <a href="{{ route('admin.certification-scheme.index') }}" class="{{ request()->routeIs('admin.certification-scheme.*') ? 'active' : '' }}">
                        <i class="fas fa-id-badge"></i> Skema Sertifikasi
                    </a>
                    <a href="{{ route('admin.profile.index') }}" class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i> Profil Instansi
                    </a>
                </div>
            </div>

            <div class="menu-group">
                <button class="menu-group-header" data-target="#group-service">
                    <span>Pelayanan Publik</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="submenu show" id="group-service">
                    <a href="{{ route('admin.public-service.settings') }}" class="{{ request()->routeIs('admin.public-service.settings*') ? 'active' : '' }}">
                        <i class="fas fa-headset"></i> Pengaturan Pelayanan
                    </a>
                    <a href="{{ route('admin.public-service-flow.index') }}" class="{{ request()->routeIs('admin.public-service-flow.*') ? 'active' : '' }}">
                        <i class="fas fa-route"></i> Alur Pelayanan
                    </a>
                    <a href="{{ route('admin.contact.settings') }}" class="{{ request()->routeIs('admin.contact.settings*') ? 'active' : '' }}">
                        <i class="fas fa-phone"></i> Hubungi Kami
                    </a>
                    <a href="{{ route('admin.contact-channel.index') }}" class="{{ request()->routeIs('admin.contact-channel.*') ? 'active' : '' }}">
                        <i class="fas fa-address-card"></i> Channel Kontak
                    </a>
                    <a href="{{ route('admin.faq.settings') }}" class="{{ request()->routeIs('admin.faq.settings*') ? 'active' : '' }}">
                        <i class="fas fa-circle-question"></i> Pengaturan FAQ
                    </a>
                    <a href="{{ route('admin.faq-category.index') }}" class="{{ request()->routeIs('admin.faq-category.*') ? 'active' : '' }}">
                        <i class="fas fa-folder-open"></i> Kategori FAQ
                    </a>
                    <a href="{{ route('admin.faq-item.index') }}" class="{{ request()->routeIs('admin.faq-item.*') ? 'active' : '' }}">
                        <i class="fas fa-question"></i> Item FAQ
                    </a>
                </div>
            </div>

            <div class="menu-group">
                <button class="menu-group-header" data-target="#group-ppid">
                    <span>PPID & Infografis</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="submenu show" id="group-ppid">
                    <a href="{{ route('admin.ppid.settings') }}" class="{{ request()->routeIs('admin.ppid.settings*') ? 'active' : '' }}">
                        <i class="fas fa-shield-alt"></i> Pengaturan PPID
                    </a>
                    <a href="{{ route('admin.ppid-highlight.index') }}" class="{{ request()->routeIs('admin.ppid-highlight.*') ? 'active' : '' }}">
                        <i class="fas fa-icons"></i> Highlight PPID
                    </a>
                    <a href="{{ route('admin.ppid-request.index') }}" class="{{ request()->routeIs('admin.ppid-request.*') ? 'active' : '' }}">
                        <i class="fas fa-inbox"></i> Permohonan PPID
                    </a>
                    <a href="{{ route('admin.infographic-year.index') }}" class="{{ request()->routeIs('admin.infographic-year.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar"></i> Infografis Tahun
                    </a>
                    <a href="{{ route('admin.infographic-metric.index') }}" class="{{ request()->routeIs('admin.infographic-metric.*') ? 'active' : '' }}">
                        <i class="fas fa-list-ol"></i> Infografis Metric
                    </a>
                    <a href="{{ route('admin.infographic-card.index') }}" class="{{ request()->routeIs('admin.infographic-card.*') ? 'active' : '' }}">
                        <i class="fas fa-layer-group"></i> Infografis Kartu
                    </a>
                    <a href="{{ route('admin.infographic-embed.index') }}" class="{{ request()->routeIs('admin.infographic-embed.*') ? 'active' : '' }}">
                        <i class="fas fa-video"></i> Infografis Embed
                    </a>
                </div>
            </div>

            <div class="menu-group">
                <button class="menu-group-header" data-target="#group-shortcuts">
                    <span>Shortcut</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="submenu show" id="group-shortcuts">
                    <a href="{{ route('home') }}" target="_blank">
                        <i class="fas fa-globe"></i> Lihat Website
                    </a>
                    @if(auth()->user()?->hasPermission('manage-users'))
                        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-users-cog"></i> Manajemen Pengguna
                        </a>
                    @endif
                    @if(auth()->user()?->hasPermission('manage-access'))
                        <a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                            <i class="fas fa-user-shield"></i> Role
                        </a>
                        <a href="{{ route('admin.permissions.index') }}" class="{{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                            <i class="fas fa-key"></i> Permission
                        </a>
                    @endif
                    @if(auth()->user()?->hasPermission('manage-audit'))
                        <a href="{{ route('admin.activity-logs.index') }}" class="{{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-list"></i> Log Aktivitas
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="sidebar-overlay d-lg-none"></div>

    <div class="content-area">
        <div class="topbar">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <h1>Selamat Datang, Admin!</h1>
                    <small class="text-muted">Kelola seluruh konten dan layanan BPVP Bantul.</small>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-logout btn-sm px-3"><i class="fas fa-arrow-right-from-bracket me-1"></i> Logout</button>
            </form>
        </div>

        <div class="content-card">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const bodyEl = document.body;
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const sidebarOverlay = document.querySelector('.sidebar-overlay');

        function toggleSidebar(state) {
            if (state === 'close') {
                bodyEl.classList.remove('sidebar-open');
            } else {
                bodyEl.classList.toggle('sidebar-open');
            }
        }

        if (sidebarToggle) sidebarToggle.addEventListener('click', () => toggleSidebar());
        if (sidebarClose) sidebarClose.addEventListener('click', () => toggleSidebar('close'));
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', () => toggleSidebar('close'));

        document.querySelectorAll('.menu-group-header').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const target = document.querySelector(btn.dataset.target);
                if (!target) return;
                target.classList.toggle('show');
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
