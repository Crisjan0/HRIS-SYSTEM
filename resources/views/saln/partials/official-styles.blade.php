<style>
    :root {
        --saln-page-w: 215.9mm;
        --saln-page-h: 330.2mm;
        --saln-page-top: 4.1mm;
        --saln-page-right: 4.5mm;
        --saln-page-bottom: 3mm;
        --saln-page-left: 6mm;
        --saln-print-content-h: 314mm;
        --saln-footer-space: 7mm;
    }
    .saln-official {
        font-family: "Bookman Old Style", "Times New Roman", serif;
        font-size: 10pt;
        line-height: 1.18;
        color: #000;
        width: 100%;
    }
    .saln-official *,
    .saln-official *::before,
    .saln-official *::after {
        box-sizing: border-box;
    }
    .saln-official .saln-page {
        width: 100%;
        min-height: 0;
        background: #fff;
        margin: 0 auto;
        padding: 0;
        position: relative;
    }
    .saln-official .saln-page + .saln-page {
        margin-top: 18px;
        padding-top: 8px;
    }
    .saln-official .saln-page-2 {
        page-break-before: always;
    }
    .saln-official .saln-page-break {
        page-break-after: always;
        height: 0;
        overflow: hidden;
    }
    .saln-official .saln-header {
        position: relative;
        min-height: 27mm;
        margin-bottom: 2mm;
    }
    .saln-official .saln-meta {
        font-size: 7pt;
        text-align: right;
        line-height: 1.4;
        font-family: "Bookman Old Style", "Times New Roman", serif;
    }
    .saln-official .saln-title {
        font-size: 10pt;
        font-weight: bold;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        margin-top: 4.5mm;
        margin-bottom: 0.7mm;
    }
    .saln-official .saln-subtitle {
        text-align: center;
        font-size: 8pt;
        margin: 0;
    }
    .saln-official table {
        border-collapse: collapse;
        width: 100%;
        table-layout: fixed;
        margin: 0 0 7px;
    }
    .saln-official th,
    .saln-official td {
        border: 1px solid #000;
        padding: 3px 5px;
        vertical-align: middle;
        overflow-wrap: anywhere;
        word-wrap: break-word;
        word-break: normal;
        white-space: normal;
    }
    .saln-official thead {
        display: table-header-group;
    }
    .saln-official tfoot {
        display: table-footer-group;
    }
    .saln-official tbody {
        display: table-row-group;
    }
    .saln-official tr {
        page-break-inside: avoid;
        break-inside: avoid;
    }
    .saln-official .saln-th {
        background: #d9d9d9;
        font-weight: bold;
        text-align: center;
        font-size: 8pt;
        text-transform: uppercase;
    }
    .saln-official .saln-th-sm {
        font-size: 7pt;
        font-weight: normal;
        text-transform: none;
    }
    .saln-official .saln-section-title {
        font-weight: bold;
        text-align: center;
        text-transform: uppercase;
        margin: 7px 0 5px;
        font-size: 9pt;
        page-break-after: avoid;
        break-after: avoid;
    }
    .saln-official .saln-line {
        border-bottom: 1px solid #000;
        min-height: 16px;
        display: block;
        width: 100%;
        overflow-wrap: anywhere;
        word-break: normal;
        white-space: normal;
    }
    .saln-official .saln-multiple-marriage-section {
        margin-top: 1.5mm;
        margin-bottom: 0;
        padding-bottom: 5mm;
        border-bottom: 0.7mm double #000;
    }
    .saln-official .saln-multiple-marriage-row {
        display: flex;
        align-items: flex-end;
        justify-content: flex-start;
        gap: 22mm;
        padding: 0;
        margin-bottom: 1.5mm;
    }
    .saln-official .saln-multiple-marriage-row .saln-check-label {
        margin-bottom: 0;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .saln-official .saln-multiple-marriage-section .saln-line {
        width: 89mm;
        max-width: 89mm;
        min-height: 4mm;
        margin-left: 12mm;
    }
    .saln-official .saln-input {
        width: 100%;
        border: none;
        border-bottom: 1px solid #000;
        background: transparent;
        font-size: 11px;
        padding: 2px 0;
        outline: none;
    }
    .saln-official .saln-input:focus {
        background: #fefce8;
    }
    .saln-official .saln-cell-input {
        width: 100%;
        border: none;
        background: transparent;
        font-size: 10px;
        padding: 2px;
        outline: none;
    }
    .saln-official .saln-cell-input:focus {
        background: #fefce8;
    }
    .saln-official .saln-add-btn {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        font-size: 10px;
        font-weight: 700;
        color: #4338ca;
        background: #eef2ff;
        border: 1px solid #c7d2fe;
        border-radius: 6px;
        cursor: pointer;
    }
    .saln-official .saln-add-btn:hover {
        background: #e0e7ff;
    }
    .saln-official .saln-remove-btn {
        margin-left: 4px;
        font-size: 9px;
        font-weight: 700;
        color: #dc2626;
        cursor: pointer;
    }
    .saln-official .saln-empty-row td {
        text-align: center;
        color: #9ca3af;
        font-style: italic;
        font-size: 10px;
        padding: 10px;
    }
    .saln-official .saln-compliance-title {
        text-align: center;
        text-transform: uppercase;
        font-size: 8pt;
        font-weight: bold;
        margin: 0 0 1.5mm;
    }
    .saln-official .saln-checkbox-row,
    .saln-official .saln-compliance-row {
        display: flex;
        flex-wrap: wrap;
        gap: 4mm 13mm;
        align-items: baseline;
        justify-content: center;
        font-size: 9pt;
    }
    .saln-official .saln-compliance-option {
        display: inline-flex;
        align-items: baseline;
        gap: 4px;
        cursor: pointer;
        white-space: nowrap;
        font-style: italic;
    }
    .saln-official .saln-compliance-rule {
        margin-bottom: 8mm !important;
        padding-bottom: 0 !important;
    }
    .saln-official .saln-inline-blank {
        border: none;
        border-bottom: 1px solid #000;
        background: transparent;
        font-family: inherit;
        font-size: 11px;
        padding: 0 2px 1px;
        outline: none;
        border-radius: 0;
        min-height: 18px;
    }
    .saln-official .saln-inline-blank:focus {
        background: #fefce8;
    }
    .saln-official .saln-inline-blank[type="date"] {
        color-scheme: light;
    }
    .saln-official .saln-inline-blank[type="date"]::-webkit-calendar-picker-indicator {
        margin-left: 2px;
        cursor: pointer;
    }
    .saln-official .saln-inline-year {
        width: 44px;
        text-align: center;
    }
    .saln-official .saln-box {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 11px;
        height: 11px;
        min-width: 11px;
        border: 1px solid #000;
        background: #fff;
        vertical-align: middle;
        box-sizing: border-box;
        flex-shrink: 0;
        font-family: Arial, sans-serif;
        font-size: 8px;
        font-weight: 700;
        line-height: 1;
    }
    .saln-official .saln-box.is-checked,
    .saln-official .saln-native-check:checked + .saln-box {
        background: #fff;
    }
    .saln-official .saln-check-label {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
        font-size: 9pt;
    }
    .saln-official .saln-native-check {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }
    .saln-official .saln-note {
        font-size: 9px;
        font-style: italic;
        margin: 3px 0;
    }
    .saln-official .saln-top-identity > .saln-note {
        font-size: 10pt;
        font-style: normal;
        line-height: 1.15;
        margin: 0;
    }
    .saln-official .saln-instruction-label {
        margin: 0;
        font-size: 9pt;
        font-weight: bold;
        font-style: normal;
        line-height: 1.15;
        text-transform: uppercase;
    }
    .saln-official .saln-top-identity > .saln-checkbox-row {
        justify-content: center;
        gap: 21mm;
        margin-top: 1.2mm !important;
        margin-bottom: 7mm !important;
        font-style: italic;
    }
    .saln-official .saln-top-identity > .saln-checkbox-row .saln-check-label {
        font-size: 9.2pt;
        font-style: italic;
    }
    .saln-official .saln-top-identity > .saln-instruction-label + .saln-instruction-label {
        margin-top: 0.9mm;
    }
    .saln-official .saln-total-row td {
        font-weight: bold;
        text-align: right;
        white-space: nowrap;
    }
    .saln-official .saln-networth {
        border: 1px solid #000;
        margin: 8px 0 10px;
        padding: 6px 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: bold;
        font-size: 12px;
    }
    .saln-official .saln-liabilities-heading {
        margin: 0 0 1mm;
        font-size: 10pt;
        font-weight: bold;
        text-transform: uppercase;
    }
    .saln-official .saln-liabilities-table {
        margin-bottom: 1.5mm;
    }
    .saln-official .saln-liabilities-table th:nth-child(1),
    .saln-official .saln-liabilities-table td:nth-child(1) {
        width: 36%;
    }
    .saln-official .saln-liabilities-table th:nth-child(2),
    .saln-official .saln-liabilities-table td:nth-child(2) {
        width: 40%;
    }
    .saln-official .saln-liabilities-table th:nth-child(3),
    .saln-official .saln-liabilities-table td:nth-child(3) {
        width: 24%;
    }
    .saln-official .saln-liabilities-table th {
        font-size: 8.4pt;
        padding: 2.2mm 2mm;
        white-space: nowrap;
    }
    .saln-official .saln-liabilities-table td {
        height: 6mm;
        font-size: 8.8pt;
        line-height: 1.15;
        text-align: center;
    }
    .saln-official .saln-liabilities-table td:nth-child(3) {
        white-space: nowrap;
        overflow-wrap: normal;
        word-break: normal;
    }
    .saln-official .saln-liabilities-summary {
        margin-bottom: 6mm;
        font-size: 10pt;
        font-weight: bold;
    }
    .saln-official .saln-total-liabilities-row {
        display: block;
        text-align: right;
        margin-bottom: 1.5mm;
    }
    .saln-official .saln-total-liabilities-label {
        display: inline-block;
        min-width: 50mm;
        text-align: right;
        margin-right: 2mm;
    }
    .saln-official .saln-total-liabilities-value {
        display: inline-block;
        min-width: 51mm;
        border-bottom: 1.2mm solid #000;
        text-align: center;
        padding-bottom: 0.5mm;
    }
    .saln-official .saln-networth-row {
        display: block;
        text-align: right;
        font-size: 10pt;
    }
    .saln-official .saln-networth-value {
        display: inline-block;
        min-width: 51mm;
        border-bottom: 1.2mm solid #000;
        text-align: center;
        padding-bottom: 0.5mm;
        margin-left: 2mm;
    }
    .saln-official .saln-cert {
        font-size: 10pt;
        text-align: justify;
        margin: 3mm 0 2.5mm;
        line-height: 1.22;
        text-indent: 1.27cm;
    }
    .saln-official .saln-repeat-title {
        text-align: right;
        font-size: 9px;
        font-weight: bold;
        margin-bottom: 4px;
    }
    .saln-official .saln-wide-table th,
    .saln-official .saln-wide-table td {
        padding: 3px 4px;
        font-size: 8.8pt;
        line-height: 1.15;
    }
    .saln-official .saln-wide-table .saln-th {
        font-size: 7.4pt;
    }
    .saln-official .saln-asset-table tbody td {
        font-size: 8.8pt;
        line-height: 1.15;
    }
    .saln-official .saln-asset-table td:nth-last-child(1) {
        white-space: nowrap;
        overflow-wrap: normal;
        word-break: normal;
    }
    .saln-official .saln-asset-table .saln-total-row td {
        font-size: 9.2pt;
        line-height: 1.15;
        padding-top: 1.2mm;
        padding-bottom: 1.2mm;
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
    }
    .saln-official .saln-text-table th,
    .saln-official .saln-text-table td {
        vertical-align: top;
    }
    .saln-official .saln-text-table .saln-th {
        font-size: 8.4pt;
    }
    .saln-official .saln-text-table tbody td {
        font-size: 8.8pt;
        line-height: 1.15;
    }
    .saln-official .saln-name-table td {
        border: 0;
        vertical-align: top;
        height: 3.63mm;
        padding-top: 2px;
        padding-bottom: 2px;
        line-height: 1.1;
        font-size: 9pt;
    }
    .saln-official .saln-name-table {
        border-top: 0.7mm double #000;
        margin-top: 0;
        margin-bottom: 6mm;
        padding-top: 5mm;
    }
    .saln-official .saln-name-table .saln-name-cell {
        vertical-align: top;
        font-size: 9.5pt;
    }
    .saln-official .saln-name-table .saln-name-role {
        padding-top: 3.8mm;
        font-size: 10pt;
    }
    .saln-official .saln-name-table .saln-name-label {
        padding-left: 3mm;
        padding-right: 3mm;
        white-space: nowrap !important;
        font-size: 8.7pt;
    }
    .saln-official .saln-name-table .saln-name-cell .saln-value-wrap,
    .saln-official .saln-name-table .saln-name-value,
    .saln-official .saln-name-table .saln-name-blank-line {
        border-bottom: 1px solid #000;
    }
    .saln-official .saln-name-table .saln-name-cell .saln-value-wrap {
        min-height: 4.5mm;
        margin-bottom: 0.8mm;
        text-align: center;
    }
    .saln-official .saln-name-table .saln-name-value {
        min-height: 4.6mm;
        padding-left: 5mm;
    }
    .saln-official .saln-name-table .saln-name-blank-line {
        height: 5.4mm;
    }
    .saln-official .saln-name-table td.font-bold {
        white-space: nowrap;
        overflow-wrap: normal;
        word-break: normal;
        font-size: 9pt;
    }
    .saln-official .saln-name-table .saln-empty-side-cell {
        padding: 0;
        font-size: 1px;
    }
    .saln-official .saln-children-table {
        border: 0;
        margin-top: 1mm;
        margin-bottom: 4mm;
    }
    .saln-official .saln-children-table th,
    .saln-official .saln-children-table td {
        border: 0 !important;
        background: #fff !important;
        font-size: 9pt;
        text-align: center;
        padding: 1.1mm 3mm;
    }
    .saln-official .saln-children-table th {
        font-weight: bold;
    }
    .saln-official .saln-children-table td {
        height: 5mm;
        font-weight: normal;
    }
    .saln-official .saln-children-table td:first-child {
        padding-left: 2mm;
        padding-right: 22mm;
    }
    .saln-official .saln-children-table td:last-child {
        padding-left: 20mm;
        padding-right: 12mm;
    }
    .saln-official .saln-child-line {
        display: block;
        width: 100%;
        min-height: 4mm;
        border-bottom: 1px solid #000;
    }
    .saln-official .saln-assets-title {
        border-top: 0.7mm double #000;
        padding-top: 2.2mm;
        margin-top: 2mm;
    }
    .saln-official .saln-label-stack {
        display: block;
        font-weight: bold;
        white-space: nowrap;
    }
    .saln-official .saln-value-wrap {
        display: block;
        min-height: 13px;
        overflow-wrap: anywhere;
        word-break: normal;
        white-space: normal;
    }
    .saln-official .saln-signature-grid {
        display: table;
        width: 100%;
        border-collapse: separate;
        border-spacing: 28px 0;
        margin: 12px 0 14px;
        font-size: 10px;
    }
    .saln-official .saln-page1-signature {
        width: 52mm;
        margin: 3mm 0 6mm auto;
        font-size: 9pt;
    }
    .saln-official .saln-signature-grid > div {
        display: table-cell;
        width: 50%;
        vertical-align: top;
    }
    .saln-official .saln-sign-line {
        border-bottom: 1px solid #000;
        min-height: 30px;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        text-align: center;
    }
    .saln-official .saln-sign-line img {
        display: block;
        max-width: 180px;
        max-height: 34px;
        object-fit: contain;
    }
    .saln-official .saln-oath-row {
        page-break-inside: avoid;
        break-inside: avoid;
        margin-top: 10px;
        font-size: 10pt;
        line-height: 1.2;
    }
    .saln-official .saln-oath-text {
        margin: 0 0 5mm;
        font-size: 10pt;
        line-height: 1.2;
    }
    .saln-official .saln-oath-officer {
        width: 78mm;
        margin: 0 auto 3mm;
        text-align: center;
        font-size: 10pt;
        line-height: 1.15;
    }
    .saln-official .saln-oath-officer-line {
        min-height: 5mm;
        border-bottom: 1px solid #000;
        margin-bottom: 1mm;
        text-align: center;
    }
    .saln-official .saln-oath-label {
        font-weight: normal;
        font-style: italic;
    }
    .saln-official .saln-footnotes {
        margin-top: 2mm;
        font-size: 6.2pt;
        line-height: 1.08;
        text-align: justify;
    }
    .saln-official .saln-footnotes p {
        margin: 0 0 0.7mm;
        padding-left: 4mm;
        text-indent: -4mm;
    }
    .saln-official .saln-footnotes sup {
        font-size: 5.6pt;
        line-height: 0;
    }
    .saln-official .text-center { text-align: center !important; }
    .saln-official .text-right { text-align: right !important; }
    .saln-official .font-bold { font-weight: bold !important; }
    .saln-official .uppercase { text-transform: uppercase !important; }
    .saln-official .align-top { vertical-align: top !important; }
    .saln-official .mb-1 { margin-bottom: 4px !important; }
    .saln-official .mb-2 { margin-bottom: 6px !important; }
    .saln-official .mb-3 { margin-bottom: 8px !important; }
    .saln-official .mb-4 { margin-bottom: 10px !important; }
    .saln-official .mb-6 { margin-bottom: 14px !important; }
    .saln-official .mb-8 { margin-bottom: 18px !important; }
    .saln-official .mt-1 { margin-top: 4px !important; }
    .saln-official .mt-3 { margin-top: 8px !important; }
    .saln-official .mt-4 { margin-top: 10px !important; }
    .saln-official .my-4 { margin-top: 10px !important; margin-bottom: 10px !important; }
    .saln-official .ml-3 { margin-left: 12px !important; }
    .saln-official .pt-3 { padding-top: 8px !important; }
    .saln-official .pb-3 { padding-bottom: 8px !important; }
    .saln-official .border-b { border-bottom: 1px solid #000 !important; }
    .saln-official .border-t { border-top: 1px solid #000 !important; }
    .saln-official .border-t-2 { border-top: 2px solid #000 !important; }
    .saln-official .border-black { border-color: #000 !important; }
    .saln-official .inline-block { display: inline-block !important; }
    .saln-official .flex { display: flex !important; }
    .saln-official .grid { display: grid !important; }
    .saln-official .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; }
    .saln-official .gap-2 { gap: 8px !important; }
    .saln-official .gap-8 { gap: 28px !important; }
    .saln-official .flex-1 { flex: 1 1 0% !important; }
    .saln-official .shrink-0 { flex-shrink: 0 !important; }
    .saln-official .w-28 { width: 112px !important; }
    .saln-official .w-32 { width: 128px !important; }
    .saln-official .w-\[8\%\] { width: 8% !important; }
    .saln-official .w-\[12\%\] { width: 12% !important; }
    .saln-official .w-\[22\%\] { width: 22% !important; }
    .saln-official .w-\[36\%\] { width: 36% !important; }
    .saln-official .w-1\/2 { width: 50% !important; }
    .saln-official .w-1\/3 { width: 33.333333% !important; }
    .saln-official .w-2\/3 { width: 66.666667% !important; }
    .saln-official .min-w-\[180px\] { min-width: 180px !important; }
    .saln-official .min-w-\[250px\] { min-width: 250px !important; }
    .saln-official .min-h-\[40px\] { min-height: 40px !important; }
    .saln-official .text-\[8px\] { font-size: 8px !important; }
    .saln-official .text-\[9px\] { font-size: 9px !important; }
    .saln-official .text-\[10px\] { font-size: 10px !important; }
    .saln-official .text-\[11px\] { font-size: 11px !important; }
    .saln-official .text-\[14px\] { font-size: 14px !important; }
    .saln-official .saln-page-footer {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 2.5mm;
        z-index: 50;
        height: 6mm;
        text-align: center;
        color: #777;
        background: #fff;
        font-family: "Bookman Old Style", "Times New Roman", serif;
        font-size: 10pt;
        font-style: italic;
        line-height: 6mm;
        pointer-events: none;
    }
    .saln-official .saln-footnotes {
        font-size: 8px;
        margin-top: 16px;
        line-height: 1.4;
    }
    .saln-official.saln-preview .saln-page,
    .saln-official.saln-pdf .saln-page {
        width: var(--saln-page-w);
        height: var(--saln-page-h);
        min-height: var(--saln-page-h);
        padding: var(--saln-page-top) var(--saln-page-right) calc(var(--saln-page-bottom) + var(--saln-footer-space) + 4mm) var(--saln-page-left);
    }
    .saln-official.saln-preview .saln-page + .saln-page {
        margin-top: 18px;
        padding-top: var(--saln-page-top);
    }
    .saln-official.saln-pdf .saln-page + .saln-page {
        margin-top: 0;
        padding-top: var(--saln-page-top);
    }
    .saln-official.saln-pdf .saln-page {
        width: 215.9mm;
        height: 330.2mm;
        min-height: 330.2mm;
        padding: 4.1mm 4.5mm 14mm 6mm;
    }
    .saln-official.saln-pdf .saln-page + .saln-page {
        padding-top: 4.1mm;
    }
    .saln-official.saln-pdf .saln-page-footer {
        display: none;
    }
    @media print {
        @page {
            size: 215.9mm 330.2mm;
            margin: 0;
        }
        .print\:hidden { display: none !important; }
        html {
            width: auto !important;
            min-height: var(--saln-print-content-h) !important;
            overflow: visible !important;
            background: #fff !important;
        }
        body {
            margin: 0 !important;
            background: #fff !important;
            overflow: visible !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        body > .flex.h-screen.overflow-hidden {
            display: block !important;
            height: auto !important;
            min-height: 0 !important;
            overflow: visible !important;
            background: #fff !important;
        }
        body > .flex.h-screen.overflow-hidden > aside,
        body > .flex.h-screen.overflow-hidden > div[x-show],
        body > .flex.h-screen.overflow-hidden header {
            display: none !important;
        }
        body > .flex.h-screen.overflow-hidden > .flex,
        body > .flex.h-screen.overflow-hidden main,
        body > .flex.h-screen.overflow-hidden main > div,
        body > .flex.h-screen.overflow-hidden main > div > div {
            display: block !important;
            width: 100% !important;
            max-width: none !important;
            height: auto !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: visible !important;
            background: #fff !important;
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }
        .print\:py-0 { padding-top: 0 !important; padding-bottom: 0 !important; }
        .print\:max-w-none { max-width: none !important; }
        .print\:mx-0 { margin-left: 0 !important; margin-right: 0 !important; }
        .print\:px-0 { padding-left: 0 !important; padding-right: 0 !important; }
        .print\:shadow-none { box-shadow: none !important; }
        .print\:border-none { border: 0 !important; }
        .print\:p-4 { padding: 0 !important; }
        .saln-official {
            font-size: 10pt;
            line-height: 1.16;
        }
        .saln-official .saln-page {
            width: var(--saln-page-w);
            height: var(--saln-page-h);
            min-height: var(--saln-page-h);
            page-break-after: auto;
            margin: 0;
            padding: var(--saln-page-top) var(--saln-page-right) calc(var(--saln-page-bottom) + var(--saln-footer-space) + 4mm) var(--saln-page-left);
        }
        .saln-official .saln-page + .saln-page {
            margin-top: 0 !important;
            padding-top: var(--saln-page-top) !important;
        }
        .saln-official .saln-page-footer {
            display: block !important;
        }
        .saln-official .saln-page-2 {
            page-break-before: always;
        }
        .saln-official .saln-section-title,
        .saln-official .saln-networth,
        .saln-official .saln-cert {
            page-break-inside: avoid;
            break-inside: avoid;
        }
    }
</style>
