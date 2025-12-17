<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Baru - Satpel PVP Bantul</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #003366; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { width: 420px; border: none; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="card shadow-lg p-4">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-primary">UNDANGAN BARU</h4>
            <p class="text-muted small">Selesaikan profil untuk mulai mengelola konten.</p>
        </div>

        @if(session('status'))
            <div class="alert alert-success text-center small p-2">
                {{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger text-center small p-2">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('invite.accept', $token) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">Email yang diundang</label>
                <input type="email" class="form-control" value="{{ $invitation->email }}" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Nama lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Password</label>
                <input type="password" name="password" class="form-control" required>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Konfirmasi password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">Selesaikan pendaftaran</button>
        </form>
        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none small">Kembali ke login</a>
        </div>
    </div>
</body>
</html>
