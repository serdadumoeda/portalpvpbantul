@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Instruktur</h3>
    <a href="{{ route('admin.instructor.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah Instruktur</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Keahlian</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($instructors as $instruktur)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($instruktur->foto)
                            <img src="{{ asset($instruktur->foto) }}" width="60" class="rounded-circle">
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $instruktur->nama }}</td>
                    <td>{{ $instruktur->keahlian }}</td>
                    <td>{{ $instruktur->urutan }}</td>
                    <td>
                        <span class="badge {{ $instruktur->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $instruktur->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="d-flex gap-2">
                        <a href="{{ route('admin.instructor.edit', $instruktur->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('admin.instructor.destroy', $instruktur->id) }}" method="POST" onsubmit="return confirm('Hapus instruktur ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data instruktur.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
