<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Undangan akun Satpel PVP Bantul</title>
</head>
<body>
    <p>Halo,</p>
    <p>Anda diundang untuk mengakses panel admin Satpel PVP Bantul dengan peran <strong>{{ $invitation->role?->label ?? 'Pengguna' }}</strong>.</p>
    <p>Silakan klik tautan berikut untuk menyelesaikan pendaftaran:</p>
    <p><a href="{{ route('invite.show', $token) }}">{{ route('invite.show', $token) }}</a></p>
    @if($message)
        <p>{{ $message }}</p>
    @endif
    <p>Tautan ini akan hilang setelah digunakan atau setelah {{ $invitation->expires_at?->translatedFormat('d M Y H:i') ?? 'waktu kadaluarsa' }}.</p>
</body>
</html>
