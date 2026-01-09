<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Satpel PVP Bantul</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #003366; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { width: 400px; border: none; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="card shadow-lg p-4">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-primary">LUPA PASSWORD</h4>
            <p class="text-muted small">Reset password dikelola langsung di SIAP Kerja.</p>
        </div>

        <div class="alert alert-info text-center small">Silakan gunakan tombol di bawah untuk login dengan SIAP Kerja.</div>
        <a href="{{ route('sso.siapkerja.redirect') }}" class="btn btn-primary w-100 py-2">Login dengan SIAP Kerja</a>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none small">Kembali ke login</a>
        </div>
    </div>
</body>
</html>
