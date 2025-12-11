@extends('layouts.admin')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        <h5 class="mb-0">Tambah Berita Baru</h5>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Gagal menyimpan.</strong> Periksa kembali input berikut:
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Judul Berita</label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" placeholder="Masukkan judul berita (maks. 150 karakter)" maxlength="150" required>
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                        @foreach(\App\Models\Berita::categories() as $key => $label)
                            <option value="{{ $key }}" {{ old('kategori') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Penulis</label>
                    <input type="text" name="author" class="form-control @error('author') is-invalid @enderror" value="{{ old('author') }}" placeholder="Nama penulis (maks. 100 karakter)" maxlength="100">
                    @error('author') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Publikasi</label>
                    <input type="datetime-local" name="published_at" class="form-control @error('published_at') is-invalid @enderror" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}">
                    @error('published_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status Konten</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        @foreach(\App\Models\Berita::statuses() as $key => $label)
                            @if($key === \App\Models\Berita::STATUS_PUBLISHED && !auth()->user()->hasPermission('approve-content'))
                                @continue
                            @endif
                            <option value="{{ $key }}" {{ old('status', \App\Models\Berita::STATUS_DRAFT) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    <small class="text-muted">Draft untuk penyimpanan sementara, pending untuk diajukan.</small>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar Utama</label>
                <input type="file" name="gambar_utama" class="form-control @error('gambar_utama') is-invalid @enderror" accept=".jpg,.jpeg,.png,image/jpeg,image/png" data-validate-image required>
                @error('gambar_utama') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                <small class="text-muted">Format: JPG atau PNG. Maksimal 2MB.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Ringkasan Singkat</label>
                <textarea name="excerpt" rows="3" class="form-control @error('excerpt') is-invalid @enderror" placeholder="Opsional, akan otomatis dibuat jika dikosongkan.">{{ old('excerpt') }}</textarea>
                @error('excerpt') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Konten Berita</label>
                <textarea name="konten" rows="10" class="form-control @error('konten') is-invalid @enderror" placeholder="Tulis isi berita di sini..." required>{{ old('konten') }}</textarea>
                @error('konten') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            @include('admin.partials.seo-tools', ['model' => null, 'baseUrl' => url('/berita')])

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Berita</button>
            <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.querySelector('[data-validate-image]');
            if (!input) return;

            input.addEventListener('change', function () {
                const file = this.files?.[0];
                if (!file) return;

                const allowedTypes = ['image/jpeg', 'image/png'];
                const maxBytes = 2 * 1024 * 1024;

                if (!allowedTypes.includes(file.type)) {
                    alert('Format gambar harus JPG atau PNG.');
                    this.value = '';
                    return;
                }
                if (file.size > maxBytes) {
                    alert('Ukuran gambar melebihi 2MB.');
                    this.value = '';
                }
            });
        });
    </script>
@endpush
