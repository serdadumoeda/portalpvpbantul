<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi 2FA - Satpel PVP Bantul</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #003366; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { width: 420px; border: none; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="card shadow-lg p-4">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-primary">AUTENTIKASI DUA LANGKAH</h4>
            <p class="text-muted small">Masukkan kode 6 digit yang dikirimkan ke email Anda.</p>
        </div>

        @if(session('status'))
            <div class="alert alert-success text-center small p-2">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('two-factor.verify') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">Kode 2FA</label>
                <input type="text" name="code" class="form-control text-center fs-4" maxlength="6" autofocus required>
                @error('code')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">Verifikasi</button>
        </form>

        <form action="{{ route('two-factor.resend') }}" method="POST" class="mt-3 text-center">
            @csrf
            <button type="submit" class="btn btn-link text-decoration-none small px-0">Kirim ulang kode</button>
        </form>
        <div class="text-center mt-2">
            <a href="{{ route('login') }}" class="text-decoration-none small">Kembali ke login</a>
        </div>
    </div>
</body>
</html>
