<style>
    .saln-official {
        font-family: "Times New Roman", Times, serif;
        font-size: 11px;
        line-height: 1.35;
        color: #000;
    }
    .saln-official .saln-header {
        min-height: 72px;
    }
    .saln-official .saln-meta {
        font-size: 9px;
        text-align: right;
        line-height: 1.4;
    }
    .saln-official .saln-title {
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .saln-official .saln-subtitle {
        text-align: center;
        font-size: 11px;
    }
    .saln-official table {
        border-collapse: collapse;
        width: 100%;
    }
    .saln-official th,
    .saln-official td {
        border: 1px solid #000;
        padding: 4px 5px;
        vertical-align: middle;
    }
    .saln-official .saln-th {
        background: #d9d9d9;
        font-weight: bold;
        text-align: center;
        font-size: 9px;
        text-transform: uppercase;
    }
    .saln-official .saln-th-sm {
        font-size: 8px;
        font-weight: normal;
        text-transform: none;
    }
    .saln-official .saln-section-title {
        font-weight: bold;
        text-align: center;
        text-transform: uppercase;
        margin: 12px 0 6px;
        font-size: 11px;
    }
    .saln-official .saln-line {
        border-bottom: 1px solid #000;
        min-height: 18px;
        display: block;
        width: 100%;
    }
    .saln-official .saln-multiple-marriage-section {
        margin-top: 6px;
    }
    .saln-official .saln-multiple-marriage-row {
        display: flex;
        align-items: flex-end;
        gap: 16px;
        padding: 6px 0;
        margin-bottom: 6px;
    }
    .saln-official .saln-multiple-marriage-row .saln-check-label {
        margin-bottom: 5px;
        white-space: nowrap;
        flex-shrink: 0;
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
    .saln-official .saln-checkbox-row,
    .saln-official .saln-compliance-row {
        display: flex;
        flex-wrap: wrap;
        gap: 12px 28px;
        align-items: baseline;
        font-size: 11px;
    }
    .saln-official .saln-compliance-option {
        display: inline-flex;
        align-items: baseline;
        gap: 4px;
        cursor: pointer;
        white-space: nowrap;
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
        display: inline-block;
        width: 11px;
        height: 11px;
        min-width: 11px;
        border: 1px solid #000;
        background: #fff;
        vertical-align: middle;
        box-sizing: border-box;
        flex-shrink: 0;
    }
    .saln-official .saln-box.is-checked,
    .saln-official .saln-native-check:checked + .saln-box {
        background: #000;
    }
    .saln-official .saln-check-label {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
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
        margin: 4px 0;
    }
    .saln-official .saln-total-row td {
        font-weight: bold;
        text-align: right;
    }
    .saln-official .saln-networth {
        border-top: 3px solid #000;
        margin-top: 8px;
        padding-top: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: bold;
        font-size: 12px;
    }
    .saln-official .saln-cert {
        font-size: 10px;
        text-align: justify;
        margin: 12px 0;
        line-height: 1.5;
    }
    .saln-official .saln-footnotes {
        font-size: 8px;
        margin-top: 16px;
        line-height: 1.4;
    }
    @media print {
        .print\:hidden { display: none !important; }
        .saln-official { font-size: 10px; }
    }
</style>
