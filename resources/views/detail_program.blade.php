@extends('layouts.app')

@section('content')
<section class="py-4" style="background: linear-gradient(135deg, #e6f7f4 0%, #eef5fb 50%, #e4f3f0 100%);">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="mb-3">
                    <h1 class="fw-bold mb-2">{{ $program->judul }}</h1>
                    <span class="badge bg-success">Program Pelatihan</span>
                </div>
                <div class="rounded-4 overflow-hidden shadow-sm">
                    <img src="{{ $program->gambar ? asset($program->gambar) : 'https://placehold.co/960x480?text=Pelatihan' }}" class="img-fluid w-100" alt="{{ $program->judul }}" style="object-fit:cover; max-height:420px;">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="rounded-4 overflow-hidden" style="width:90px; height:90px;">
                                <img src="{{ $program->gambar ? asset($program->gambar) : 'https://placehold.co/200x200?text=Pelatihan' }}" class="w-100 h-100" style="object-fit:cover;" alt="{{ $program->judul }}">
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">{{ $program->judul }}</h6>
                                <span class="badge bg-primary">Gratis</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex align-items-center text-muted mb-2"><i class="fas fa-check-circle text-success me-2"></i> Sertifikat Mengikuti Pelatihan</div>
                            <div class="d-flex align-items-center text-muted"><i class="fas fa-language text-success me-2"></i> Bahasa Indonesia</div>
                        </div>
                        <a href="{{ route('program.show', $program->id) }}" class="btn btn-success w-100 rounded-pill">Daftar Pelatihan Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-4" style="line-height:1.7;">{{ $program->deskripsi }}</p>
                        <h6 class="fw-bold mb-3">Kode Unit Kompetensi</h6>
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode Unit</th>
                                        <th>Judul Unit Kompetensi</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    <tr><td>THP PX02.02.01</td><td>Mengidentifikasi Bahan Kemasan Alami</td></tr>
                                    <tr><td>THP PX02.04.03.01</td><td>Mengidentifikasi Bahan Kemasan Buatan</td></tr>
                                    <tr><td>THP PX02.04.04.01</td><td>Memilih Cara, Bahan Kemasan, dan Alat Pengemasan Manual</td></tr>
                                    <tr><td>THP PX02.04.05.01</td><td>Mengemas Secara Manual</td></tr>
                                    <tr><td>THP PX02.04.06.01</td><td>Mengoperasikan Proses Pengemasan</td></tr>
                                    <tr><td>THP PX02.04.07.01</td><td>Menerapkan Prinsip Pengemasan Komoditas Pertanian</td></tr>
                                    <tr><td>THP PX02.04.08.01</td><td>Memilih Cara, Bahan Kemasan, dan Alat Pengemasan Material</td></tr>
                                    <tr><td>THP PX02.04.09.01</td><td>Mengoperasikan Proses Pada Sistem Pengemasan</td></tr>
                                    <tr><td>THP PX02.05.00.01</td><td>Membuat Desain Grafis Kemasan</td></tr>
                                    <tr><td colspan="2" class="fw-bold">Produktivitas</td></tr>
                                    <tr><td colspan="2" class="fw-bold">Softskills</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 text-muted small">Pelatihan dilaksanakan selama 140 jam pelatihan (±20 hari).</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Fasilitas & Keunggulan</h6>
                        <ul class="list-unstyled text-muted small">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Workshop standar industri</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Instruktur tersertifikasi</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Bahan praktik lengkap</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Sertifikat pelatihan & kompetensi</li>
                        </ul>
                    </div>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Info Tambahan</h6>
                        <div class="text-muted small">
                            <div class="mb-2"><i class="fas fa-clock me-2 text-success"></i> Durasi: 140 JP (±20 hari)</div>
                            <div class="mb-2"><i class="fas fa-map-marker-alt me-2 text-success"></i> Lokasi: Kampus Satpel PVP Bantul</div>
                            <div class="mb-2"><i class="fas fa-user-friends me-2 text-success"></i> Kapasitas: 20 peserta</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-1">Pelatihan yang Terkait</h4>
                <p class="text-muted mb-0">Jelajahi pelatihan terkait sesuai minat Anda.</p>
            </div>
            <a href="{{ route('pelatihan.katalog') }}" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
        </div>
        <div class="row g-3">
            @foreach($programs = \App\Models\Program::where('id', '!=', $program->id)->latest()->take(3)->get() as $related)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="{{ $related->gambar ? asset($related->gambar) : 'https://placehold.co/400x240?text=Program' }}" class="card-img-top" style="height:200px; object-fit:cover;" alt="{{ $related->judul }}">
                    <div class="card-body">
                        <h6 class="fw-bold">{{ $related->judul }}</h6>
                        <p class="text-muted small">{{ Str::limit($related->deskripsi ?? '', 90) }}</p>
                        <a href="{{ route('program.show', $related->id) }}" class="btn btn-sm btn-primary rounded-pill">Ikut Pelatihan</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
