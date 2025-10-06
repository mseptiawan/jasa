<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Seller Disetujui</title>
</head>

<body style="margin:0;padding:0;background-color:#f4f6f8;font-family:Arial,Helvetica,sans-serif;color:#333;">

    <table width="100%"
           cellpadding="0"
           cellspacing="0"
           role="presentation">
        <tr>
            <td align="center"
                style="padding:30px 10px;">
                <table width="600"
                       cellpadding="0"
                       cellspacing="0"
                       style="background:#ffffff;border-radius:8px;overflow:hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="background-color:#2b3cd7;padding:18px 24px;">
                            <h1 style="color:#fff;margin:0;font-size:22px;">{{ config('app.name') }}</h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:30px;">
                            <p style="font-size:16px;">Halo <strong>{{ $user->name }}</strong>,</p>

                            <p style="font-size:15px;line-height:1.6;">
                                Selamat! 🎉<br>
                                Pengajuan kamu untuk menjadi <strong>Seller</strong> di platform
                                {{ config('app.name') }} telah <strong>disetujui</strong> oleh tim admin kami.
                            </p>

                            <p style="font-size:15px;line-height:1.6;">
                                Sekarang kamu dapat:
                            </p>
                            <ul style="font-size:15px;line-height:1.6;margin:10px 0 20px 20px;">
                                <li>Membuat dan mengelola jasa kamu</li>
                                <li>Menerima pesanan dari customer</li>
                                <li>Mengatur rekening bank & pembayaran</li>
                                <li>Mengaktifkan highlight untuk promosi jasa</li>
                            </ul>

                            <div style="text-align:center;margin:24px 0;">
                                <a href="{{ $url }}"
                                   style="background-color:#2b3cd7;color:#fff;padding:12px 22px;border-radius:5px;
                                      text-decoration:none;font-size:16px;display:inline-block;">
                                    Buka Dashboard Seller
                                </a>
                            </div>

                            <p style="font-size:14px;line-height:1.6;">
                                Terima kasih sudah bergabung bersama kami. Kami harap kamu bisa tumbuh dan sukses di
                                {{ config('app.name') }}!
                            </p>

                            <p style="font-size:14px;margin-top:30px;">Salam hangat,<br>
                                <strong>Tim {{ config('app.name') }}</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#f0f2f5;padding:16px;text-align:center;font-size:12px;color:#777;">
                            <p style="margin:0;">Email ini dikirim otomatis oleh sistem {{ config('app.name') }}.</p>
                            <p style="margin:6px 0 0 0;">© {{ date('Y') }} {{ config('app.name') }}. Semua hak
                                dilindungi.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>
