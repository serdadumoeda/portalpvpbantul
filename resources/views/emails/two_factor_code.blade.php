<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kode 2FA</title>
</head>
<body>
    <p>Halo {{ $user->name ?? 'Pengguna' }},</p>
    <p>Kode autentikasi dua langkah Anda adalah:</p>
    <h2 style="letter-spacing:4px;">{{ $code }}</h2>
    <p>Kode ini hanya berlaku 5 menit. Jangan bagikan kode ini kepada siapapun.</p>
</body>
</html>
