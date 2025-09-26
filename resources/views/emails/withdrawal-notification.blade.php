<!-- resources/views/emails/deposit-notification.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Deposit Request</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .header .icon {
            font-size: 48px;
            margin-bottom: 10px;
            display: block;
        }

        .content {
            padding: 30px;
        }

        .alert-box {
            background: #e8f5e8;
            border: 1px solid #28a745;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }

        .alert-box h2 {
            color: #28a745;
            margin: 0 0 10px 0;
            font-size: 18px;
        }

        .transaction-id {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
            background: #f8f9ff;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin: 15px 0;
            border: 2px dashed #007bff;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .details-table tr {
            border-bottom: 1px solid #eee;
        }

        .details-table td {
            padding: 12px 0;
            vertical-align: top;
        }

        .details-table .label {
            font-weight: 600;
            color: #555;
            width: 40%;
        }

        .details-table .value {
            color: #333;
        }

        .amount-highlight {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
            border: 2px solid #28a745;
        }

        .user-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #007bff;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            background: #ffc107;
            color: #000;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }

        .footer .logo {
            font-weight: bold;
            color: #007bff;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .datetime {
            color: #666;
            font-size: 14px;
        }

        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 5px;
            }

            .content {
                padding: 20px 15px;
            }

            .details-table .label {
                width: 100%;
                font-weight: bold;
                padding-bottom: 5px;
            }

            .details-table .value {
                padding-top: 0;
                padding-bottom: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <div class="icon">💰</div>
            <h1>New Deposit Request</h1>
        </div>

        <div class="content">
            <div class="alert-box">
                <h2>🚨 New Deposit Alert</h2>
                <p>A new deposit request has been submitted and requires review.</p>
            </div>

            <div class="transaction-id">
                Transaction ID: {{ $transaction->reference }}
            </div>

            <div class="amount-highlight">
                IDR {{ number_format($transaction->amount, 0, ',', '.') }}
            </div>

            <div class="user-info">
                <h3 style="margin: 0 0 10px 0; color: #007bff;">👤 User Information</h3>
                <strong>Name:</strong> {{ $user->name }}<br>
                <strong>Email:</strong> {{ $user->email }}<br>
                <strong>User ID:</strong> {{ $user->id }}
            </div>

            <table class="details-table">
                <tr>
                    <td class="label">💳 Payment Method:</td>
                    <td class="value">{{ $transaction->payment_method }}</td>
                </tr>
                <tr>
                    <td class="label">📊 Status:</td>
                    <td class="value">
                        <span class="status-badge">{{ ucfirst($transaction->status) }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">🕒 Date & Time:</td>
                    <td class="value datetime">{{ $transaction->created_at->format('d/m/Y H:i:s') }} WIB</td>
                </tr>
                <tr>
                    <td class="label">🆔 Transaction Type:</td>
                    <td class="value">{{ strtoupper($transaction->type) }}</td>
                </tr>
            </table>

            <div
                style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 15px; margin: 20px 0;">
                <strong style="color: #856404;">⚠️ Action Required:</strong><br>
                Please review and approve this deposit request in the admin panel.
            </div>
        </div>

        <div class="footer">
            <div class="logo">🏰 Rich Kingdom MLM System</div>
            <p>This is an automated notification from the Rich Kingdom platform.</p>
            <p style="font-size: 12px; color: #999;">
                Generated on {{ now()->format('d/m/Y H:i:s') }} WIB
            </p>
        </div>
    </div>
</body>

</html>

<!-- resources/views/emails/withdrawal-notification.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Withdrawal Request</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #dc3545 0%, #b21e2f 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .header .icon {
            font-size: 48px;
            margin-bottom: 10px;
            display: block;
        }

        .content {
            padding: 30px;
        }

        .alert-box {
            background: #ffe6e6;
            border: 1px solid #dc3545;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }

        .alert-box h2 {
            color: #dc3545;
            margin: 0 0 10px 0;
            font-size: 18px;
        }

        .transaction-id {
            font-size: 20px;
            font-weight: bold;
            color: #dc3545;
            background: #fff5f5;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin: 15px 0;
            border: 2px dashed #dc3545;
        }

        .amount-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #dee2e6;
        }

        .amount-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .amount-row:last-child {
            border-bottom: 2px solid #28a745;
            font-weight: bold;
            font-size: 18px;
        }

        .amount-label {
            color: #555;
        }

        .amount-value {
            font-weight: bold;
        }

        .amount-value.gross {
            color: #dc3545;
        }

        .amount-value.fee {
            color: #ffc107;
        }

        .amount-value.net {
            color: #28a745;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .details-table tr {
            border-bottom: 1px solid #eee;
        }

        .details-table td {
            padding: 12px 0;
            vertical-align: top;
        }

        .details-table .label {
            font-weight: 600;
            color: #555;
            width: 40%;
        }

        .details-table .value {
            color: #333;
        }

        .user-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #dc3545;
        }

        .wallet-info {
            background: #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #6c757d;
        }

        .wallet-address {
            word-break: break-all;
            background: white;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 14px;
            margin-top: 5px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            background: #ffc107;
            color: #000;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }

        .footer .logo {
            font-weight: bold;
            color: #dc3545;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .datetime {
            color: #666;
            font-size: 14px;
        }

        .notes-section {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }

        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 5px;
            }

            .content {
                padding: 20px 15px;
            }

            .details-table .label {
                width: 100%;
                font-weight: bold;
                padding-bottom: 5px;
            }

            .details-table .value {
                padding-top: 0;
                padding-bottom: 15px;
            }

            .amount-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .amount-value {
                margin-top: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <div class="icon">💸</div>
            <h1>New Withdrawal Request</h1>
        </div>

        <div class="content">
            <div class="alert-box">
                <h2>🚨 New Withdrawal Alert</h2>
                <p>A new withdrawal request has been submitted and requires review.</p>
            </div>

            <div class="transaction-id">
                Transaction ID: {{ $transaction->reference }}
            </div>

            <div class="amount-section">
                <h3 style="margin: 0 0 15px 0; color: #dc3545;">💰 Amount Breakdown</h3>

                <div class="amount-row">
                    <span class="amount-label">Gross Amount:</span>
                    <span class="amount-value gross">IDR {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                </div>

                <div class="amount-row">
                    <span class="amount-label">Withdrawal Fee:</span>
                    <span class="amount-value fee">- IDR {{ number_format($withdrawalFee, 0, ',', '.') }}</span>
                </div>

                <div class="amount-row">
                    <span class="amount-label">Net Amount (to be transferred):</span>
                    <span class="amount-value net">IDR {{ number_format($netAmount, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="user-info">
                <h3 style="margin: 0 0 10px 0; color: #dc3545;">👤 User Information</h3>
                <strong>Name:</strong> {{ $user->name }}<br>
                <strong>Email:</strong> {{ $user->email }}<br>
                <strong>User ID:</strong> {{ $user->id }}
            </div>

            <div class="wallet-info">
                <h3 style="margin: 0 0 10px 0; color: #6c757d;">🏦 Wallet Information</h3>
                <strong>Type:</strong> {{ strtoupper($wallet->type) }}<br>
                <strong>Name:</strong> {{ $wallet->name }}<br>
                <strong>Address:</strong>
                <div class="wallet-address">{{ $wallet->address }}</div>
            </div>

            @if ($transaction->notes)
                <div class="notes-section">
                    <strong style="color: #856404;">📝 User Notes:</strong><br>
                    {{ $transaction->notes }}
                </div>
            @endif

            <table class="details-table">
                <tr>
                    <td class="label">📊 Status:</td>
                    <td class="value">
                        <span class="status-badge">{{ ucfirst($transaction->status) }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">🕒 Date & Time:</td>
                    <td class="value datetime">{{ $transaction->created_at->format('d/m/Y H:i:s') }} WIB</td>
                </tr>
                <tr>
                    <td class="label">🆔 Transaction Type:</td>
                    <td class="value">{{ strtoupper($transaction->type) }}</td>
                </tr>
            </table>

            <div
                style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 15px; margin: 20px 0;">
                <strong style="color: #856404;">⚠️ Action Required:</strong><br>
                Please review and process this withdrawal request in the admin panel.
                The net amount of <strong>IDR {{ number_format($netAmount, 0, ',', '.') }}</strong>
                should be transferred to the user's {{ strtoupper($wallet->type) }} wallet.
            </div>
        </div>

        <div class="footer">
            <div class="logo">🏰 Rich Kingdom MLM System</div>
            <p>This is an automated notification from the Rich Kingdom platform.</p>
            <p style="font-size: 12px; color: #999;">
                Generated on {{ now()->format('d/m/Y H:i:s') }} WIB
            </p>
        </div>
    </div>
</body>

</html>
