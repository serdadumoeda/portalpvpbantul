@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>{{ $embed->exists ? 'Edit' : 'Tambah' }} Embed</h3>
    <a href="{{ route('admin.infographic-embed.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ $action }}" method="POST">
            @csrf
            @if($method === 'PUT')
                @method('PUT')
            @endif
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tahun</label>
                    <select name="infographic_year_id" class="form-select" required>
                        <option value="">Pilih Tahun</option>
                        @foreach($years as $id => $label)
                            <option value="{{ $id }}" {{ old('infographic_year_id', $embed->infographic_year_id) == $id ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Judul</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $embed->title) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tinggi (px)</label>
                    <input type="number" name="height" class="form-control" value="{{ old('height', $embed->height ?? 600) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">URL Embed Google Looker</label>
                    <input type="text" name="url" class="form-control" value="{{ old('url', $embed->url) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $embed->urutan ?? 0) }}">
                </div>
            </div>
            <div class="mt-4">
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
