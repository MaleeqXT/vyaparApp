<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invoicePreviewData['title'] ?? 'Invoice Preview' }}</title>
    <style>
        body {
            margin: 0;
            background: #fff;
            color: #111827;
            font-family: Arial, Helvetica, sans-serif;
        }
        .preview-shell {
            max-width: 1140px;
            margin: 0 auto;
            padding: 28px 28px 36px;
        }
        .preview-title {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 20px;
        }
        .preview-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .preview-table th,
        .preview-table td {
            border: 1px solid #2f2f2f;
            padding: 7px 9px;
            font-size: 13px;
            vertical-align: top;
            word-wrap: break-word;
        }
        .preview-table th {
            font-weight: 700;
            background: #fafafa;
        }
        .company-name {
            font-size: 26px;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 10px;
        }
        .muted {
            color: #4b5563;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .section-heading {
            font-weight: 700;
            margin-bottom: 8px;
        }
        .totals-row td {
            font-weight: 700;
        }
        .summary-table td {
            padding: 6px 10px;
        }
        .summary-label {
            width: 32%;
        }
        .summary-sep {
            width: 4%;
            text-align: center;
        }
        .summary-value {
            width: 64%;
            text-align: right;
        }
        .signature-box {
            height: 110px;
        }
        .signature-label {
            font-weight: 700;
        }
        @media print {
            body {
                background: #fff;
            }
            .preview-shell {
                padding: 0;
            }
        }
    </style>
</head>
<body>
@php
    $items = $invoicePreviewData['items'] ?? [];
    $adjustmentRows = $invoicePreviewData['adjustmentRows'] ?? [];
    $businessName = $invoicePreviewData['businessName'] ?? 'Business';
    $businessPhone = $invoicePreviewData['phone'] ?? '';
    $businessEmail = $invoicePreviewData['businessEmail'] ?? '';
    $billTo = $invoicePreviewData['billTo'] ?? '-';
    $billPhone = $invoicePreviewData['billPhone'] ?? '';
    $invoiceNo = $invoicePreviewData['invoiceNo'] ?? '-';
    $invoiceDate = $invoicePreviewData['date'] ?? '-';
    $subtotal = (float) ($invoicePreviewData['subtotal'] ?? 0);
    $total = (float) ($invoicePreviewData['total'] ?? 0);
    $received = (float) ($invoicePreviewData['received'] ?? 0);
    $balance = (float) ($invoicePreviewData['balance'] ?? 0);
    $totalQty = collect($items)->sum(fn ($item) => (float) ($item['qty'] ?? $item['tadaat'] ?? 0));
@endphp

<div class="preview-shell">
    <div class="preview-title">{{ $invoicePreviewData['title'] ?? 'Invoice' }}</div>

    <table class="preview-table" style="margin-bottom: 10px;">
        <tr>
            <td colspan="2" style="padding: 0;">
                <table class="preview-table" style="border: 0;">
                    <tr>
                        <td style="width: 50%; border-left: 0; border-top: 0; border-bottom: 0;">
                            <div class="company-name">{{ $businessName }}</div>
                            <div>Phone: {{ $businessPhone ?: '-' }}</div>
                        </td>
                        <td style="width: 50%; border-right: 0; border-top: 0; border-bottom: 0;">
                            <div style="margin-top: 38px;">Email: {{ $businessEmail ?: '-' }}</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width: 50%;">
                <div class="section-heading">Bill To:</div>
                <div style="font-weight: 700; margin-bottom: 10px;">{{ $billTo }}</div>
                <div>Contact No: <strong>{{ $billPhone ?: '-' }}</strong></div>
            </td>
            <td style="width: 50%;">
                <div class="section-heading">Invoice Details:</div>
                <div>No: <strong>{{ $invoiceNo }}</strong></div>
                <div>Date: <strong>{{ $invoiceDate }}</strong></div>
            </td>
        </tr>
    </table>

    <table class="preview-table" style="margin-bottom: 14px;">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 41%;">Item name</th>
                <th style="width: 18%;">Quantity</th>
                <th style="width: 18%;">Price / Unit(Rs)</th>
                <th style="width: 18%;">Amount(Rs)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $item['name'] ?? 'Item' }}</strong></td>
                    <td class="text-right">{{ number_format((float) ($item['qty'] ?? $item['tadaat'] ?? 0), 0) }}</td>
                    <td class="text-right">Rs {{ number_format((float) ($item['rate'] ?? 0), 2) }}</td>
                    <td class="text-right">Rs {{ number_format((float) ($item['amt'] ?? $item['amount'] ?? 0), 2) }}</td>
                </tr>
            @endforeach
            <tr class="totals-row">
                <td></td>
                <td>Total</td>
                <td class="text-right">{{ number_format($totalQty, 0) }}</td>
                <td></td>
                <td class="text-right">Rs {{ number_format($total, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table class="preview-table summary-table" style="margin-bottom: 10px;">
        <tr>
            <td class="summary-label">Sub Total</td>
            <td class="summary-sep">:</td>
            <td class="summary-value">Rs {{ number_format($subtotal, 2) }}</td>
        </tr>
        <tr>
            <td class="summary-label"><strong>Total</strong></td>
            <td class="summary-sep"><strong>:</strong></td>
            <td class="summary-value"><strong>Rs {{ number_format($total, 2) }}</strong></td>
        </tr>
        <tr>
            <td class="summary-label">Received</td>
            <td class="summary-sep">:</td>
            <td class="summary-value">Rs {{ number_format($received, 2) }}</td>
        </tr>
        <tr>
            <td class="summary-label">Balance</td>
            <td class="summary-sep">:</td>
            <td class="summary-value">Rs {{ number_format($balance, 2) }}</td>
        </tr>
        @foreach($adjustmentRows as $row)
            <tr>
                <td class="summary-label">{{ $row['label'] ?? 'Adjustment' }}</td>
                <td class="summary-sep">:</td>
                <td class="summary-value">Rs {{ number_format((float) ($row['amount'] ?? 0), 2) }}</td>
            </tr>
        @endforeach
    </table>

    <table class="preview-table" style="margin-bottom: 10px;">
        <tr>
            <td class="section-heading">Invoice Amount in Words:</td>
        </tr>
        <tr>
            <td>{{ $invoicePreviewData['totalInWords'] ?? ('Rs ' . number_format($total, 2)) }}</td>
        </tr>
        <tr>
            <td class="section-heading">Terms &amp; Conditions:</td>
        </tr>
        <tr>
            <td>{{ $invoicePreviewData['description'] ?? 'Thanks for doing business with us!' }}</td>
        </tr>
    </table>

    <table class="preview-table">
        <tr>
            <td style="width: 50%; border-right: 0;"></td>
            <td style="width: 50%; border-left: 1px solid #2f2f2f;" class="signature-label">For {{ $businessName }}:</td>
        </tr>
        <tr>
            <td style="border-right: 0;"></td>
            <td class="signature-box text-center" style="vertical-align: bottom;">Authorized Signatory</td>
        </tr>
    </table>
</div>

@if(!empty($autoPrint))
<script>
    window.addEventListener('load', function () {
        window.print();
    });
</script>
@endif
</body>
</html>
