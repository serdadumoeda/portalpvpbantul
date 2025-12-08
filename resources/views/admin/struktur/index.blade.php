@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Struktur Organisasi</h3>
    <a href="{{ route('admin.struktur.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Tambah Jabatan
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body">
        @if($roots->isEmpty())
            <p class="text-muted mb-0">Belum ada data struktur. Tambahkan jabatan pertama.</p>
        @else
            @php
                $renderTree = function($nodes) use (&$renderTree) {
                    echo '<ul class="list-unstyled ms-3">';
                    foreach ($nodes as $node) {
                        ?>
                        <li class="mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold">{{ e($node->nama) }}</span>
                                @if($node->jabatan)
                                    <span class="text-muted">({{ e($node->jabatan) }})</span>
                                @endif
                                <span class="badge bg-light text-dark">Urutan: {{ e($node->urutan) }}</span>
                                <div class="ms-2 d-inline-flex gap-1">
                                    <a href="{{ route('admin.struktur.edit', $node->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <form action="{{ route('admin.struktur.destroy', $node->id) }}" method="POST" onsubmit="return confirm('Hapus data ini beserta anaknya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </div>
                            </div>
                            @if($node->children->count())
                                {!! $renderTree($node->children) !!}
                            @endif
                        </li>
                        <?php
                    }
                    echo '</ul>';
                };
            @endphp
            {!! $renderTree($roots) !!}
        @endif
    </div>
</div>
@endsection
