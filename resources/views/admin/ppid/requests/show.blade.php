@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Detail Permohonan</h4>
        <small class="text-muted">Permohonan informasi publik oleh {{ $ppid_request->nama }}</small>
    </div>
    <a href="{{ route('admin.ppid-request.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <h6 class="text-muted">Nama</h6>
                <p class="fw-semibold">{{ $ppid_request->nama }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted">Nomor Identitas</h6>
                <p class="fw-semibold">{{ $ppid_request->nomor_identitas }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted">NPWP</h6>
                <p class="fw-semibold">{{ $ppid_request->npwp ?: '-' }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted">Pekerjaan</h6>
                <p class="fw-semibold">{{ $ppid_request->pekerjaan ?: '-' }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted">Jenis Pemohon</h6>
                <p class="fw-semibold">{{ $ppid_request->jenis_pemohon ?: '-' }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted">Alamat</h6>
                <p class="fw-semibold">{{ $ppid_request->alamat ?: '-' }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted">No HP</h6>
                <p class="fw-semibold">{{ $ppid_request->no_hp ?: '-' }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted">Email</h6>
                <p class="fw-semibold">{{ $ppid_request->email ?: '-' }}</p>
            </div>
        </div>
        <hr>
        <h6 class="text-muted">Informasi yang Dimohonkan</h6>
        <p>{{ $ppid_request->informasi_dimohon }}</p>
        <h6 class="text-muted">Tujuan Penggunaan Informasi</h6>
        <p>{{ $ppid_request->tujuan_penggunaan ?: '-' }}</p>
        <h6 class="text-muted">Cara Memperoleh Informasi</h6>
        <p>{{ $ppid_request->cara_memperoleh ?: '-' }}</p>

        @if($ppid_request->tanda_tangan)
            <hr>
            <h6 class="text-muted">Tanda Tangan</h6>
            <a href="{{ asset($ppid_request->tanda_tangan) }}" target="_blank" class="btn btn-outline-primary btn-sm">Lihat File</a>
        @endif
    </div>
</div>
@endsection
