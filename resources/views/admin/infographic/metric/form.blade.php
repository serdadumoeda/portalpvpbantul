@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>{{ $metric->exists ? 'Edit' : 'Tambah' }} Metric</h3>
    <a href="{{ route('admin.infographic-metric.index') }}" class="btn btn-secondary">Kembali</a>
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
                            <option value="{{ $id }}" {{ old('infographic_year_id', $metric->infographic_year_id) == $id ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Label</label>
                    <input type="text" name="label" class="form-control" value="{{ old('label', $metric->label) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nilai</label>
                    <input type="text" name="value" class="form-control" value="{{ old('value', $metric->value) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $metric->urutan ?? 0) }}">
                </div>
            </div>
            <div class="mt-4">
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
