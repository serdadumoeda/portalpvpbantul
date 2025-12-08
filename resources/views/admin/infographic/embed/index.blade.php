@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Embed Dashboard</h3>
        <p class="text-muted mb-0">Masukkan tautan Google Looker/Looker Studio untuk tiap tahun.</p>
    </div>
    <a href="{{ route('admin.infographic-embed.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah Embed</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tahun</th>
                    <th>Judul</th>
                    <th>URL</th>
                    <th>Tinggi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($embeds as $embed)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $embed->year->tahun ?? '-' }}</td>
                        <td>{{ $embed->title }}</td>
                        <td class="text-truncate" style="max-width:250px;">{{ $embed->url }}</td>
                        <td>{{ $embed->height }} px</td>
                        <td>
                            <a href="{{ route('admin.infographic-embed.edit', $embed->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.infographic-embed.destroy', $embed->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus embed ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada embed dashboard.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
