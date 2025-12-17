@extends('layouts.app')

@php $currentUser = auth()->user(); @endphp

@section('title', 'Lengkapi Profil Alumni')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h1 class="fw-bold">Lengkapi Profil Alumni</h1>
                        <p class="text-muted mb-0">Informasi ini membantu kami memetakan alumni dan menyediakan program lanjutan.</p>
                    </div>
                    @if(session('success'))
                        <span class="badge bg-success">{{ session('success') }}</span>
                    @endif
                </div>
                <form action="{{ route('alumni.profile.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $currentUser->name ?? '') }}" class="form-control" required>
                            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Aktif</label>
                            <input type="email" name="email" value="{{ old('email', $currentUser->email ?? '') }}" class="form-control" required>
                            @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Nomor HP</label>
                            <input type="text" name="phone" value="{{ old('phone', $currentUser->phone ?? '') }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Technical Field / Program</label>
                            <input type="text" name="field_of_study" value="{{ old('field_of_study', $currentUser->field_of_study ?? '') }}" class="form-control">
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Tahun Lulus</label>
                            <input type="number" name="graduation_year" value="{{ old('graduation_year', $currentUser->graduation_year ?? '') }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status Pekerjaan</label>
                            <input type="text" name="employment_status" value="{{ old('employment_status', $currentUser->employment_status ?? '') }}" class="form-control">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Catatan / Portofolio Singkat</label>
                        <textarea name="notes" rows="4" class="form-control">{{ old('notes', $currentUser->notes ?? '') }}</textarea>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Verifikasi Keamanan</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-semibold">{{ $captchaQuestion ?? '' }}</span>
                            <input type="text" name="captcha_answer" class="form-control" required>
                        </div>
                        @error('captcha_answer')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="text-end mt-4">
                        <button class="btn btn-primary px-4">Kirim Profil</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
