@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Kartu Ringkasan Infografis</h3>
        <p class="text-muted mb-0">Menampilkan daftar topik seperti "Top Program" atau "Top Penempatan".</p>
    </div>
    <a href="{{ route('admin.infographic-card.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah Kartu</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tahun</th>
                    <th>Judul</th>
                    <th>Jumlah Poin</th>
                    <th>Urutan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cards as $card)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $card->year->tahun ?? '-' }}</td>
                        <td>{{ $card->title }}</td>
                        <td>{{ is_array($card->entries) ? count($card->entries) : 0 }}</td>
                        <td>{{ $card->urutan }}</td>
                        <td>
                            <a href="{{ route('admin.infographic-card.edit', $card->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.infographic-card.destroy', $card->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kartu ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada kartu.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
