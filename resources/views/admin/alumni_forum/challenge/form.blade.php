@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="mb-3">{{ $title }}</h4>
                    <form action="{{ $action }}" method="POST">
                        @csrf
                        @if($method !== 'POST')
                            @method($method)
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Judul Challenge</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $challenge->title) }}" required maxlength="160">
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pertanyaan</label>
                            <textarea name="question" rows="4" class="form-control" required>{{ old('question', $challenge->question) }}</textarea>
                            @error('question')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mulai</label>
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', optional($challenge->start_date)->toDateString()) }}" required>
                                @error('start_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Selesai</label>
                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', optional($challenge->end_date)->toDateString()) }}" required>
                                @error('end_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="activeSwitch" {{ old('is_active', $challenge->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activeSwitch">
                                Tandai sebagai aktif (akan menonaktifkan challenge lain)
                            </label>
                        </div>

                        <button class="btn btn-primary px-4">{{ $challenge->exists ? 'Perbarui' : 'Simpan' }}</button>
                        <a href="{{ route('admin.alumni-forum.challenge.index') }}" class="btn btn-outline-secondary ms-2">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
