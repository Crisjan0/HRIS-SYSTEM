<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            color: #0f172a;
            font-family: Arial, sans-serif;
            padding: 24px;
            box-sizing: border-box;
        }

        .card {
            width: 100%;
            max-width: 560px;
            border: 1px solid #dbe2ea;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 18px 60px rgba(15, 23, 42, 0.10);
            overflow: hidden;
        }

        .header {
            padding: 20px 24px;
            background: #0f766e;
            color: #ffffff;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .body {
            padding: 24px;
        }

        .status {
            display: inline-block;
            margin-bottom: 16px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .status-success { background: #dcfce7; color: #166534; }
        .status-warning { background: #fef3c7; color: #92400e; }
        .status-info { background: #e0f2fe; color: #075985; }

        .message {
            margin: 0 0 20px;
            font-size: 18px;
            font-weight: 700;
            line-height: 1.4;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .field {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: #f8fafc;
            padding: 14px 16px;
        }

        .label {
            margin: 0 0 6px;
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .value {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            line-height: 1.4;
        }

        @media (max-width: 640px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>{{ $title }}</h1>
        </div>

        <div class="body">
            <span class="status status-{{ $status }}">{{ strtoupper($status) }}</span>
            <p class="message">{{ $message }}</p>

            <div class="grid">
                <div class="field">
                    <p class="label">Employee</p>
                    <p class="value">{{ trim(($locatorSlip->employee?->firstname ?? '') . ' ' . ($locatorSlip->employee?->lastname ?? '')) ?: 'N/A' }}</p>
                </div>
                <div class="field">
                    <p class="label">Date Covered</p>
                    <p class="value">{{ \Carbon\Carbon::parse($locatorSlip->date_covered)->format('M d, Y') }}</p>
                </div>
                <div class="field">
                    <p class="label">OUT Time</p>
                    <p class="value">{{ $locatorSlip->time_from ? \Carbon\Carbon::parse($locatorSlip->time_from)->format('h:i A') : 'Not recorded' }}</p>
                </div>
                <div class="field">
                    <p class="label">IN Time</p>
                    <p class="value">{{ $locatorSlip->time_to ? \Carbon\Carbon::parse($locatorSlip->time_to)->format('h:i A') : 'Not recorded' }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
