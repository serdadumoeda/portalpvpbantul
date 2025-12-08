@extends('layouts.admin')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Berita</p>
                        <h3 class="fw-bold">{{ $metrics['berita'] }}</h3>
                    </div>
                    <span class="badge bg-primary-subtle text-primary p-3 rounded-circle">
                        <i class="fas fa-newspaper"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Program Pelatihan</p>
                        <h3 class="fw-bold">{{ $metrics['program'] }}</h3>
                    </div>
                    <span class="badge bg-success-subtle text-success p-3 rounded-circle">
                        <i class="fas fa-graduation-cap"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Pengumuman Aktif</p>
                        <h3 class="fw-bold">{{ $metrics['pengumuman'] }}</h3>
                    </div>
                    <span class="badge bg-warning-subtle text-warning p-3 rounded-circle">
                        <i class="fas fa-bullhorn"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Pesan Masuk</p>
                        <h3 class="fw-bold">{{ $metrics['pesan'] }}</h3>
                        <small class="text-danger">{{ $pesanUnread }} belum diproses</small>
                    </div>
                    <span class="badge bg-danger-subtle text-danger p-3 rounded-circle">
                        <i class="fas fa-envelope-open-text"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Kunjungan</p>
                        <h3 class="fw-bold">{{ number_format($metrics['visits']) }}</h3>
                        <small class="text-muted">7 hari terakhir: {{ $visitTrend->sum('total') }}</small>
                    </div>
                    <span class="badge bg-info-subtle text-info p-3 rounded-circle">
                        <i class="fas fa-chart-line"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow border-0 mb-4">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Tren Berita Minggu Ini</h5>
                    <small class="text-muted">Jumlah artikel yang dipublikasikan per hari</small>
                </div>
            </div>
            <div class="card-body">
                <canvas id="newsTrendChart" height="120"></canvas>
            </div>
        </div>

        <div class="card shadow border-0">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Aktivitas Terbaru</h5>
                    <small class="text-muted">5 aktivitas terakhir oleh admin</small>
                </div>
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentActivities as $activity)
                        <div class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold text-primary">{{ $activity->action }}</div>
                                <small class="text-muted">{{ $activity->description ?? '-' }}</small>
                            </div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</small>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted py-4">Belum ada aktivitas.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow border-0 mb-4">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">Ringkasan Konten</h5>
                <small class="text-muted">Data cepat untuk modul utama</small>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Lowongan Aktif
                        <span class="badge bg-primary rounded-pill">{{ $metrics['lowongan'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Program Pelatihan
                        <span class="badge bg-success rounded-pill">{{ $metrics['program'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Pengumuman
                        <span class="badge bg-warning rounded-pill">{{ $metrics['pengumuman'] }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card shadow border-0">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Selamat Datang Kembali!</h5>
                <p class="text-muted mb-4">Gunakan panel ini untuk memantau kinerja portal dan menindaklanjuti pesan/publikasi terbaru.</p>
                <a href="{{ route('admin.berita.create') }}" class="btn btn-primary w-100 mb-2">Buat Berita Baru</a>
                <a href="{{ route('admin.pesan.index') }}" class="btn btn-outline-secondary w-100">Tinjau Pesan Masuk</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('newsTrendChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($trendLabels->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))) !!},
                datasets: [{
                    label: 'Berita',
                    data: {!! json_encode($beritaSeries) !!},
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.15)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Kunjungan',
                    data: {!! json_encode($visitSeries) !!},
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22, 163, 74, 0.15)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                plugins: { legend: { display: true } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    </script>
@endpush
@endsection
