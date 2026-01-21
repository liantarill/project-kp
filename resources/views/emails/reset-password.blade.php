<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Atur Ulang Kata Sandi</title>
</head>

<body style="margin:0; padding:0; background-color:#f8fafc; font-family: Arial, Helvetica, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding:40px 0;">
                <table width="570" cellpadding="0" cellspacing="0"
                    style="background:#ffffff; border-radius:6px; padding:32px;">

                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding-bottom:24px;">
                            <h1 style="margin:0; font-size:24px; color:#1f2937;">
                                {{ config('app.name') }}
                            </h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="color:#374151; font-size:16px; line-height:1.6;">
                            <p>Halo!</p>

                            <p>
                                Anda menerima email ini karena kami menerima permintaan
                                untuk mengatur ulang kata sandi akun Anda.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" style="margin:30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $url }}"
                                            style="
                                               background-color:#3b82f6;
                                               color:#ffffff;
                                               padding:12px 24px;
                                               text-decoration:none;
                                               border-radius:4px;
                                               font-weight:bold;
                                               display:inline-block;
                                           ">
                                            Atur Ulang Kata Sandi
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p>
                                Tautan pengaturan ulang kata sandi ini akan kedaluwarsa dalam
                                <strong>{{ config('auth.passwords.users.expire') }} menit</strong>.
                            </p>

                            <p>
                                Jika Anda tidak merasa mengajukan permintaan pengaturan ulang kata sandi,
                                abaikan email ini dan tidak perlu melakukan tindakan apa pun.
                            </p>

                            <p style="margin-top:32px;">
                                Hormat kami,<br>
                                {{ config('app.name') }}
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding-top:30px; font-size:12px; color:#9ca3af; text-align:center;">
                            © {{ date('Y') }} {{ config('app.name') }}. Seluruh hak cipta dilindungi.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
