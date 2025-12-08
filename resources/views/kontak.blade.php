@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-5 mb-4">
            <h2 class="fw-bold text-primary mb-4">Hubungi Kami</h2>
            <div class="card border-0 shadow-sm h-100 bg-primary text-white">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="fas fa-building me-2"></i> PVP BANTUL</h5>
                    <p class="mb-3"><i class="fas fa-map-marker-alt me-3"></i> Jl. Parangtritis Km 10, Bantul, DI Yogyakarta.</p>
                    <p class="mb-3"><i class="fas fa-phone me-3"></i> (0274) 367123</p>
                    <p class="mb-3"><i class="fas fa-envelope me-3"></i> info@bpvpbantul.kemnaker.go.id</p>
                    <div class="mt-4">
                        <h6 class="fw-bold">Jam Operasional:</h6>
                        <ul class="list-unstyled small">
                            <li>Senin - Kamis: 07.30 - 16.00 WIB</li>
                            <li>Jumat: 07.30 - 16.30 WIB</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <h3 class="fw-bold mb-3">Kirim Pesan / Pengaduan</h3>
            <p class="text-muted mb-4">Masukan dan saran Anda sangat berarti bagi kemajuan pelayanan kami.</p>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('kontak.store') }}" method="POST" class="card p-4 border-0 shadow-sm">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control bg-light" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control bg-light" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Subjek</label>
                    <input type="text" name="subjek" class="form-control bg-light" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Isi Pesan</label>
                    <textarea name="pesan" rows="5" class="form-control bg-light" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary px-4 py-2"><i class="fas fa-paper-plane me-2"></i> Kirim Pesan</button>
            </form>
        </div>
    </div>
</div>
@endsection
