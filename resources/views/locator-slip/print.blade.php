<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Business Form</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #fff;
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
        }

        .sheet {
            width: 194mm;
            height: 281mm;
            margin: 0 auto;
            background: #fff;
        }

        .copies-layout {
            width: 100%;
            height: 100%;
            border-collapse: separate;
            border-spacing: 7.5mm 7mm;
            table-layout: fixed;
        }

        .copies-layout > tbody > tr > td {
            width: 50%;
            height: 50%;
            padding: 0;
            vertical-align: top;
        }

        .copy-table {
            width: 100%;
            height: 125mm;
            border-collapse: collapse;
            table-layout: fixed;
            border: .45mm solid #000;
            font-size: 6.2pt;
            line-height: 1.08;
        }

        .copy-table td {
            border: .22mm solid #000;
            padding: 0;
            vertical-align: top;
        }

        .copy-wrap {
            width: 100%;
        }

        .header-cell {
            height: 17mm;
            padding: 1mm 1.4mm;
        }

        .form-header {
            display: grid;
            grid-template-columns: 15mm 1fr 16mm;
            align-items: center;
            height: 100%;
            column-gap: 1.5mm;
        }

        .logo-slot {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 1.5mm;
        }

        .form-logo {
            width: 11mm;
            max-height: 11mm;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        .form-logo.right {
            width: 13.5mm;
            max-height: 11mm;
        }

        .agency-block {
            text-align: center;
            font-size: 5.8pt;
            line-height: 1.1;
        }

        .agency-name {
            font-weight: 700;
            text-transform: uppercase;
        }

        .form-title {
            margin-top: .8mm;
            font-size: 6.8pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .01em;
        }

        .label {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 5.5pt;
            white-space: nowrap;
        }

        .label-cell {
            height: 12mm;
            text-align: center;
            vertical-align: top;
            padding-top: 1.8mm !important;
        }

        .field-cell {
            height: 8mm;
            padding: .7mm 1mm !important;
        }

        .field-label {
            display: block;
            font-size: 5.5pt;
            font-weight: 700;
            text-transform: uppercase;
            line-height: 1;
            text-align: left;
        }

        .field-value {
            display: block;
            margin-top: 1.4mm;
            text-align: center;
            font-size: 6pt;
            font-weight: 700;
            text-transform: uppercase;
            line-height: 1.12;
            overflow-wrap: anywhere;
        }

        .value {
            font-size: 6pt;
            font-weight: 700;
            text-transform: uppercase;
            overflow-wrap: anywhere;
        }

        .purpose-label-cell {
            height: 4.8mm;
            padding: .7mm 1mm !important;
            border-bottom: 0 !important;
        }

        .purpose-value {
            height: 27mm;
            vertical-align: top;
            padding: 1.2mm 1.3mm !important;
            line-height: 1.15;
            text-align: left;
            border-top: 0 !important;
        }

        .time-cell {
            height: 14mm;
            text-align: center;
            vertical-align: top;
            padding-top: 2.6mm !important;
        }

        .signature-space {
            height: 13mm;
            vertical-align: bottom;
            text-align: left;
            padding: 1.1mm 1mm !important;
            font-size: 5.8pt;
        }

        .signature-name {
            display: none;
        }

        .signature-caption {
            font-weight: 700;
            text-transform: uppercase;
        }

        .approval-cell {
            height: 25mm;
            text-align: left;
            vertical-align: bottom;
            padding: 1mm 1mm 1.5mm !important;
            font-size: 5.6pt;
        }

        .approval-name {
            margin-top: 15mm;
            min-height: 3mm;
            border-bottom: .22mm solid #000;
            display: flex;
            align-items: end;
            justify-content: center;
            padding-bottom: .25mm;
            font-size: 5.5pt;
            font-weight: 700;
            text-transform: uppercase;
        }

        .approval-role {
            padding-top: .45mm;
            text-align: center;
            font-size: 5.4pt;
            font-weight: 700;
            text-transform: uppercase;
        }

        .approval-position {
            text-align: center;
            font-size: 5.2pt;
            font-weight: 700;
        }

        .copy-label {
            height: 4mm;
            text-align: center;
            font-size: 7.5pt;
            font-weight: 400;
            text-transform: uppercase;
            line-height: 4mm;
        }

        @media print {
            .sheet {
                width: 194mm;
                height: 281mm;
                margin: 0;
            }

            .copies-layout {
                border-spacing: 7.5mm 7mm;
            }
        }
    </style>
</head>
<body>
    @php
        $employee = $locatorSlip->employee;
        $middleInitial = $employee?->middlename ? mb_substr($employee->middlename, 0, 1) . '. ' : '';
        $employeeName = trim(($employee->firstname ?? '') . ' ' . $middleInitial . ($employee->lastname ?? '') . ' ' . ($employee->suffix ?? ''));
        $division = $employee?->division ?: 'N/A';
        $office = 'DMW RO XI';
        $chiefName = $locatorSlip->approved_by_chief_name ?: 'LOUIE JAY C. LOSARIA';
        $regionalDirectorName = $locatorSlip->approved_by_regional_director_name ?: 'MARIA CAROLINA B. AGDAMAG';
        $dateCovered = \Carbon\Carbon::parse($locatorSlip->date_covered)->format('F d, Y');
        $timeFrom = \Carbon\Carbon::parse($locatorSlip->time_from)->format('h:i A');
        $timeTo = \Carbon\Carbon::parse($locatorSlip->time_to)->format('h:i A');
        $copyLabels = ['EMPLOYEE FILE', 'HR/FAD FILE', 'EMPLOYEE FILE', 'HR/FAD FILE'];
    @endphp

    <main class="sheet">
        <table class="copies-layout" aria-label="Official Business Form copies">
            <tbody>
                @foreach(array_chunk($copyLabels, 2) as $copyRow)
                    <tr>
                        @foreach($copyRow as $copyLabel)
                            <td>
                                <div class="copy-wrap">
                                <table class="copy-table" aria-label="{{ $copyLabel }} Official Business Form">
                                    <colgroup>
                                        <col style="width: 25%">
                                        <col style="width: 25%">
                                        <col style="width: 25%">
                                        <col style="width: 25%">
                                    </colgroup>
                                    <tr>
                                        <td colspan="4" class="header-cell">
                                            <div class="form-header">
                                                <div class="logo-slot">
                                                    <img class="form-logo" src="{{ asset('images/dmw.png') }}" alt="">
                                                </div>
                                                <div class="agency-block">
                                                    <div class="agency-name">Department of Migrant Workers</div>
                                                    <div>Regional Office XI</div>
                                                    <div class="form-title">Official Business Form</div>
                                                </div>
                                                <div class="logo-slot">
                                                    <img class="form-logo right" src="{{ asset('images/bagong-pilipinas-logo.png') }}" alt="">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label label-cell" colspan="2">
                                            Name of Employee
                                            <span class="field-value">{{ $employeeName ?: 'N/A' }}</span>
                                        </td>
                                        <td class="label label-cell" colspan="2">
                                            Date Covered
                                            <span class="field-value">{{ $dateCovered }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="field-cell" colspan="2">
                                            <span class="field-label">Division:</span>
                                            <span class="field-value">{{ $division }}</span>
                                        </td>
                                        <td class="field-cell" colspan="2">
                                            <span class="field-label">Office:</span>
                                            <span class="field-value">{{ $office }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="purpose-label-cell" colspan="4">
                                            <span class="field-label">Purpose:</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="value purpose-value" colspan="4">{{ $locatorSlip->purpose ?: '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="time-cell" colspan="2">
                                            <span class="label">From (Time)</span>
                                            <span class="field-value">{{ $timeFrom }}</span>
                                        </td>
                                        <td class="time-cell" colspan="2">
                                            <span class="label">To (Time)</span>
                                            <span class="field-value">{{ $timeTo }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="signature-space" colspan="4">
                                            <span class="signature-caption">Signature:</span>
                                            <div class="signature-name">{{ $employeeName ?: '' }}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="approval-cell" colspan="2">
                                            <span class="field-label">Recommending Approval:</span>
                                            <div class="approval-name">{{ $chiefName }}</div>
                                            <div class="approval-position">Division Chief</div>
                                        </td>
                                        <td class="approval-cell" colspan="2">
                                            <span class="field-label">Approved By:</span>
                                            <div class="approval-name">{{ $regionalDirectorName }}</div>
                                            <div class="approval-position">Regional Director</div>
                                        </td>
                                    </tr>
                                </table>
                                <div class="copy-label">{{ $copyLabel }}</div>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
    <script>
        const locatorSlipReturnUrl = @js(route('locator-slips.show', $locatorSlip));

        window.addEventListener('afterprint', () => {
            window.location.href = locatorSlipReturnUrl;
        });

        window.addEventListener('load', () => {
            window.print();
        });
    </script>
</body>
</html>
