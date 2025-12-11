@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Partner Kami</h3>
    <a href="{{ route('admin.partner.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah Partner</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Logo</th>
                    <th>Nama</th>
                    <th>Tautan</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($partners as $partner)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($partner->logo)
                            <img src="{{ asset($partner->logo) }}" alt="{{ $partner->nama }}" width="60">
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $partner->nama }}</td>
                    <td>
                        @if($partner->tautan)
                            <a href="{{ $partner->tautan }}" target="_blank">{{ $partner->tautan }}</a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $partner->urutan }}</td>
                    <td>
                        <span class="badge {{ $partner->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $partner->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="d-flex gap-2">
                        <a href="{{ route('admin.partner.edit', $partner->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('admin.partner.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Hapus partner ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data partner.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
