@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Daftar FAQ</h4>
        <small class="text-muted">Pertanyaan dan jawaban yang ditampilkan pada halaman publik.</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('resource.faq') }}" target="_blank" class="btn btn-outline-primary btn-sm">Lihat Halaman</a>
        <a href="{{ route('admin.faq-item.create') }}" class="btn btn-primary btn-sm">Tambah FAQ</a>
    </div>
</div>

<div class="bg-white rounded shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Pertanyaan</th>
                    <th>Kategori</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $item->question }}</strong>
                            <div class="text-muted small">{{ \Illuminate\Support\Str::limit(strip_tags($item->answer), 100) }}</div>
                        </td>
                        <td>{{ $item->category->title ?? '-' }}</td>
                        <td>{{ $item->urutan }}</td>
                        <td>
                            <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $item->is_active ? 'Aktif' : 'Draft' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.faq-item.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.faq-item.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus FAQ ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada FAQ.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
