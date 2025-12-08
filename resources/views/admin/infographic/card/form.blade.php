@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>{{ $card->exists ? 'Edit' : 'Tambah' }} Kartu</h3>
    <a href="{{ route('admin.infographic-card.index') }}" class="btn btn-secondary">Kembali</a>
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
                            <option value="{{ $id }}" {{ old('infographic_year_id', $card->infographic_year_id) == $id ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Judul Kartu</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $card->title) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $card->urutan ?? 0) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Daftar Poin (pisahkan tiap baris)</label>
                    <textarea name="entries" rows="5" class="form-control">{{ old('entries', isset($card->entries) ? implode("\n", (array) $card->entries) : '') }}</textarea>
                </div>
            </div>
            <div class="mt-4">
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
