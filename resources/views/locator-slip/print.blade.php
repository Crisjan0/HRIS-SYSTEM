<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locator Slip Form</title>
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

        .locator-signature-img {
            display: block;
            max-width: 44mm;
            max-height: 9mm;
            margin: 0 auto;
            object-fit: contain;
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

        .pass-slip-table {
            height: 121mm;
            border: .35mm solid #000;
            font-size: 8.2pt;
            line-height: 1.15;
        }

        .pass-slip-table td {
            border: 0;
        }

        .pass-header-cell {
            height: 21mm;
            padding: 2.2mm 4.5mm 0 !important;
        }

        .pass-header {
            display: grid;
            grid-template-columns: 20mm 1fr 20mm;
            align-items: start;
            column-gap: 2mm;
        }

        .pass-logo {
            width: 9.5mm;
            max-height: 9.5mm;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        .pass-logo.right {
            width: 10.5mm;
        }

        .pass-title {
            padding-top: 1.2mm;
            text-align: center;
            font-size: 12pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .02em;
        }

        .pass-date-row {
            display: flex;
            justify-content: flex-end;
            align-items: flex-end;
            gap: 1.6mm;
            margin-top: 2mm;
            font-size: 8.2pt;
            font-weight: 400;
        }

        .pass-body-cell {
            padding: 0 4.5mm 1.1mm !important;
            vertical-align: top;
        }

        .pass-line-row {
            display: flex;
            align-items: flex-end;
            gap: 1.8mm;
            min-height: 4.7mm;
            font-size: 8.2pt;
            font-weight: 400;
        }

        .pass-line-row.compact {
            min-height: 4.25mm;
        }

        .pass-label {
            flex: 0 0 auto;
            white-space: nowrap;
        }

        .pass-line {
            display: block;
            flex: 1 1 auto;
            min-height: 3.7mm;
            border-bottom: .22mm solid #000;
            text-align: center;
            font-size: 7pt;
            font-weight: 400;
            line-height: 3.5mm;
            overflow: hidden;
            white-space: nowrap;
            text-transform: uppercase;
        }

        .pass-line.strong {
            font-weight: 700;
        }

        .pass-instruction {
            margin: .8mm 0 .3mm;
            font-size: 8.2pt;
            font-weight: 700;
            font-style: italic;
            line-height: 1.25;
        }

        .pass-reason-line {
            margin-top: .4mm;
        }

        .pass-signature {
            width: 42mm;
            margin: 7.5mm auto .7mm;
            border-bottom: .22mm solid #000;
            min-height: 3mm;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        .pass-signature img {
            max-width: 40mm;
            max-height: 9mm;
            object-fit: contain;
            display: block;
        }

        .pass-signature-caption {
            text-align: center;
            font-size: 8pt;
            font-weight: 400;
        }

        .pass-approval {
            width: 100%;
            height: 22mm;
            margin-top: .8mm;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .pass-approval td {
            width: 50%;
            border: .22mm solid #000;
            padding: .9mm 2mm .8mm !important;
            vertical-align: bottom;
        }

        .pass-approval-label {
            height: 5mm;
            font-size: 5.8pt;
            font-weight: 400;
            text-transform: uppercase;
            vertical-align: top;
        }

        .pass-approval-line {
            width: 32mm;
            min-height: 3mm;
            margin: 9mm auto .4mm;
            border-bottom: .22mm solid #000;
        }

        .pass-approval-role {
            text-align: center;
            font-size: 8pt;
            font-weight: 400;
        }

        .pass-security-note {
            text-align: center;
            font-size: 8pt;
            font-weight: 400;
            margin: .8mm 0 .2mm;
        }

        .pass-guard-line {
            width: 58mm;
            min-height: 3mm;
            margin: 5.8mm auto .4mm;
            border-bottom: .22mm solid #000;
        }

        .pass-copy-label {
            height: 4mm;
            line-height: 4mm;
            font-size: 7pt;
            color: transparent;
        }


        /*
         * Preview mode
         * The iframe URL contains ?preview=1, so only one enlarged copy is
         * displayed in the modal. These styles do not affect actual printing.
         */
        .preview-sheet {
            width: 100%;
            height: auto;
            min-height: 0;
            margin: 0;
            padding: 8px;
            background: #ffffff;
        }

        .preview-layout {
            /* Same approximate width as one copy in the 2 x 2 A4 print layout. */
            width: min(100%, 89.5mm);
            height: auto;
            margin: 0 auto;
            border-spacing: 0;
        }

        .preview-layout > tbody,
        .preview-layout > tbody > tr {
            display: block;
            width: 100%;
            height: auto;
        }

        .preview-layout > tbody > tr > td {
            display: block;
            width: 100%;
            height: auto;
            padding: 0;
        }

        .preview-layout .copy-wrap {
            width: 100%;
        }

        .preview-layout .copy-table {
            width: 100%;
            height: 125mm;
            min-height: 0;
            margin: 0;
            background: #fff;
            box-shadow: none;
        }

        .preview-layout .pass-slip-table {
            height: 121mm;
            min-height: 0;
        }

        .preview-layout .copy-label,
        .preview-layout .pass-copy-label {
            display: none;
        }


        .preview-layout .copy-wrap {
            margin: 0;
        }

        .preview-layout .copy-table,
        .preview-layout .pass-slip-table {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        body:has(.preview-sheet) {
            min-height: 0;
            background: #ffffff;
        }

        @media print {
            html,
            body {
                width: 210mm;
                height: 297mm;
                margin: 0;
                padding: 0;
                background: #ffffff;
            }

            .sheet {
                width: 194mm;
                height: 281mm;
                min-height: 0;
                margin: 0;
                padding: 0;
                background: #ffffff;
            }

            .copies-layout {
                display: table;
                width: 100%;
                height: 100%;
                margin: 0;
                border-collapse: separate;
                border-spacing: 7.5mm 7mm;
                table-layout: fixed;
            }

            .copies-layout > tbody {
                display: table-row-group;
            }

            .copies-layout > tbody > tr {
                display: table-row;
            }

            .copies-layout > tbody > tr > td {
                display: table-cell;
                width: 50%;
                height: 50%;
                padding: 0;
                vertical-align: top;
            }

            .copy-table {
                width: 100%;
                height: 125mm;
                margin: 0;
                box-shadow: none;
            }

            .pass-slip-table {
                height: 121mm;
            }

            .copy-label,
            .pass-copy-label {
                display: block;
            }

            .preview-sheet {
                width: 100%;
                height: auto;
                min-height: 0;
                margin: 0;
                padding: 8mm;
                background: #ffffff;
            }

            .preview-layout {
                display: table;
                width: min(100%, 89.5mm);
                height: auto;
                margin: 0 auto;
                border-spacing: 0;
                table-layout: fixed;
            }

            .preview-layout > tbody,
            .preview-layout > tbody > tr,
            .preview-layout > tbody > tr > td {
                display: block;
                width: 100%;
                height: auto;
                padding: 0;
            }

            .preview-layout .copy-table {
                width: 100%;
                height: 125mm;
                margin: 0;
                box-shadow: none;
            }

            .preview-layout .pass-slip-table {
                height: 121mm;
            }

            .preview-layout .copy-label,
            .preview-layout .pass-copy-label {
                display: none;
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
        $timeFrom = $locatorSlip->time_from ? \Carbon\Carbon::parse($locatorSlip->time_from)->format('h:i A') : '';
        $timeTo = $locatorSlip->time_to ? \Carbon\Carbon::parse($locatorSlip->time_to)->format('h:i A') : '';
        $formType = ($locatorSlip->type ?? '') === 'Personal' ? 'Pass Slip' : ($locatorSlip->type ?: 'Official Business');
        $formTitle = $formType === 'Pass Slip' ? 'Pass Slip Form' : 'Official Business Form';
        $signatureUrl = $employee?->effective_signature_url;
        $isPreview = request()->boolean('preview');

        // Show only one copy inside the preview modal.
        // Keep four copies when opening the actual printable page.
        $copyLabels = $isPreview
            ? ['PREVIEW']
            : ['EMPLOYEE FILE', 'HR/FAD FILE', 'EMPLOYEE FILE', 'HR/FAD FILE'];
    @endphp

    <main class="sheet{{ $isPreview ? ' preview-sheet' : '' }}">
        <table
            class="copies-layout{{ $isPreview ? ' preview-layout' : '' }}"
            aria-label="{{ $formTitle }} copies"
        >
            <tbody>
                @foreach(array_chunk($copyLabels, 2) as $copyRow)
                    <tr>
                        @foreach($copyRow as $copyLabel)
                            <td>
                                <div class="copy-wrap">
                                <table class="copy-table{{ $formType === 'Pass Slip' ? ' pass-slip-table' : '' }}" aria-label="{{ $copyLabel }} {{ $formTitle }}">
                                    <colgroup>
                                        <col style="width: 25%">
                                        <col style="width: 25%">
                                        <col style="width: 25%">
                                        <col style="width: 25%">
                                    </colgroup>
                                    @if($formType === 'Pass Slip')
                                    <tr>
                                        <td colspan="4" class="pass-header-cell">
                                            <div class="pass-header">
                                                <img class="pass-logo" src="{{ asset('images/dmw.png') }}" alt="">
                                                <div>
                                                    <div class="pass-title">PASS SLIP</div>
                                                    <div class="pass-date-row">
                                                        <span>Date :</span>
                                                        <span class="pass-line" style="max-width: 29mm;">{{ $dateCovered }}</span>
                                                    </div>
                                                </div>
                                                <img class="pass-logo right" src="{{ asset('images/bagong-pilipinas-logo.png') }}" alt="">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="pass-body-cell">
                                            <div class="pass-line-row compact">
                                                <span class="pass-label">Name :</span>
                                                <span class="pass-line strong">{{ $employeeName ?: 'N/A' }}</span>
                                            </div>
                                            <div class="pass-line-row compact">
                                                <span class="pass-label">Division :</span>
                                                <span class="pass-line strong">{{ $division }}</span>
                                            </div>

                                            <div class="pass-instruction">
                                                Permission is requested to leave the office during<br>
                                                office hours for personal purposes.
                                            </div>

                                            <div class="pass-line-row compact">
                                                <span class="pass-label">State Reason(s) :</span>
                                                <span class="pass-line">{{ $locatorSlip->purpose ?: '' }}</span>
                                            </div>
                                            <span class="pass-line pass-reason-line"></span>

                                            <div class="pass-line-row compact">
                                                <span class="pass-label">Time of Departure :</span>
                                                <span class="pass-line">{{ $timeFrom }}</span>
                                            </div>
                                            <div class="pass-line-row compact">
                                                <span class="pass-label">Est. Time of Return :</span>
                                                <span class="pass-line">{{ $timeTo }}</span>
                                            </div>

                                            <div class="pass-signature">
                                                @if($signatureUrl)
                                                    <img src="{{ $signatureUrl }}" alt="Employee signature">
                                                @endif
                                            </div>
                                            <div class="pass-signature-caption">Signature of Employee</div>

                                            <table class="pass-approval">
                                                <tr>
                                                    <td>
                                                        <div class="pass-approval-label">RECOMMENDING APPROVAL:</div>
                                                        <div class="pass-approval-line"></div>
                                                        <div class="pass-approval-role">Division Chief</div>
                                                    </td>
                                                    <td>
                                                        <div class="pass-approval-label" style="text-align:center;">APPROVED BY:</div>
                                                        <div class="pass-approval-line"></div>
                                                        <div class="pass-approval-role">Director/Head, B/S/O</div>
                                                    </td>
                                                </tr>
                                            </table>

                                            <div class="pass-security-note">(To be filled up by the Security Guard)</div>

                                            <div class="pass-line-row">
                                                <span class="pass-label">Time of Return :</span>
                                                <span class="pass-line"></span>
                                            </div>

                                            <div class="pass-guard-line"></div>
                                            <div class="pass-signature-caption">Signature of Security Guard</div>
                                        </td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td colspan="4" class="header-cell">
                                            <div class="form-header">
                                                <div class="logo-slot">
                                                    <img class="form-logo" src="{{ asset('images/dmw.png') }}" alt="">
                                                </div>
                                                <div class="agency-block">
                                                    <div class="agency-name">Department of Migrant Workers</div>
                                                    <div>Regional Office XI</div>
                                                    <div class="form-title">{{ $formTitle }}</div>
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
                                            @if($signatureUrl)
                                                <img src="{{ $signatureUrl }}" alt="Employee signature" class="locator-signature-img">
                                            @endif
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
                                    @endif
                                </table>
                                <div class="{{ $formType === 'Pass Slip' ? 'pass-copy-label' : 'copy-label' }}">{{ $copyLabel }}</div>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
    @unless($isPreview)
        <script>
            const locatorSlipReturnUrl = @js(route('locator-slips.index', $locatorSlip));

            window.addEventListener('afterprint', () => {
                window.location.href = locatorSlipReturnUrl;
            });

            window.addEventListener('load', () => {
                window.print();
            });
        </script>
    @endunless
</body>
</html>
