@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">{{ $data->judul }}</h2>
        <div class="border-bottom border-3 border-primary w-25 mx-auto mt-3"></div>
    </div>

    <div class="card shadow-lg border-0">
        <div class="card-body p-4 p-md-5 text-center">
            
            @if($data->gambar)
                <div class="mb-5">
                    <img src="{{ asset($data->gambar) }}" class="img-fluid rounded border" alt="Bagan Struktur Organisasi">
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i> Bagan Struktur Organisasi belum diunggah oleh Admin.
                </div>
            @endif
            
            <div class="text-start mt-4 pt-4 border-top">
                <h4 class="fw-bold mb-3 text-secondary">Struktur Organisasi</h4>
                @if($structures->isEmpty())
                    <div class="alert alert-info">Belum ada data struktur organisasi.</div>
                @else
                    @php
                        $renderTree = function($nodes) use (&$renderTree) {
                            echo '<ul class="list-unstyled ps-3 border-start">';
                            foreach ($nodes as $node) {
                                ?>
                                <li class="mb-3">
                                    <div class="fw-bold">{{ e($node->nama) }}</div>
                                    @if($node->jabatan)
                                        <div class="text-muted small">{{ e($node->jabatan) }}</div>
                                    @endif
                                    @if($node->children->count())
                                        {!! $renderTree($node->children) !!}
                                    @endif
                                </li>
                                <?php
                            }
                            echo '</ul>';
                        };
                    @endphp
                    {!! $renderTree($structures) !!}
                @endif
            </div>

            @if($data->konten)
            <div class="text-start mt-4 pt-4 border-top">
                <h4 class="fw-bold mb-3 text-secondary">Keterangan Detail:</h4>
                <div class="content-body table-responsive">
                    {!! $data->konten !!}
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
