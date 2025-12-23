<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Satpel PVP Bantul</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        /* Pertahankan warna default; hanya menambahkan rotasi ikon */
        .menu-group.open .menu-group-header i { transform: rotate(180deg); transition: transform 0.2s ease; }
        /* Header sidebar selaras tema Kemnaker (lihat referensi) */
        .sidebar-header {
            background: #0c1328;
            color: #e9eef7;
            padding: 16px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .sidebar-header .brand-logo {
            height: 44px;
            width: auto;
        }
        .sidebar-header h4 {
            letter-spacing: 0.5px;
            color: #e9eef7;
        }
        .sidebar-header small {
            color: #9cb3d9;
        }
    </style>
    @stack('styles')
</head>
<body>
    @php($adminUser = auth()->user())
    @php($adminInitial = strtoupper(substr($adminUser->name ?? 'A', 0, 1)))
    @php($isInstructor = $adminUser?->hasRole('instructor'))
    @php($canContent = ! $isInstructor && $adminUser?->hasAnyPermission([
        'manage-berita',
        'manage-program',
        'manage-publication',
        'manage-gallery',
        'approve-content',
        'review-content',
        'manage-seo',
    ]))
    @php($canPublication = ! $isInstructor && $adminUser?->hasAnyPermission([
        'manage-publication',
        'manage-program',
        'manage-berita',
        'manage-gallery',
        'manage-settings',
    ]))
    @php($canService = ! $isInstructor && $adminUser?->hasAnyPermission([
        'manage-public-service',
        'manage-faq',
        'manage-ppid',
        'manage-settings',
    ]))
    @php($canAlumni = ! $isInstructor && $adminUser?->hasAnyPermission([
        'manage-users',
        'moderate-alumni-forum',
        'access-alumni-forum',
        'manage-enrollment',
    ]))
    @php($canSurvey = $adminUser?->hasAnyPermission(['manage-surveys', 'view-survey-analytics']))
    @php($canClass = $adminUser?->hasAnyPermission([
        'manage-classes',
        'manage-sessions',
        'manage-assignments',
        'grade-submissions',
        'manage-announcements',
        'moderate-class-forum',
        'manage-enrollment',
    ]))
    @php($canPpid = ! $isInstructor && $adminUser?->hasAnyPermission([
        'manage-ppid',
        'manage-publication',
        'manage-faq',
        'manage-settings',
        'manage-public-service',
    ]))
    @php($canSettings = ! $isInstructor && $adminUser?->hasPermission('manage-settings'))
    <div class="admin-wrapper">
    <div class="sidebar" id="adminSidebar">
        <div class="sidebar-header d-flex justify-content-between align-items-start">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ asset('image/logo/Kemnaker_Logo_White.png') }}" alt="Kemnaker" class="brand-logo">
                <div>
                    <h4 class="fw-bold mb-0">Satpel PVP Bantul</h4>
                    <small>BPVP Surakarta • Kemnaker RI</small>
                </div>
            </div>
            <button class="btn btn-sm btn-outline-light d-lg-none" id="sidebarClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="sidebar-menu">
            @if($isInstructor)
                @if($canClass)
                    <div class="menu-group">
                        <button class="menu-group-header" data-target="#group-class">
                            <span>Kelas & Tugas</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="submenu show" id="group-class">
                            <a href="{{ route('admin.course-class.index') }}" class="{{ request()->routeIs('admin.course-class.*') ? 'active' : '' }}">
                                <i class="fas fa-graduation-cap"></i> Kelas Pelatihan
                            </a>
                            <a href="{{ route('admin.course-session.index') }}" class="{{ request()->routeIs('admin.course-session.*') ? 'active' : '' }}">
                                <i class="fas fa-video"></i> Sesi & Rekaman
                            </a>
                            <a href="{{ route('admin.course-assignment.index') }}" class="{{ request()->routeIs('admin.course-assignment.*') ? 'active' : '' }}">
                                <i class="fas fa-tasks"></i> Tugas / Quiz
                            </a>
                            <a href="{{ route('admin.course-attendance.index') }}" class="{{ request()->routeIs('admin.course-attendance.*') ? 'active' : '' }}">
                                <i class="fas fa-user-check"></i> Presensi
                            </a>
                            @if($adminUser?->hasPermission('grade-submissions'))
                                <a href="{{ route('admin.course-submission.index') }}" class="{{ request()->routeIs('admin.course-submission.*') ? 'active' : '' }}">
                                    <i class="fas fa-file-signature"></i> Submission
                                </a>
                            @endif
                            @if($adminUser?->hasPermission('manage-enrollment'))
                                <a href="{{ route('admin.course-enrollment.index') }}" class="{{ request()->routeIs('admin.course-enrollment.*') ? 'active' : '' }}">
                                    <i class="fas fa-user-plus"></i> Enrollment
                                </a>
                                <a href="{{ route('admin.course-enrollment.import') }}" class="{{ request()->routeIs('admin.course-enrollment.import*') ? 'active' : '' }}">
                                    <i class="fas fa-file-import"></i> Import Enrollment
                                </a>
                            @endif
                            <a href="{{ route('admin.course-announcement.index') }}" class="{{ request()->routeIs('admin.course-announcement.*') ? 'active' : '' }}">
                                <i class="fas fa-bullhorn"></i> Pengumuman Kelas
                            </a>
                            @if($adminUser?->hasPermission('moderate-class-forum'))
                                <a href="{{ route('admin.course-forum-reports.index') }}" class="{{ request()->routeIs('admin.course-forum-reports.*') ? 'active' : '' }}">
                                    <i class="fas fa-flag"></i> Laporan Forum
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
                @if(auth()->user()?->hasPermission('view-talent-pool') || auth()->user()?->hasRole('superadmin'))
                    <div class="menu-group">
                        <button class="menu-group-header" data-target="#group-talent">
                            <span>Kemitraan</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="submenu show" id="group-talent">
                            <a href="{{ route('admin.talent-pool.index') }}" class="{{ request()->routeIs('admin.talent-pool.*') ? 'active' : '' }}">
                                <i class="fas fa-users"></i> Talent Pool & CV Book
                            </a>
                        </div>
                    </div>
                @endif
            @else
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

                @if($canContent)
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
                @endif

                @if($canPublication)
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
                @endif

                @if($canService)
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
                @endif

                @if($canAlumni)
                <div class="menu-group">
                    <button class="menu-group-header" data-target="#group-alumni">
                        <span>Alumni & Tracer</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="submenu show" id="group-alumni">
                        <a href="{{ route('admin.alumni.index') }}" class="{{ request()->routeIs('admin.alumni.*') ? 'active' : '' }}">
                            <i class="fas fa-user-graduate"></i> Data Alumni
                        </a>
                        <a href="{{ route('admin.alumni-tracer.index') }}" class="{{ request()->routeIs('admin.alumni-tracer.*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-check"></i> Tracer Study
                        </a>
                    </div>
                </div>
                @endif

                @if($canSurvey)
                <div class="menu-group">
                    <button class="menu-group-header" data-target="#group-survey">
                        <span>Survey & Feedback</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="submenu show" id="group-survey">
                        @if($adminUser?->hasPermission('manage-surveys'))
                            <a href="{{ route('admin.surveys.index') }}" class="{{ request()->routeIs('admin.surveys.*') ? 'active' : '' }}">
                                <i class="fas fa-clipboard-list"></i> Survey Dinamis
                            </a>
                        @endif
                        @if($adminUser?->hasPermission('manage-surveys'))
                            <a href="{{ route('admin.survey-instance.index') }}" class="{{ request()->routeIs('admin.survey-instance.*') ? 'active' : '' }}">
                                <i class="fas fa-link"></i> Survey Instance (Kelas/Instruktur)
                            </a>
                        @endif
                        @if($adminUser?->hasPermission('manage-surveys'))
                            <a href="{{ route('admin.survey-instance.dashboard') }}" class="{{ request()->routeIs('admin.survey-instance.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-chart-line"></i> Dashboard Survey
                            </a>
                        @endif
                    </div>
                </div>
                @endif

                @if($canClass)
                <div class="menu-group">
                    <button class="menu-group-header" data-target="#group-class">
                        <span>Kelas & Tugas</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="submenu show" id="group-class">
                        <a href="{{ route('admin.course-class.index') }}" class="{{ request()->routeIs('admin.course-class.*') ? 'active' : '' }}">
                            <i class="fas fa-graduation-cap"></i> Kelas Pelatihan
                        </a>
                        <a href="{{ route('admin.course-session.index') }}" class="{{ request()->routeIs('admin.course-session.*') ? 'active' : '' }}">
                            <i class="fas fa-video"></i> Sesi & Rekaman
                        </a>
                        <a href="{{ route('admin.course-assignment.index') }}" class="{{ request()->routeIs('admin.course-assignment.*') ? 'active' : '' }}">
                            <i class="fas fa-tasks"></i> Tugas / Quiz
                        </a>
                        <a href="{{ route('admin.course-attendance.index') }}" class="{{ request()->routeIs('admin.course-attendance.*') ? 'active' : '' }}">
                            <i class="fas fa-user-check"></i> Presensi
                        </a>
                        @if($adminUser?->hasPermission('grade-submissions'))
                            <a href="{{ route('admin.course-submission.index') }}" class="{{ request()->routeIs('admin.course-submission.*') ? 'active' : '' }}">
                                <i class="fas fa-file-signature"></i> Submission
                            </a>
                        @endif
                        @if($adminUser?->hasPermission('manage-enrollment'))
                            <a href="{{ route('admin.course-enrollment.index') }}" class="{{ request()->routeIs('admin.course-enrollment.*') ? 'active' : '' }}">
                                <i class="fas fa-user-plus"></i> Enrollment
                            </a>
                            <a href="{{ route('admin.course-enrollment.import') }}" class="{{ request()->routeIs('admin.course-enrollment.import*') ? 'active' : '' }}">
                                <i class="fas fa-file-import"></i> Import Enrollment
                            </a>
                        @endif
                        <a href="{{ route('admin.course-announcement.index') }}" class="{{ request()->routeIs('admin.course-announcement.*') ? 'active' : '' }}">
                            <i class="fas fa-bullhorn"></i> Pengumuman Kelas
                        </a>
                        @if($adminUser?->hasPermission('moderate-class-forum'))
                            <a href="{{ route('admin.course-forum-reports.index') }}" class="{{ request()->routeIs('admin.course-forum-reports.*') ? 'active' : '' }}">
                                <i class="fas fa-flag"></i> Laporan Forum
                            </a>
                        @endif
                    </div>
                </div>
                @endif

                @if($canPpid)
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
                @endif

                @if($canSettings)
                <div class="menu-group">
                    <button class="menu-group-header" data-target="#group-settings">
                        <span>Pengaturan Situs</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="submenu show" id="group-settings">
                        <a href="{{ route('admin.settings.site') }}" class="{{ request()->routeIs('admin.settings.site*') ? 'active' : '' }}">
                            <i class="fas fa-home"></i> Beranda & Umum
                        </a>
                    </div>
                </div>
                @endif

                @if(auth()->user()?->hasPermission('view-talent-pool') || auth()->user()?->hasRole('superadmin'))
                <div class="menu-group">
                    <button class="menu-group-header" data-target="#group-talent">
                        <span>Kemitraan</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="submenu show" id="group-talent">
                        <a href="{{ route('admin.talent-pool.index') }}" class="{{ request()->routeIs('admin.talent-pool.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i> Talent Pool & CV Book
                        </a>
                    </div>
                </div>
                @endif

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
                        @if(Route::has('admin.branding-kpi.index'))
                            <a href="{{ route('admin.branding-kpi.index') }}" class="{{ request()->routeIs('admin.branding-kpi.*') ? 'active' : '' }}">
                                <i class="fas fa-bullseye"></i> KPI Branding
                            </a>
                        @endif
                        @if(auth()->user()?->hasPermission('view-talent-pool'))
                            <a href="{{ route('admin.talent-pool.index') }}" class="{{ request()->routeIs('admin.talent-pool.*') ? 'active' : '' }}">
                                <i class="fas fa-users"></i> Talent Pool & CV Book
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="sidebar-overlay d-lg-none"></div>

    <div class="content-area">
        <div class="topbar">
            <div class="topbar-main d-flex align-items-start gap-3 flex-wrap">
                <button class="sidebar-toggle d-lg-none" id="sidebarToggle" aria-label="Buka menu">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="topbar-text">
                    <div class="admin-breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        <span>/</span>
                        <span>@yield('page_title', 'Panel Admin')</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h1 class="mb-0">Halo, {{ $adminUser->name ?? 'Admin' }} 👋</h1>
                        <span class="pill pill-primary">Panel Admin</span>
                    </div>
                    <div class="admin-meta d-flex flex-wrap gap-2 align-items-center mt-1">
                        <span class="info-chip"><i class="fa-regular fa-calendar"></i>{{ now()->translatedFormat('l, d F Y') }}</span>
                        <span class="info-chip"><i class="fa-regular fa-envelope"></i>{{ $adminUser->email ?? 'Satpel PVP Bantul' }}</span>
                    </div>
                </div>
            </div>
            <div class="topbar-actions d-flex align-items-center gap-2 flex-wrap">
                <div class="quick-actions d-flex gap-2">
                    @if(! $isInstructor && $adminUser?->hasPermission('manage-berita'))
                        <a href="{{ route('admin.berita.create') }}" class="btn btn-soft-primary btn-sm d-flex align-items-center gap-1"><i class="fas fa-plus"></i><span>Berita</span></a>
                    @endif
                    <a href="{{ route('home') }}" target="_blank" class="btn btn-soft-secondary btn-sm d-flex align-items-center gap-1"><i class="fas fa-globe"></i><span>Lihat FE</span></a>
                </div>
                <div class="user-pill shadow-sm">
                    <div class="user-avatar">{{ $adminInitial }}</div>
                    <div>
                        <div class="fw-semibold">{{ $adminUser->name ?? 'Admin' }}</div>
                        <small class="text-muted">
                            @if(session('impersonator_id'))
                                Impersonasi aktif
                            @else
                                Logged in
                            @endif
                        </small>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-logout btn-sm px-3 d-flex align-items-center gap-1"><i class="fas fa-arrow-right-from-bracket"></i><span>Logout</span></button>
                </form>
            </div>
        </div>

        @if(session('impersonator_id'))
            <div class="alert alert-warning d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-user-secret"></i>
                    <div>
                        <div class="fw-semibold">Mode impersonasi</div>
                        <small>Anda sedang masuk sebagai <strong>{{ $adminUser->name }}</strong>. Akun asli: {{ session('impersonator_name') }}.</small>
                    </div>
                </div>
                <form action="{{ route('impersonate.stop') }}" method="POST" class="m-0">
                    @csrf
                    <button class="btn btn-sm btn-outline-dark">
                        <i class="fas fa-rotate-left"></i> Kembali ke akun asli
                    </button>
                </form>
            </div>
        @endif

        <div class="content-card">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
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

        const groupButtons = document.querySelectorAll('.menu-group-header');
        const storageKey = 'adminSidebarOpenGroups';

        function saveOpenGroups() {
            const openIds = Array.from(document.querySelectorAll('.submenu.show')).map(el => el.id);
            localStorage.setItem(storageKey, JSON.stringify(openIds));
        }

        function restoreGroups() {
            const saved = localStorage.getItem(storageKey);
            if (!saved) return;
            try {
                const ids = JSON.parse(saved);
                document.querySelectorAll('.submenu').forEach(el => {
                    el.classList.toggle('show', ids.includes(el.id));
                });
            } catch (e) {}
        }

        restoreGroups();

        // Selalu buka grup yang memiliki link aktif
        document.querySelectorAll('.submenu').forEach(function (submenu) {
            if (submenu.querySelector('a.active')) {
                submenu.classList.add('show');
            } else if (!localStorage.getItem(storageKey)) {
                submenu.classList.remove('show');
            }
        });

        groupButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                const target = document.querySelector(btn.dataset.target);
                if (!target) return;
                const parent = btn.closest('.menu-group');
                // tutup grup lain agar fokus tidak pindah jauh
                document.querySelectorAll('.submenu').forEach(el => {
                    if (el !== target) el.classList.remove('show');
                });
                document.querySelectorAll('.menu-group').forEach(g => g.classList.remove('open'));

                target.classList.toggle('show');
                parent?.classList.toggle('open', target.classList.contains('show'));
                saveOpenGroups();
            });
        });

        const activeLink = document.querySelector('.submenu a.active');
        if (activeLink) {
            activeLink.scrollIntoView({ block: 'center' });
        }
    </script>
    @stack('scripts')
</body>
</html>
