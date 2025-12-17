@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Undangan Pengguna</h2>
        <a href="{{ route('admin.invitations.create') }}" class="btn btn-outline-primary btn-sm">Buat undangan</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger rounded-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Email</th>
                            <th>Peran</th>
                            <th>Kadaluarsa</th>
                            <th>Status</th>
                            <th>Diundang oleh</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invitations as $invitation)
                            <tr>
                                <td>{{ $invitation->email }}</td>
                                <td>{{ $invitation->role?->label ?? '—' }}</td>
                                <td>{{ $invitation->expires_at?->translatedFormat('d M Y H:i') ?? '—' }}</td>
                                <td>
                                    @if($invitation->isUsed())
                                        <span class="badge bg-success">Digunakan</span>
                                    @elseif($invitation->isRevoked())
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @elseif($invitation->isExpired())
                                        <span class="badge bg-warning text-dark">Kadaluarsa</span>
                                    @else
                                        <span class="badge bg-info text-dark">Aktif</span>
                                    @endif
                                </td>
                                <td>{{ $invitation->creator?->name ?? '—' }}</td>
                                <td>
                                    @if(! $invitation->isUsed() && ! $invitation->isRevoked() && ! $invitation->isExpired())
                                        <form action="{{ route('admin.invitations.destroy', $invitation) }}" method="POST" onsubmit="return confirm('Batalkan undangan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Batalkan</button>
                                        </form>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Belum ada undangan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $invitations->links('pagination::bootstrap-5') }}
    </div>
@endsection
