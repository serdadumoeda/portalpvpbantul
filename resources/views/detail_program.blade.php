@extends('layouts.app')

@section('content')
@php
    $registrationUrl = $program->pendaftaran_link ?: 'https://siapkerja.kemnaker.go.id/app/pelatihan';
    $biayaLabel = $program->biaya_label ?: 'Gratis';
    $sertifikatLabel = $program->sertifikat_label ?: 'Sertifikat Mengikuti Pelatihan';
    $bahasaLabel = $program->bahasa_label ?: 'Bahasa Indonesia';
@endphp
<section class="section-shell" style="background: linear-gradient(120deg, #f5f9ff 0%, #eef5fb 50%, #f3f9fc 100%);">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="feature-card p-4 h-100">
                    <div class="mb-3">
                        <h1 class="fw-bold mb-2">{{ $program->judul }}</h1>
                        <span class="badge bg-primary-subtle text-primary">Program Pelatihan</span>
                    </div>
                    <div class="rounded-4 overflow-hidden shadow-soft">
                        <img src="{{ $program->gambar ? asset($program->gambar) : 'https://placehold.co/960x480?text=Pelatihan' }}" class="img-fluid w-100" alt="{{ $program->judul }}" style="object-fit:cover; max-height:420px;">
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="feature-card p-3">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="rounded-4 overflow-hidden" style="width:90px; height:90px;">
                            <img src="{{ $program->gambar ? asset($program->gambar) : 'https://placehold.co/200x200?text=Pelatihan' }}" class="w-100 h-100" style="object-fit:cover;" alt="{{ $program->judul }}">
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">{{ $program->judul }}</h6>
                            <span class="badge bg-success">{{ $biayaLabel }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center text-muted mb-2"><i class="fas fa-check-circle text-success me-2"></i> {{ $sertifikatLabel }}</div>
                        <div class="d-flex align-items-center text-muted"><i class="fas fa-language text-success me-2"></i> {{ $bahasaLabel }}</div>
                    </div>
                    <a href="{{ $registrationUrl }}" target="_blank" rel="noopener" class="btn btn-primary w-100 pill-btn">Daftar Pelatihan Sekarang</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-shell">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="feature-card p-4">
                    <p class="text-muted mb-4" style="line-height:1.7;">{{ $program->deskripsi }}</p>

                        @php
                            $kodeUnits = collect(preg_split('/\r\n|\r|\n/', (string) $program->kode_unit_kompetensi))
                                ->map(fn ($item) => trim($item))
                                ->filter();
                        @endphp
                        @if($kodeUnits->isNotEmpty())
                            <h6 class="fw-bold mb-3">Kode Unit Kompetensi</h6>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="30%">Kode Unit</th>
                                            <th>Judul</th>
                                        </tr>
                                    </thead>
                                    <tbody class="small">
                                        @foreach($kodeUnits as $unit)
                                            @php
                                                if (str_contains($unit, '|')) {
                                                    [$code, $title] = array_map('trim', explode('|', $unit, 2));
                                                } elseif (str_contains($unit, ' - ')) {
                                                    [$code, $title] = array_map('trim', explode(' - ', $unit, 2));
                                                } else {
                                                    $code = null;
                                                    $title = $unit;
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $code ?? '–' }}</td>
                                                <td>{{ $title }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if($program->info_tambahan)
                            <div class="mt-3 text-muted small">
                                {!! nl2br(e($program->info_tambahan)) !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="feature-card p-3 mb-3">
                    <h6 class="fw-bold mb-3">Fasilitas & Keunggulan</h6>
                        @php
                            $fasilitas = collect(preg_split('/\r\n|\r|\n/', (string) $program->fasilitas_keunggulan))
                                ->map(fn ($item) => trim($item))
                                ->filter();
                        @endphp
                        @if($fasilitas->isNotEmpty())
                            <ul class="list-unstyled text-muted small">
                                @foreach($fasilitas as $item)
                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> {{ $item }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted small mb-0">Belum ada informasi fasilitas yang ditambahkan.</p>
                        @endif
                    </div>
                </div>
                <div class="feature-card p-3">
                    <h6 class="fw-bold mb-3">Info Tambahan</h6>
                        @php
                            $infoTambahan = collect(preg_split('/\r\n|\r|\n/', (string) $program->info_tambahan))
                                ->map(fn ($item) => trim($item))
                                ->filter();
                        @endphp
                        @if($infoTambahan->isNotEmpty())
                            <div class="text-muted small">
                                @foreach($infoTambahan as $info)
                                    <div class="mb-2"><i class="fas fa-circle text-success me-2" style="font-size:0.5rem;"></i> {{ $info }}</div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted small mb-0">Tidak ada info tambahan.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-shell bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-1">Pelatihan yang Terkait</h4>
                <p class="text-muted mb-0">Jelajahi pelatihan terkait sesuai minat Anda.</p>
            </div>
            <a href="{{ route('pelatihan.katalog') }}" class="btn btn-outline-primary btn-sm pill-btn">Lihat Semua</a>
        </div>
        <div class="row g-3">
            @foreach($programs = \App\Models\Program::where('id', '!=', $program->id)->latest()->take(3)->get() as $related)
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100">
                    <img src="{{ $related->gambar ? asset($related->gambar) : 'https://placehold.co/400x240?text=Program' }}" class="card-img-top" style="height:200px; object-fit:cover;" alt="{{ $related->judul }}">
                    <div class="p-3">
                        <h6 class="fw-bold">{{ $related->judul }}</h6>
                        <p class="text-muted small">{{ Str::limit($related->deskripsi ?? '', 90) }}</p>
                        <a href="{{ route('program.show', $related->id) }}" class="btn btn-sm btn-primary pill-btn">Ikut Pelatihan</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
