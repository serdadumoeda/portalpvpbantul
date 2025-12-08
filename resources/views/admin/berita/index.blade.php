@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Kelola Berita</h3>
    <a href="{{ route('admin.berita.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Berita</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <table class="table table-hover table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Judul Berita</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($berita as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <img src="{{ $item->gambar_utama }}" width="80" class="rounded">
                    </td>
                    <td>{{ $item->judul }}</td>
                    <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ \App\Models\Berita::categories()[$item->kategori] ?? $item->kategori }}</span></td>
                    <td>{{ optional($item->published_at)->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.berita.edit', $item->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.berita.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        {{ $berita->links() }}
    </div>
</div>
@endsection
