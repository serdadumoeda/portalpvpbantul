<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Satpel PVP Bantul</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #003366; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card { width: 400px; border: none; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="card shadow-lg p-4">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-primary">ADMIN LOGIN</h4>
            <p class="text-muted small">Silakan masuk untuk mengelola website</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger text-center small p-2">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <input type="email" name="email" class="form-control" placeholder="admin@bpvp.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="********" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">MASUK</button>
        </form>
        
        <div class="text-center mt-3">
            <a href="{{ route('home') }}" class="text-decoration-none small">Kembali ke Website Utama</a>
        </div>
    </div>
</body>
</html>
