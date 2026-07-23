<style>
    @page {
        size: legal portrait;
        margin: 8mm 7mm 30mm 8mm;
    }

    body {
        margin: 0;
        color: #000;
        font-family: "Bookman Old Style", "Times New Roman", serif;
        font-size: 9pt;
        line-height: 1.12;
    }

    footer {
        position: fixed;
        bottom: -15mm;
        left: 0;
        right: 0;
        height: 15mm;
    }

    .saln-pdf-document,
    .saln-pdf-document * {
        box-sizing: border-box;
    }

    .saln-pdf-document {
        width: 100%;
    }

    .saln-pdf-meta {
        text-align: right;
        font-size: 7.5pt;
        line-height: 1.15;
        margin-bottom: 4mm;
    }

    .saln-pdf-title {
        text-align: center;
        font-weight: bold;
        font-size: 13pt;
        text-transform: uppercase;
        letter-spacing: 0.2pt;
        margin: 0;
    }

    .saln-pdf-subtitle {
        text-align: center;
        font-size: 9pt;
        margin: 0 0 7mm;
    }

    .saln-pdf-compliance-title {
        text-align: center;
        text-transform: uppercase;
        font-weight: bold;
        margin: 0 0 1.6mm;
        font-size: 9pt;
    }

    .saln-pdf-compliance {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 7mm;
    }

    .saln-pdf-compliance td {
        border: 0;
        text-align: center;
        font-style: italic;
        font-size: 10pt;
        white-space: nowrap;
        padding: 0;
        vertical-align: middle;
    }

    .saln-pdf-box {
        display: inline-block;
        width: 3.2mm;
        height: 3.2mm;
        border: 0.35mm solid #000;
        text-align: center;
        line-height: 2.7mm;
        font-family: Arial, sans-serif;
        font-size: 7pt;
        font-weight: bold;
        vertical-align: middle;
        margin-right: 1.4mm;
    }

    .saln-pdf-double-rule {
        border-top: 0.7mm double #000;
        height: 0;
        margin: 0 0 5mm;
    }

    .saln-pdf-identity {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 6mm;
        page-break-inside: avoid;
    }

    .saln-pdf-identity td {
        border: 0;
        padding: 0.8mm 1.2mm;
        vertical-align: top;
    }

    .saln-pdf-role {
        width: 20mm;
        font-weight: bold;
        font-size: 10pt;
        padding-top: 2.8mm !important;
        white-space: nowrap;
    }

    .saln-pdf-name-cell {
        text-align: center;
    }

    .saln-pdf-line {
        border-bottom: 0.25mm solid #000;
        min-height: 4.4mm;
        display: block;
        width: 100%;
        text-align: center;
    }

    .saln-pdf-small-label {
        font-size: 8pt;
        display: block;
        text-align: center;
    }

    .saln-pdf-side-label {
        width: 35mm;
        font-weight: bold;
        font-size: 10pt;
        white-space: nowrap;
    }

    .saln-pdf-side-value {
        border-bottom: 0.25mm solid #000 !important;
        font-size: 10pt;
        min-height: 4.4mm;
    }

    .saln-pdf-instruction {
        font-weight: bold;
        text-transform: uppercase;
        font-size: 9.2pt;
        margin: 0 0 1mm;
    }

    .saln-pdf-choice-table {
        width: 100%;
        border-collapse: collapse;
        margin: 1mm 0 7mm;
    }

    .saln-pdf-choice-table td {
        width: 33.333%;
        border: 0;
        text-align: center;
        font-size: 10pt;
        font-style: italic;
        padding: 0;
        vertical-align: middle;
        white-space: nowrap;
    }

    .saln-pdf-multiple {
        width: 100%;
        border-collapse: collapse;
        margin: 0 0 6mm;
    }

    .saln-pdf-multiple td {
        border: 0;
        padding: 0.5mm 0;
    }

    .saln-pdf-section-title {
        text-align: center;
        text-transform: uppercase;
        font-weight: bold;
        font-size: 11pt;
        margin: 4mm 0 2mm;
        page-break-after: avoid;
    }

    .saln-pdf-section-title.underlined {
        text-decoration: underline;
    }

    .saln-pdf-children-title {
        white-space: nowrap;
        font-size: 10.2pt;
        letter-spacing: 0;
    }

    .saln-pdf-note {
        text-align: center;
        font-style: italic;
        font-size: 8pt;
        margin: 0 0 2mm;
    }

    .saln-pdf-main-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        margin: 0 0 3mm;
        page-break-inside: auto;
    }

    .saln-pdf-main-table thead {
        display: table-header-group;
    }

    .saln-pdf-main-table tbody {
        display: table-row-group;
    }

    .saln-pdf-main-table tr {
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .saln-pdf-main-table th,
    .saln-pdf-main-table td {
        border: 0.25mm solid #000;
        padding: 1.2mm 1.3mm;
        vertical-align: middle;
        font-size: 7.6pt;
        line-height: 1.08;
        word-wrap: break-word;
    }

    .saln-pdf-main-table th {
        background: #d9d9d9;
        text-align: center;
        font-weight: bold;
        text-transform: uppercase;
    }

    .saln-pdf-main-table td {
        min-height: 5mm;
    }

    .saln-pdf-children {
        width: 100%;
        border-collapse: collapse;
        margin: 0 0 5mm;
        page-break-inside: avoid;
    }

    .saln-pdf-children th,
    .saln-pdf-children td {
        border: 0;
        padding: 0.8mm 3mm;
        text-align: center;
        font-size: 9pt;
    }

    .saln-pdf-children th {
        font-weight: bold;
    }

    .saln-pdf-child-line {
        border-bottom: 0.25mm solid #000;
        display: block;
        min-height: 4mm;
    }

    .saln-pdf-subheading {
        font-weight: bold;
        margin: 1.5mm 0 1mm;
        font-size: 10pt;
        page-break-after: avoid;
    }

    .saln-pdf-total-row td {
        font-weight: bold;
        font-size: 9pt;
        white-space: nowrap;
    }

    .saln-pdf-summary {
        width: 100%;
        border-collapse: collapse;
        margin: 2mm 0 6mm;
        page-break-inside: avoid;
    }

    .saln-pdf-summary td {
        border: 0;
        padding: 0.5mm 0;
        font-weight: bold;
        font-size: 12pt;
    }

    .saln-pdf-heavy-line {
        display: inline-block;
        border-bottom: 1mm solid #000;
        min-width: 48mm;
        text-align: center;
        padding-bottom: 0.3mm;
        font-size: 10pt;
    }

    .saln-pdf-cert {
        text-align: justify;
        text-indent: 12.7mm;
        margin: 3mm 0 2.5mm;
        font-size: 10pt;
        line-height: 1.18;
    }

    .saln-pdf-sign-grid {
        width: 100%;
        border-collapse: collapse;
        margin: 4mm 0 4mm;
        page-break-inside: avoid;
    }

    .saln-pdf-sign-grid td {
        border: 0;
        width: 50%;
        padding: 0 6mm;
        vertical-align: top;
        font-size: 8pt;
    }

    .saln-pdf-sign-line {
        border-bottom: 0.25mm solid #000;
        height: 8mm;
        text-align: center;
    }

    .saln-pdf-sign-line img {
        max-height: 8mm;
        max-width: 40mm;
    }

    .saln-pdf-sign-label {
        text-align: center;
        font-weight: bold;
        font-size: 8pt;
        margin-bottom: 2mm;
    }

    .saln-pdf-id-row {
        width: 100%;
        border-collapse: collapse;
    }

    .saln-pdf-id-row td {
        border: 0;
        padding: 0.5mm 0;
        font-size: 7.8pt;
    }

    .saln-pdf-oath {
        page-break-inside: avoid;
        margin-top: 2mm;
        font-size: 10pt;
        line-height: 1.18;
    }

    .saln-pdf-oath-officer {
        width: 70mm;
        margin: 8mm auto 2mm;
        text-align: center;
        font-size: 10pt;
    }

    .saln-pdf-oath-label {
        font-style: italic;
        font-weight: normal;
    }

    .saln-pdf-footnotes {
        font-size: 6.2pt;
        line-height: 1.08;
        text-align: justify;
        margin-top: 3mm;
    }

    .saln-pdf-footnotes p {
        margin: 0 0 0.5mm;
        padding-left: 3.5mm;
        text-indent: -3.5mm;
    }

    .saln-pdf-page-break {
        page-break-before: always;
    }

    .avoid-break {
        page-break-inside: avoid;
    }

    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .font-bold { font-weight: bold; }
</style>
