@extends('layouts.admin')
@section('content')
<h3>Kotak Masuk (Pengaduan)</h3>
<div class="card shadow-sm mt-3">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr><th>Tgl</th><th>Nama</th><th>Subjek</th><th>Pesan</th></tr>
            </thead>
            <tbody>
                @foreach($pesan as $p)
                <tr>
                    <td>{{ $p->created_at->format('d/m/Y') }}</td>
                    <td>{{ $p->nama }}<br><small class="text-muted">{{ $p->email }}</small></td>
                    <td>{{ $p->subjek }}</td>
                    <td>{{ Str::limit($p->pesan, 50) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection