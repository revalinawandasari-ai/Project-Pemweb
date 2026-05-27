<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Boarding Pass</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f4f8;
        }

        .boarding-pass {
            max-width: 700px;
            border-radius: 16px;
            overflow: hidden;
            background-color: #ffffff;
        }

        .header-flight-info {
            width: 100%;
            background: #0056b3;
            color: white;
            padding: 0;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            padding: 20px 24px;
            vertical-align: top;
            color: white;
            width: 50%;
        }

        .label {
            font-size: 11px;
            letter-spacing: 2px;
            opacity: 0.8;
            margin-bottom: 4px;
        }

        .airport {
            font-size: 16px;
            font-weight: bold;
            line-height: 1.3;
        }

        .time {
            font-size: 12px;
            opacity: 0.85;
            margin-top: 4px;
        }

        .arrow-cell {
            text-align: center;
            font-size: 24px;
            color: rgba(255,255,255,0.7);
            width: 10%;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            padding: 24px;
        }

        .details-table td {
            padding: 6px 24px;
            vertical-align: top;
            width: 50%;
        }

        .info-label {
            font-size: 10px;
            letter-spacing: 1px;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 14px;
            font-weight: bold;
            color: #222;
            margin-bottom: 12px;
        }

        .seats-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .seats-list li {
            font-size: 13px;
            font-weight: bold;
            color: #222;
            padding: 4px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .barcode {
            text-align: center;
            padding: 24px;
            background-color: #f8f9fa;
        }

        img.qr-code {
            width: 130px;
            height: 130px;
            border: 4px solid #0056b3;
            border-radius: 8px;
            padding: 4px;
            background: white;
        }

        .qr-label {
            font-size: 11px;
            color: #999;
            margin-top: 8px;
            letter-spacing: 1px;
        }

        .footer {
            padding: 16px 24px;
            background-color: #0056b3;
            text-align: center;
        }

        .footer p {
            margin: 0;
            font-size: 12px;
            color: white;
        }

        .divider {
            border-top: 1px dashed #ddd;
            margin: 0 24px;
        }
    </style>
</head>

<body>
    <div class="boarding-pass">

        <div class="header-flight-info">
            <table class="header-table">
                <tr>
                    <td>
                        <div class="label">FROM</div>
                        <div class="airport">{{ $transaction->flight->segments->first()->airport->name }}</div>
                        <div class="time">{{ $transaction->flight->segments->first()->time->format('d F Y H:i') }}</div>
                    </td>
                    <td class="arrow-cell">--></td>
                    <td>
                        <div class="label">TO</div>
                        <div class="airport">{{ $transaction->flight->segments->last()->airport->name }}</div>
                        <div class="time">{{ $transaction->flight->segments->last()->time->format('d F Y H:i') }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <table class="details-table">
            <tr>
                <td>
                    <div class="info-label">Passenger Name</div>
                    <div class="info-value">{{ $transaction->name }}</div>

                    <div class="info-label">Transaction Code</div>
                    <div class="info-value">{{ $transaction->code }}</div>

                    <div class="info-label">Flight</div>
                    <div class="info-value">{{ $transaction->flight->flight_number }}</div>

                    <div class="info-label">Class</div>
                    <div class="info-value">{{ \Str::ucfirst($transaction->class->class_type) }}</div>
                </td>
                <td>
                    <div class="info-label">Seats</div>
                    <ul class="seats-list">
                        @foreach ($transaction->passengers as $passenger)
                            <li>{{ $passenger->name }} — {{ $passenger->seat->name }}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
        </table>

        <div class="divider"></div>

        <div class="barcode">
            <img class="qr-code" src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
            <div class="qr-label">SCAN TO VERIFY</div>
        </div>

        <div class="footer">
            <p>Please present this boarding pass at the airport. Safe travels!</p>
        </div>

    </div>
</body>

</html>