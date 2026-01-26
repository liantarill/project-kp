<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Verifikasi Alamat Email</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap");
    </style>
</head>

<body
    style="margin:0; padding:0; background-color:#f9fafb; font-family: 'Plus Jakarta Sans', 'Inter', Arial, Helvetica, sans-serif; color:#1f2937; -webkit-font-smoothing: antialiased;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f9fafb; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#ffffff; border-radius:16px; padding:48px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); border: 1px solid #f3f4f6;">

                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding-bottom:32px;">
                            <h1
                                style="margin:0; font-size:24px; font-weight:700; color:#111827; letter-spacing: -0.025em;">
                                {{ config('app.name') }}
                            </h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="color:#4b5563; font-size:16px; line-height:1.625;">
                            <p style="margin-bottom: 24px; font-weight: 500;">Halo!</p>

                            <p style="margin-bottom: 24px; color: #4b5563;">
                                Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda.
                            </p>

                            <!-- Button Area -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin:32px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $url }}"
                                            style="
                                               background-color:#065f46;
                                               color:#ffffff;
                                               padding:14px 32px;
                                               text-decoration:none;
                                               border-radius:10px;
                                               font-weight:600;
                                               display:inline-block;
                                               box-shadow: 0 4px 6px -1px rgba(6, 95, 70, 0.3), 0 2px 4px -1px rgba(6, 95, 70, 0.06);
                                               font-size: 15px;
                                               letter-spacing: 0.01em;
                                           ">
                                            Verifikasi Alamat Email
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin-bottom: 24px;">
                                Jika Anda tidak membuat akun, tidak ada tindakan lebih lanjut yang diperlukan.
                            </p>

                            <p
                                style="margin-top:40px; border-top: 1px solid #e5e7eb; padding-top: 24px; color: #6b7280; font-size: 15px;">
                                <span style="display:block; font-weight:600; color:#374151; margin-bottom: 4px;">Hormat
                                    kami,</span>
                                {{ config('app.name') }}
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="padding-top:32px; font-size:12px; color:#9ca3af; text-align:center; font-family: 'Inter', sans-serif;">
                            <p style="margin:0;">© {{ date('Y') }} {{ config('app.name') }}. Seluruh hak cipta
                                dilindungi.</p>
                        </td>
                    </tr>

                </table>

                <!-- Sub Footer for small print if needed -->
                <table width="600" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" style="padding-top: 24px;">
                            <p style="font-size: 12px; color: #9ca3af; text-align: center; max-width: 400px; margin: 0 auto;">
                                Jika Anda mengalami masalah saat menekan tombol "Verifikasi Alamat Email", salin dan
                                tempel URL di bawah ini ke browser web Anda: <br>
                                <a href="{{ $url }}" style="color: #065f46; word-break: break-all;">{{ $url }}</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
