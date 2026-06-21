<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Akun Psikolog MindHaven Disetujui</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f7fa; padding: 30px; color: #1e293b;">

    <div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 16px; padding: 30px; border: 1px solid #e2e8f0;">

        <h2 style="color: #01588E; margin-top: 0;">
            Akun Psikolog MindHaven Telah Disetujui
        </h2>

        <p>Halo, <strong>{{ $psikolog->nama_lengkap }}</strong>.</p>

        <p>
            Selamat, akun psikolog Anda telah diverifikasi dan disetujui oleh admin MindHaven.
        </p>

        <p>Silakan login melalui halaman berikut:</p>

        <p>
            <a href="{{ route('backend.login') }}" style="color: #01588E; font-weight: bold;">
                {{ route('backend.login') }}
            </a>
        </p>

        <div style="background: #f1f5f9; border-radius: 12px; padding: 18px; margin: 20px 0;">
            <p style="margin: 0 0 10px 0;"><strong>Data Login:</strong></p>

            <p style="margin: 0;">
                Email: <strong>{{ $psikolog->user->email }}</strong><br>
                Password: <strong>{{ $plainPassword }}</strong>
            </p>
        </div>

        <p>
            Setelah berhasil login, silakan gunakan akun ini untuk mengakses dashboard psikolog MindHaven.
        </p>

        <p style="margin-top: 30px;">
            Terima kasih,<br>
            <strong>Admin MindHaven</strong>
        </p>

    </div>

</body>
</html>