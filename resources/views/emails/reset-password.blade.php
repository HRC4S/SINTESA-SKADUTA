<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Reset Password SINTESA</title>
</head>
<body style="margin:0; padding:0; background-color:#f7f9fc; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f9fc; padding: 24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="480" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                    <tr>
                        <td style="background-color:#1e3a67; padding: 20px 32px;">
                            <span style="color:#ffffff; font-size:18px; font-weight:700;">SINTESA</span>
                            <div style="color:#c7d5f0; font-size:12px;">SMK N 2 Yogyakarta</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 32px;">
                            <h2 style="margin:0 0 16px; color:#1e3a67; font-size:18px;">Permintaan Reset Password</h2>
                            <p style="color:#333333; font-size:14px; line-height:1.6; margin:0 0 16px;">
                                Halo <strong>{{ $nama }}</strong>,
                            </p>
                            <p style="color:#333333; font-size:14px; line-height:1.6; margin:0 0 16px;">
                                Kami menerima permintaan untuk mereset password akun SINTESA kamu.
                                Klik tombol di bawah ini untuk membuat password baru. Link ini hanya
                                berlaku selama <strong>{{ $expiresInMinutes }} menit</strong> dan hanya
                                bisa dipakai satu kali.
                            </p>
                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin: 24px 0;">
                                <tr>
                                    <td style="border-radius:8px; background-color:#1e3a67;">
                                        <a href="{{ $resetUrl }}"
                                           style="display:inline-block; padding: 12px 28px; color:#ffffff; text-decoration:none; font-size:14px; font-weight:600; border-radius:8px;">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="color:#666666; font-size:12px; line-height:1.6; margin:0 0 8px;">
                                Kalau tombol di atas tidak bisa diklik, salin dan buka link berikut di browser kamu:
                            </p>
                            <p style="word-break:break-all; font-size:12px; color:#1e3a67; margin:0 0 20px;">
                                {{ $resetUrl }}
                            </p>
                            <p style="color:#999999; font-size:12px; line-height:1.6; margin:0;">
                                Kalau kamu tidak merasa meminta reset password, abaikan saja email ini —
                                password akun kamu tidak akan berubah.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#f0f2f7; padding: 16px 32px; text-align:center;">
                            <span style="color:#999999; font-size:11px;">© {{ date('Y') }} SINTESA — SMK N 2 Yogyakarta</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
