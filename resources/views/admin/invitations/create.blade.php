@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Buat Undangan Baru</h2>
        <a href="{{ route('admin.invitations.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.invitations.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Peran</label>
                    <select name="role_id" class="form-select">
                        <option value="">(Tidak spesifik)</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') === $role->id ? 'selected' : '' }}>
                                {{ $role->label ?? ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kadaluarsa undangan</label>
                    @php
                        $expiresAt = old('expires_at', now()->addDays(7)->format('Y-m-d\TH:i'));
                    @endphp
                    <input type="datetime-local" name="expires_at" value="{{ $expiresAt }}" class="form-control">
                    @error('expires_at')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Catatan (opsional)</label>
                    <textarea name="message" rows="3" class="form-control">{{ old('message') }}</textarea>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Kirim undangan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
