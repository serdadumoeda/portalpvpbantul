@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Permohonan Informasi Publik</h4>
        <small class="text-muted">Daftar permohonan yang dikirim melalui formulir PPID.</small>
    </div>
    <a href="{{ route('ppid') }}" target="_blank" class="btn btn-outline-primary btn-sm">Lihat Form</a>
</div>

<div class="bg-white rounded shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Identitas</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Masuk</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)
                    <tr>
                        <td>{{ $requests->firstItem() + $loop->index }}</td>
                        <td>{{ $request->nama }}</td>
                        <td>{{ $request->nomor_identitas }}</td>
                        <td>{{ $request->email }}</td>
                        <td>{{ $request->no_hp }}</td>
                        <td>{{ $request->created_at->format('d M Y H:i') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.ppid-request.show', $request) }}" class="btn btn-sm btn-primary">Detail</a>
                            <form action="{{ route('admin.ppid-request.destroy', $request) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus permohonan ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada permohonan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">
        {{ $requests->links() }}
    </div>
</div>
@endsection
