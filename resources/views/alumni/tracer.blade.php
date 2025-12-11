@extends('layouts.app')

@section('content')
<section class="py-5" style="background:linear-gradient(135deg,#0f4c75,#1b6ca8);">
    <div class="container">
        <div class="row align-items-center g-4 text-white">
            <div class="col-lg-7">
                <h1 class="fw-bold mb-3">Tracer Study Alumni</h1>
                <p class="lead">Bantu kami memetakan dampak pelatihan Satpel PVP Bantul dengan mengisi perkembangan karier Anda. Data ini digunakan untuk peningkatan kurikulum dan kerja sama industri.</p>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 shadow-lg">
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 text-dark">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Mengetahui tingkat penyerapan kerja alumni.</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Menjaga hubungan dengan mitra industri.</li>
                            <li><i class="fas fa-check text-success me-2"></i>Memvalidasi kebutuhan pelatihan baru.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="py-5">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form action="{{ route('alumni.tracer.store') }}" method="POST" class="card border-0 shadow-sm p-4">
            @csrf
            <h4 class="mb-3">Data Pribadi</h4>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" required>
                    @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nomor Identitas</label>
                    <input type="text" name="national_id" class="form-control" value="{{ old('national_id') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Program Pelatihan</label>
                    <select name="program_id" class="form-select">
                        <option value="">Pilih program</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>{{ $program->judul }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nama Program (jika tidak ada di daftar)</label>
                    <input type="text" name="program_name" class="form-control" value="{{ old('program_name') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tahun Lulus</label>
                    <input type="number" name="graduation_year" class="form-control" value="{{ old('graduation_year') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Batch/Kelas</label>
                    <input type="text" name="training_batch" class="form-control" value="{{ old('training_batch') }}">
                </div>
            </div>
            <hr class="my-4">
            <h4 class="mb-3">Status Setelah Pelatihan</h4>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        @php $statuses = ['employed' => 'Bekerja', 'entrepreneur' => 'Wirausaha', 'studying' => 'Melanjutkan Studi', 'seeking' => 'Mencari Kerja', 'other' => 'Lainnya']; @endphp
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ old('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nama Perusahaan</label>
                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="job_title" class="form-control" value="{{ old('job_title') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sektor Industri</label>
                    <input type="text" name="industry_sector" class="form-control" value="{{ old('industry_sector') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Mulai Kerja</label>
                    <input type="date" name="job_start_date" class="form-control" value="{{ old('job_start_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tipe Pekerjaan</label>
                    <input type="text" name="employment_type" class="form-control" value="{{ old('employment_type') }}" placeholder="Kontrak, Tetap, Freelance">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kisaran Gaji</label>
                    <input type="text" name="salary_range" class="form-control" value="{{ old('salary_range') }}" placeholder="contoh: 3-5 juta">
                </div>
                <div class="col-md-4 form-check mt-4 pt-2">
                    <input class="form-check-input" type="checkbox" name="continue_study" value="1" {{ old('continue_study') ? 'checked' : '' }}>
                    <label class="form-check-label">Saya melanjutkan studi</label>
                </div>
                <div class="col-md-4 form-check mt-4 pt-2">
                    <input class="form-check-input" type="checkbox" name="is_entrepreneur" value="1" {{ old('is_entrepreneur') ? 'checked' : '' }}>
                    <label class="form-check-label">Saya membangun usaha sendiri</label>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Usaha</label>
                    <input type="text" name="business_name" class="form-control" value="{{ old('business_name') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bidang Usaha</label>
                    <input type="text" name="business_sector" class="form-control" value="{{ old('business_sector') }}">
                </div>
            </div>
            <hr class="my-4">
            <h4 class="mb-3">Evaluasi Program</h4>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Kepuasan Pelatihan (1-5)</label>
                    <input type="number" name="satisfaction_rating" class="form-control" min="1" max="5" value="{{ old('satisfaction_rating') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Masukan / Rekomendasi</label>
                    <textarea name="feedback" rows="4" class="form-control">{{ old('feedback') }}</textarea>
                </div>
            </div>
            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary px-4">Kirim Form</button>
            </div>
        </form>
    </div>
</section>
@endsection
