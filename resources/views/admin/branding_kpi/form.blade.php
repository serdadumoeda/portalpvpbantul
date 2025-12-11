@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">{{ $report->exists ? 'Edit' : 'Tambah' }} Laporan KPI Branding</h3>
        <p class="text-muted mb-0">Isi capaian KPI branding per bulan.</p>
    </div>
    <a href="{{ route('admin.branding-kpi.index') }}" class="btn btn-secondary">Kembali</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Form belum valid.</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $action }}" method="POST" novalidate>
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body row g-3">
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="month" class="form-select @error('month') is-invalid @enderror" required>
                    @foreach(range(1,12) as $month)
                        <option value="{{ $month }}" {{ old('month', $report->month) == $month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
                @error('month') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <input type="number" name="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year', $report->year ?? now()->year) }}" required>
                @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Rapat</label>
                <input type="date" name="meeting_date" class="form-control @error('meeting_date') is-invalid @enderror" value="{{ old('meeting_date', optional($report->meeting_date)->format('Y-m-d')) }}">
                @error('meeting_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Evidensi (tautan)</label>
                <input type="url" name="evidence_link" class="form-control @error('evidence_link') is-invalid @enderror" value="{{ old('evidence_link', $report->evidence_link) }}">
                @error('evidence_link') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Disusun Oleh</label>
                <input type="text" name="reported_by" class="form-control @error('reported_by') is-invalid @enderror" value="{{ old('reported_by', $report->reported_by) }}">
                @error('reported_by') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Disetujui Oleh</label>
                <input type="text" name="approved_by" class="form-control @error('approved_by') is-invalid @enderror" value="{{ old('approved_by', $report->approved_by) }}">
                @error('approved_by') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    @php
        $indicatorGroups = collect($indicatorDefinitions)->groupBy('category');
    @endphp
    @foreach($indicatorGroups as $category => $indicators)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 text-uppercase">{{ $category }}</h5>
            </div>
            <div class="card-body row g-3">
                @foreach($indicators as $key => $indicator)
                    <div class="col-md-3">
                        <label class="form-label fw-bold">{{ $indicator['label'] }}</label>
                        <div class="mb-2">
                            <small class="text-muted">Data bulan ini</small>
                            <input type="number" step="0.01" name="{{ $key }}_current" class="form-control @error($key.'_current') is-invalid @enderror" value="{{ old($key.'_current', $report->{$key.'_current'}) }}">
                            @error($key.'_current') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Data bulan lalu</small>
                            <input type="number" step="0.01" name="{{ $key }}_previous" class="form-control @error($key.'_previous') is-invalid @enderror" value="{{ old($key.'_previous', $report->{$key.'_previous'}) }}">
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Target</small>
                            <input type="number" step="0.01" name="{{ $key }}_target" class="form-control @error($key.'_target') is-invalid @enderror" value="{{ old($key.'_target', $report->{$key.'_target'}) }}">
                        </div>
                        <div>
                            <small class="text-muted">Catatan</small>
                            <textarea name="{{ $key }}_notes" rows="2" class="form-control">{{ old($key.'_notes', $report->{$key.'_notes'}) }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Evaluasi Kejuruan Sepi Peminat</h5>
        </div>
        <div class="card-body">
            <div id="low-interest-list">
                @php
                    $items = old('low_interest_items', $report->lowInterestItems->toArray() ?: [['program' => '', 'issue' => '', 'action_plan' => '']]);
                @endphp
                @foreach($items as $index => $item)
                    <div class="row g-3 mb-3 low-interest-item">
                        <div class="col-md-4">
                            <label class="form-label">Kejuruan</label>
                            <input type="text" name="low_interest_items[{{ $index }}][program]" class="form-control" value="{{ $item['program'] ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Masalah</label>
                            <input type="text" name="low_interest_items[{{ $index }}][issue]" class="form-control" value="{{ $item['issue'] ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Rencana Aksi</label>
                            <input type="text" name="low_interest_items[{{ $index }}][action_plan]" class="form-control" value="{{ $item['action_plan'] ?? '' }}">
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" id="addLowInterest"><i class="fas fa-plus"></i> Tambah evaluasi</button>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <label class="form-label">Catatan Rapat</label>
            <textarea name="meeting_notes" rows="4" class="form-control">{{ old('meeting_notes', $report->meeting_notes) }}</textarea>
        </div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Simpan Laporan</button>
    </div>
</form>

@push('scripts')
<script>
document.getElementById('addLowInterest').addEventListener('click', () => {
    const container = document.getElementById('low-interest-list');
    const index = container.querySelectorAll('.low-interest-item').length;
    const template = `
        <div class="row g-3 mb-3 low-interest-item">
            <div class="col-md-4">
                <label class="form-label">Kejuruan</label>
                <input type="text" name="low_interest_items[${index}][program]" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Masalah</label>
                <input type="text" name="low_interest_items[${index}][issue]" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Rencana Aksi</label>
                <input type="text" name="low_interest_items[${index}][action_plan]" class="form-control">
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', template);
});
</script>
@endpush
@endsection
