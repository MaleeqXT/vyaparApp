<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Party Statement</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color:#1f2937; font-size:12px; margin:24px; }
        .header { margin-bottom:16px; }
        .title { font-size:22px; font-weight:700; margin-bottom:6px; }
        .meta { font-size:12px; color:#475569; margin-bottom:2px; }
        .summary { width:100%; border-collapse:collapse; margin:14px 0 18px; }
        .summary td { border:1px solid #d1d5db; padding:10px; }
        .summary-label { color:#64748b; font-size:11px; }
        .summary-value { font-size:16px; font-weight:700; margin-top:4px; }
        table { width:100%; border-collapse:collapse; }
        th, td { border:1px solid #d1d5db; padding:8px 9px; vertical-align:top; }
        th { background:#f3f4f6; text-align:left; font-size:11px; }
        .num { text-align:right; }
        .section-box { border:1px solid #e5e7eb; padding:8px 10px; margin:4px 0 8px; background:#fafafa; }
        .section-title { font-weight:700; margin-bottom:4px; font-size:11px; color:#475569; }
        .muted { color:#64748b; }
        .page-note { margin-top:10px; font-size:10px; color:#64748b; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ $party->name ?: 'Party Statement' }}</div>
        <div class="meta">Phone: {{ $party->phone ?: '-' }}</div>
        <div class="meta">Statement Period: {{ $dateFrom ?: 'Start' }} to {{ $dateTo ?: 'Today' }}</div>
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="summary-label">Total Debit</div>
                <div class="summary-value">Rs {{ $summary['total_debit'] ?? '0.00' }}</div>
            </td>
            <td>
                <div class="summary-label">Total Credit</div>
                <div class="summary-value">Rs {{ $summary['total_credit'] ?? '0.00' }}</div>
            </td>
            <td>
                <div class="summary-label">Closing Balance</div>
                <div class="summary-value">Rs {{ $summary['closing_balance'] ?? '0.00' }}</div>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width:12%;">Date</th>
                <th style="width:18%;">Type</th>
                <th style="width:14%;">Bill No</th>
                <th style="width:14%;" class="num">Debit</th>
                <th style="width:14%;" class="num">Credit</th>
                <th style="width:16%;" class="num">Running Balance</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $txn)
                <tr>
                    <td>{{ $txn['date'] ?? '-' }}</td>
                    <td>{{ $txn['type'] ?: '-' }}</td>
                    <td>{{ $txn['number'] ?? '-' }}</td>
                    <td class="num">{{ ((float) str_replace(',', '', (string) ($txn['debit'] ?? 0)) > 0) ? 'Rs '.$txn['debit'] : '-' }}</td>
                    <td class="num">{{ ((float) str_replace(',', '', (string) ($txn['credit'] ?? 0)) > 0) ? 'Rs '.$txn['credit'] : '-' }}</td>
                    <td class="num">Rs {{ $txn['running_balance'] ?? '0.00' }}</td>
                </tr>
                @if(!empty($options['item_details']) && !empty($txn['item_details']))
                    <tr>
                        <td colspan="6">
                            <div class="section-box">
                                <div class="section-title">Item Details</div>
                                <table style="width:100%; border-collapse:collapse;">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th class="num">Tadaat</th>
                                            <th class="num">Net W</th>
                                            <th>Unit</th>
                                            <th class="num">Price</th>
                                            <th class="num">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($txn['item_details'] as $item)
                                            <tr>
                                                <td>{{ $item['name'] ?? '-' }}</td>
                                                <td class="num">{{ number_format((float) ($item['tadaat'] ?? 0), 2) }}</td>
                                                <td class="num">{{ number_format((float) ($item['net_w'] ?? 0), 2) }}</td>
                                                <td>{{ $item['unit'] ?? '-' }}</td>
                                                <td class="num">Rs {{ number_format((float) ($item['price'] ?? 0), 2) }}</td>
                                                <td class="num">Rs {{ number_format((float) (($item['grand_total'] ?? $item['amount'] ?? 0)), 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                @endif
                @if(!empty($options['description']) && !empty($txn['description']))
                    <tr>
                        <td colspan="6">
                            <div class="section-box">
                                <div class="section-title">Description</div>
                                <div>{{ $txn['description'] }}</div>
                            </div>
                        </td>
                    </tr>
                @endif
                @if(!empty($options['payment_status']) && !empty($txn['payment_status_text']))
                    <tr>
                        <td colspan="6">
                            <div class="section-box">
                                <div class="section-title">Payment Status</div>
                                <div>{{ $txn['payment_status_text'] }}</div>
                            </div>
                        </td>
                    </tr>
                @endif
                @if(!empty($options['payment_information']) && !empty($txn['payment_information']))
                    <tr>
                        <td colspan="6">
                            <div class="section-box">
                                <div class="section-title">Payment Information</div>
                                @foreach($txn['payment_information'] as $payment)
                                    <div style="margin-bottom:4px;">
                                        {{ $payment['payment_type'] ?? '-' }} |
                                        {{ $payment['bank_name'] ?? '-' }} |
                                        Rs {{ number_format((float) ($payment['amount'] ?? 0), 2) }} |
                                        Ref: {{ $payment['reference'] ?? '-' }}
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="6" class="muted" style="text-align:center;">No transactions found for selected period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="page-note">Generated on {{ now()->format('d/m/Y h:i A') }}</div>
</body>
</html>
