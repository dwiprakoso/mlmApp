<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Deposit Ditolak</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .error-icon {
            font-size: 50px;
            color: #dc3545;
            margin-bottom: 15px;
        }

        .title {
            color: #dc3545;
            font-size: 24px;
            margin: 0;
        }

        .info-box {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }

        .info-item {
            margin: 10px 0;
        }

        .label {
            font-weight: bold;
            color: #333;
        }

        .value {
            color: #666;
        }

        .notice {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="error-icon">❌</div>
            <h1 class="title">Deposit Ditolak</h1>
        </div>

        <p>Halo <strong>{{ $userName }}</strong>,</p>

        <p>Mohon maaf, deposit Anda tidak dapat diproses dan telah ditolak.</p>

        <div class="info-box">
            <div class="info-item">
                <span class="label">Jumlah:</span>
                <span class="value">Rp {{ number_format($amount, 0, ',', '.') }}</span>
            </div>
            <div class="info-item">
                <span class="label">Referensi:</span>
                <span class="value">{{ $reference }}</span>
            </div>
            <div class="info-item">
                <span class="label">Tanggal:</span>
                <span class="value">{{ $date }}</span>
            </div>
        </div>

        <div class="notice">
            <strong>Catatan:</strong> Deposit dapat ditolak karena beberapa alasan seperti bukti pembayaran yang tidak
            valid, nominal yang tidak sesuai, atau informasi yang tidak lengkap.
        </div>

        <p>Silakan periksa kembali data deposit Anda dan pastikan semua informasi sudah benar. Jika Anda memerlukan
            bantuan, silakan hubungi customer service kami.</p>

        <p>Terima kasih atas pengertiannya.</p>

        <div class="footer">
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>

</html>
