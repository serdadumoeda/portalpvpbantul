@extends('layouts.admin')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Alur Pelayanan Publik</h4>
        <small class="text-muted">Kelola setiap blok alur agar tampilan halaman mengikuti UI lampiran.</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('resource.pelayanan') }}" target="_blank" class="btn btn-outline-primary btn-sm">Lihat Halaman</a>
        <a href="{{ route('admin.public-service-flow.create') }}" class="btn btn-primary btn-sm">Tambah Alur</a>
    </div>
</div>

<div class="bg-white rounded shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Kategori</th>
                    <th>Judul</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($flows as $flow)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ ucfirst($flow->category) }}</td>
                        <td>
                            <strong>{{ $flow->title }}</strong>
                            <div class="small text-muted">{{ \Illuminate\Support\Str::limit($flow->subtitle, 80) }}</div>
                        </td>
                        <td>{{ $flow->urutan }}</td>
                        <td>
                            <span class="badge {{ $flow->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $flow->is_active ? 'Aktif' : 'Draft' }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.public-service-flow.edit', $flow) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.public-service-flow.destroy', $flow) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus alur ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data alur.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
