<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .otp-box {
            background: #f4f4f4;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin: 20px 0;
        }

        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #4CAF50;
            letter-spacing: 5px;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Reset Password</h2>

        @if ($userName)
            <p>Halo {{ $userName }},</p>
        @else
            <p>Halo,</p>
        @endif

        <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>

        <div class="otp-box">
            <p>Kode OTP Anda adalah:</p>
            <div class="otp-code">{{ $otp }}</div>
        </div>

        <p>Kode OTP ini akan kadaluarsa dalam <strong>5 menit</strong>.</p>

        <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>

        <div class="footer">
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>

</html>
