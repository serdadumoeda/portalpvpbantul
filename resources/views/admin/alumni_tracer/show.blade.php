@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Detail Tracer Alumni</h3>
        <p class="text-muted mb-0">{{ $alumni_tracer->full_name }}</p>
    </div>
    <div class="d-flex gap-2">
        @if(! $alumni_tracer->is_verified)
            <form action="{{ route('admin.alumni-tracer.verify', $alumni_tracer) }}" method="POST">
                @csrf
                @method('PATCH')
                <button class="btn btn-success">Verifikasi</button>
            </form>
        @endif
        <a href="{{ route('admin.alumni-tracer.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">Data Pribadi</div>
            <div class="card-body">
                <p class="mb-1"><strong>Email:</strong> {{ $alumni_tracer->email ?? '-' }}</p>
                <p class="mb-1"><strong>Telepon:</strong> {{ $alumni_tracer->phone ?? '-' }}</p>
                <p class="mb-1"><strong>Program:</strong> {{ $alumni_tracer->program_name ?? optional($alumni_tracer->program)->judul ?? '-' }}</p>
                <p class="mb-1"><strong>Tahun Lulus:</strong> {{ $alumni_tracer->graduation_year ?? '-' }}</p>
                <p class="mb-0"><strong>Batch:</strong> {{ $alumni_tracer->training_batch ?? '-' }}</p>
                <p class="mb-0"><strong>Status Verifikasi:</strong> {{ $alumni_tracer->is_verified ? 'Terverifikasi' : 'Belum' }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">Status Saat Ini</div>
            <div class="card-body">
                <p class="mb-1"><strong>Status:</strong> {{ ucfirst($alumni_tracer->status) }}</p>
                <p class="mb-1"><strong>Perusahaan:</strong> {{ $alumni_tracer->company_name ?? '-' }}</p>
                <p class="mb-1"><strong>Jabatan:</strong> {{ $alumni_tracer->job_title ?? '-' }}</p>
                <p class="mb-1"><strong>Sektor:</strong> {{ $alumni_tracer->industry_sector ?? '-' }}</p>
                <p class="mb-0"><strong>Gaji:</strong> {{ $alumni_tracer->salary_range ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-bold">Feedback</div>
    <div class="card-body">
        <p class="mb-1"><strong>Kepuasan:</strong> {{ $alumni_tracer->satisfaction_rating ?? '-' }}</p>
        <p class="mb-0"><strong>Masukan:</strong> {{ $alumni_tracer->feedback ?? '-' }}</p>
    </div>
</div>
@endsection
