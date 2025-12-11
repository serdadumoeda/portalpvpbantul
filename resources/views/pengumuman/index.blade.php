@extends('layouts.app')

@section('content')
<section class="py-5" style="background:#fff;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Pengumuman</h2>
                <p class="text-muted mb-0">Informasi resmi Satpel PVP Bantul untuk peserta dan mitra.</p>
            </div>
            <form method="GET" class="d-flex" role="search">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Cari pengumuman..." value="{{ $search }}">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search me-1"></i> Cari</button>
                </div>
            </form>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                @forelse($announcements as $item)
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-primary-subtle text-primary">{{ $item->created_at?->translatedFormat('d F Y') ?? '-' }}</span>
                                @if($item->file_download)
                                    <span class="text-muted small"><i class="fas fa-paperclip me-1"></i> Lampiran</span>
                                @endif
                            </div>
                            <h5 class="fw-bold">
                                <a href="{{ route('pengumuman.show', $item->slug) }}" class="text-decoration-none text-dark">{{ $item->judul }}</a>
                            </h5>
                            <p class="text-muted mb-3">{{ Str::limit(strip_tags($item->isi), 180) }}</p>
                            <a href="{{ route('pengumuman.show', $item->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill">Baca Selengkapnya</a>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">Belum ada pengumuman yang tersedia.</div>
                @endforelse

                <div class="mt-4">
                    {{ $announcements->links() }}
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold">Pengumuman Terbaru</div>
                    <div class="list-group list-group-flush">
                        @foreach($recent as $item)
                            <a href="{{ route('pengumuman.show', $item->slug) }}" class="list-group-item list-group-item-action">
                                <small class="text-muted">{{ $item->created_at?->translatedFormat('d M Y') ?? '-' }}</small>
                                <div class="fw-semibold">{{ Str::limit($item->judul, 70) }}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
