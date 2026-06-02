<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proforma Preview</title>
    <style>
        :root {
            --accent: #f59e0b;
            --line: #d1d5db;
            --text: #111827;
            --muted: #6b7280;
            --paper: #ffffff;
            --page: #f3f4f6;
        }

        * { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; background: var(--page); color: var(--text); }
        body {
            font-family: Arial, Helvetica, sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .sheet {
            width: min(1100px, calc(100vw - 36px));
            margin: 18px auto;
            background: var(--paper);
            border: 1px solid #e5e7eb;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
            padding: 24px 20px 28px;
        }

        .company {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .company-meta {
            font-size: 12px;
            color: var(--muted);
        }

        .accent-line {
            height: 2px;
            background: var(--accent);
            margin: 8px 0 14px;
        }

        .title {
            text-align: center;
            color: var(--accent);
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 18px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 24px;
            margin-bottom: 16px;
            align-items: start;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .muted {
            color: var(--muted);
        }

        .invoice-details {
            text-align: right;
            font-size: 14px;
            line-height: 1.7;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .items thead th {
            background: var(--accent) !important;
            color: #111827 !important;
            font-size: 12px;
            text-align: left;
            padding: 8px 10px;
            border: 1px solid var(--accent) !important;
            font-weight: 700;
        }

        .items tbody td {
            border: 1px solid var(--line);
            padding: 8px 10px;
            font-size: 13px;
            vertical-align: top;
        }

        .items .num {
            text-align: right;
            white-space: nowrap;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 24px;
            margin-top: 16px;
        }

        .words-box {
            padding-top: 6px;
        }

        .totals td {
            padding: 7px 8px;
            font-size: 14px;
        }

        .totals tr:last-child td {
            background: var(--accent);
            color: #fff;
            font-weight: 700;
        }

        .sign-block {
            margin-top: 26px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 28px;
            min-height: 150px;
        }

        .sign-left,
        .sign-right {
            font-size: 14px;
            line-height: 1.7;
        }

        .sign-right {
            text-align: center;
            align-self: end;
        }

        .sign-right .signature-line {
            margin-top: 60px;
            font-weight: 700;
        }

        @media print {
            body { background: #fff; }
            .sheet {
                width: auto;
                margin: 0;
                border: none;
                box-shadow: none;
                padding: 0 10px 16px;
            }
        }
    </style>
</head>
<body>
@php
    $items = collect($sale->items ?? [])->values();
    $subtotal = (float) $items->sum('amount');
    $grandTotal = (float) ($sale->grand_total ?? $subtotal);
    $totalQty = (float) $items->sum('quantity');
@endphp
    <div class="sheet">
        <div class="company">{{ config('app.name', 'My Company') }}</div>
        <div class="company-meta">Phone no.: {{ $sale->phone ?? '-' }}</div>
        <div class="accent-line"></div>

        <div class="title">Proforma Invoice</div>

        <div class="info-grid">
            <div>
                <div class="section-title">Proforma Invoice For</div>
                <div style="font-weight:700; font-size:18px;">{{ $sale->display_party_name }}</div>
                <div class="muted" style="margin-top:8px; line-height:1.7;">
                    <div><strong>Contact No.:</strong> {{ $sale->phone ?? '-' }}</div>
                    <div><strong>Email:</strong> {{ $sale->party?->email ?? '-' }}</div>
                    <div><strong>Address:</strong> {{ $sale->billing_address ?? '-' }}</div>
                    @if(!empty($sale->shipping_address))
                        <div><strong>Shipping:</strong> {{ $sale->shipping_address }}</div>
                    @endif
                </div>
            </div>
            <div class="invoice-details">
                <div class="section-title" style="margin-bottom: 4px;">Proforma Invoice Details</div>
                <div><strong>Proforma Invoice No.:</strong> {{ $sale->bill_number ?? '-' }}</div>
                <div><strong>Date:</strong> {{ optional($sale->invoice_date)->format('d/m/Y') ?? '-' }}</div>
                <div><strong>Status:</strong> {{ ucfirst($sale->status ?? 'open') }}</div>
            </div>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th style="width:48px;">#</th>
                    <th>Item name</th>
                    <th style="width:120px;" class="num">Quantity</th>
                    <th style="width:120px;" class="num">Unit</th>
                    <th style="width:140px;" class="num">Price/ Unit</th>
                    <th style="width:140px;" class="num">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->item_name ?? '-' }}</strong>
                            @if(!empty($item->item_description))
                                <div class="muted" style="margin-top:4px; font-size:12px;">{{ $item->item_description }}</div>
                            @endif
                        </td>
                        <td class="num">{{ $item->quantity ?? 0 }}</td>
                        <td class="num">{{ $item->unit ?? '-' }}</td>
                        <td class="num">{{ number_format((float) ($item->unit_price ?? 0), 2) }}</td>
                        <td class="num">{{ number_format((float) ($item->amount ?? 0), 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center muted" style="padding:18px;">No items found.</td>
                    </tr>
                @endforelse
                <tr>
                    <td></td>
                    <td><strong>Total</strong></td>
                    <td class="num"><strong>{{ $totalQty }}</strong></td>
                    <td></td>
                    <td></td>
                    <td class="num"><strong>{{ number_format($grandTotal, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="summary-grid">
            <div class="words-box">
                <div class="section-title">Proforma Invoice Amount In Words</div>
                <div>{{ $sale->amount_in_words ?? 'One Thousand Rupees only' }}</div>
            </div>
            <table class="totals">
                <tr>
                    <td>Sub Total</td>
                    <td class="num">{{ number_format($subtotal, 2) }}</td>
                </tr>
                @if(!empty($sale->discount))
                    <tr>
                        <td>Discount</td>
                        <td class="num">{{ number_format((float) $sale->discount, 2) }}</td>
                    </tr>
                @endif
                <tr>
                    <td>Total</td>
                    <td class="num">{{ number_format($grandTotal, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="sign-block">
            <div class="sign-left">
                <div class="section-title">Notes</div>
                <div>{{ $sale->description ?? 'Thank you for your inquiry.' }}</div>
            </div>
            <div class="sign-right">
                <div>For: {{ config('app.name', 'My Company') }}</div>
                <div class="signature-line">Authorized Signatory</div>
            </div>
        </div>
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
