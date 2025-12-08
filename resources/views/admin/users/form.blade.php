@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">{{ $title }}</h2>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>

    <div class="bg-white rounded shadow-sm p-4">
        <form action="{{ $action }}" method="POST" class="row g-3">
            @csrf
            @if($method === 'PUT')
                @method('PUT')
            @endif

            <div class="col-md-6">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Password {{ $method === 'POST' ? '' : '(kosongkan bila tidak diganti)' }}</label>
                <input type="password" name="password" class="form-control" {{ $method === 'POST' ? 'required' : '' }}>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" {{ $method === 'POST' ? 'required' : '' }}>
            </div>

            <div class="col-12">
                <label class="form-label d-block">Peran Pengguna</label>
                <div class="row">
                    @php
                        $selectedRoles = collect(old('roles', isset($user) ? $user->roles->pluck('id')->toArray() : []));
                    @endphp
                    @foreach($roles as $role)
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role-{{ $role->id }}" {{ $selectedRoles->contains($role->id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role-{{ $role->id }}">
                                    <strong>{{ $role->label ?? ucfirst($role->name) }}</strong>
                                    <small class="text-muted d-block">{{ $role->name }}</small>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('roles')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">{{ $method === 'POST' ? 'Simpan' : 'Perbarui' }}</button>
            </div>
        </form>
    </div>
@endsection
