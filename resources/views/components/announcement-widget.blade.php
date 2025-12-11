@if(!empty($announcements) && $announcements->count())
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h4 class="fw-bold mb-0">{{ $title ?? 'Pengumuman Terkini' }}</h4>
                <p class="text-muted mb-0 small">{{ $subtitle ?? 'Informasi terbaru dari Satpel PVP Bantul.' }}</p>
            </div>
            <a href="{{ route('pengumuman.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">Lihat Semua</a>
        </div>
        <div class="row g-3">
            @foreach($announcements as $item)
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-primary-subtle text-primary">{{ $item->created_at?->translatedFormat('d M Y') ?? '-' }}</span>
                                @if($item->file_download)
                                    <span class="text-muted small"><i class="fas fa-paperclip me-1"></i> Lampiran</span>
                                @endif
                            </div>
                            <h6 class="fw-bold mb-2">{{ Str::limit($item->judul, 70) }}</h6>
                            <p class="text-muted small mb-3">{{ Str::limit(strip_tags($item->isi), 100) }}</p>
                            <a href="{{ route('pengumuman.show', $item->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill">Baca</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
