<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Boarding Pass</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        body {
            font-family: 'Poppins', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f4f8;
        }

        .boarding-pass {
            max-width: 700px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            background-color: #ffffff;
        }

        .header-flight-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px;
            background: linear-gradient(135deg, #0056b3, #0099ff);
            color: white;
        }

        .header-flight-info .from,
        .header-flight-info .to {
            width: 45%;
        }

        .header-flight-info .label {
            font-size: 12px;
            letter-spacing: 2px;
            opacity: 0.8;
            margin-bottom: 4px;
        }

        .header-flight-info .airport {
            font-size: 18px;
            font-weight: 700;
            line-height: 1.3;
        }

        .header-flight-info .time {
            font-size: 13px;
            opacity: 0.85;
            margin-top: 4px;
        }

        .arrow {
            font-size: 28px;
            color: rgba(255,255,255,0.7);
        }

        .details {
            display: flex;
            justify-content: space-between;
            padding: 24px;
            border-bottom: 1px dashed #ddd;
        }

        .details div {
            width: 48%;
        }

        .info-label {
            font-size: 11px;
            letter-spacing: 1px;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 600;
            color: #222;
            margin-bottom: 14px;
        }

        .seats-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .seats-list li {
            font-size: 14px;
            font-weight: 600;
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
            font-size: 12px;
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
            font-size: 13px;
            color: rgba(255,255,255,0.85);
            letter-spacing: 0.5px;
        }
    </style>
</head>

<body>
    <div class="boarding-pass">
        <div class="header-flight-info">
            <div class="from">
                <div class="label">FROM</div>
                <div class="airport">{{ $transaction->flight->segments->first()->airport->name }}</div>
                <div class="time">{{ $transaction->flight->segments->first()->time->format('d F Y H:i') }}</div>
            </div>
            <div class="arrow">✈</div>
            <div class="to">
                <div class="label">TO</div>
                <div class="airport">{{ $transaction->flight->segments->last()->airport->name }}</div>
                <div class="time">{{ $transaction->flight->segments->last()->time->format('d F Y H:i') }}</div>
            </div>
        </div>

        <div class="details">
            <div>
                <div class="info-label">Passenger Name</div>
                <div class="info-value">{{ $transaction->name }}</div>

                <div class="info-label">Transaction Code</div>
                <div class="info-value">{{ $transaction->code }}</div>

                <div class="info-label">Flight</div>
                <div class="info-value">{{ $transaction->flight->flight_number }}</div>

                <div class="info-label">Class</div>
                <div class="info-value">{{ \Str::ucfirst($transaction->class->class_type) }}</div>
            </div>
            <div>
                <div class="info-label">Seats</div>
                <ul class="seats-list">
                    @foreach ($transaction->passengers as $passenger)
                        <li>{{ $passenger->name }} — {{ $passenger->seat->name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="barcode">
            <img class="qr-code" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $transaction->code }}" alt="QR Code">
            <div class="qr-label">SCAN TO VERIFY</div>
        </div>

        <div class="footer">
            <p>Please present this boarding pass at the airport. Safe travels! ✈</p>
        </div>
    </div>
</body>

</html>