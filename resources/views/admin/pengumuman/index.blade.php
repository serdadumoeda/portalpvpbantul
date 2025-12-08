@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Kelola Pengumuman</h3>
    <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Buat Pengumuman</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th>Judul Pengumuman</th>
                    <th>Tanggal</th>
                    <th>Lampiran</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengumuman as $info)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $info->judul }}</td>
                    <td>{{ $info->created_at->format('d M Y') }}</td>
                    <td>
                        @if($info->file_download)
                            <a href="{{ asset($info->file_download) }}" target="_blank" class="badge bg-info text-decoration-none">
                                <i class="fas fa-download"></i> Unduh File
                            </a>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.pengumuman.edit', $info->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.pengumuman.destroy', $info->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengumuman ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection