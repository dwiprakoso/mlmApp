<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Deposit Berhasil Dikonfirmasi</title>
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

        .success-icon {
            font-size: 50px;
            color: #28a745;
            margin-bottom: 15px;
        }

        .title {
            color: #28a745;
            font-size: 24px;
            margin: 0;
        }

        .info-box {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
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
            <div class="success-icon">✅</div>
            <h1 class="title">Deposit Berhasil Dikonfirmasi</h1>
        </div>

        <p>Halo <strong>{{ $userName }}</strong>,</p>

        <p>Selamat! Deposit Anda telah berhasil dikonfirmasi dan saldo telah ditambahkan ke akun Anda.</p>

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

        <p>Saldo Anda telah berhasil diperbarui. Anda dapat menggunakan saldo ini untuk melakukan transaksi di platform
            kami.</p>

        <p>Terima kasih telah menggunakan layanan kami!</p>

        <div class="footer">
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>

</html>
