<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Tracer Study</title>
</head>
<body>
    <p>Halo {{ $tracer->full_name }},</p>
    <p>Terima kasih telah mengirimkan Tracer Study Alumni Satpel PVP Bantul. Berikut rangkuman data yang kami terima:</p>
    <ul>
        <li><strong>Nomor Alumni:</strong> {{ $tracer->alumni_number }}</li>
        <li><strong>Program:</strong> {{ $tracer->program_name ?? 'Belum terdaftar' }}</li>
        <li><strong>Status:</strong> {{ ucfirst($tracer->status) }}</li>
    </ul>
    <p>Kami akan menghubungi Anda jika dibutuhkan klarifikasi lebih lanjut.</p>
    <p>Salam,<br>Satpel PVP Bantul</p>
</body>
</html>
