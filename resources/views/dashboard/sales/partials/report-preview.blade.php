<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale Report</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111827; margin: 24px; font-size: 13px; }
        h1 { text-align: center; font-size: 20px; margin: 0 0 18px; text-decoration: underline; }
        .duration { font-size: 16px; font-weight: 700; margin-bottom: 18px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        th, td { border: 1px solid #bfc5d2; padding: 6px 8px; vertical-align: top; }
        thead th { background: #d1d5db; font-weight: 700; text-transform: uppercase; font-size: 12px; }
        .subhead th { background: #d1d5db; font-size: 12px; text-transform: none; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .item-table { margin-top: 0; margin-bottom: 4px; }
        .nowrap { white-space: nowrap; }
        .sale-block { margin-bottom: 16px; }
        .muted { color: #6b7280; }
        @media print {
            body { margin: 12px; }
        }
    </style>
</head>
<body>
    <h1>Sale Report</h1>
    @if(!empty($duration))
        <div class="duration">Duration: {{ $duration }}</div>
    @endif

    @foreach($sales as $sale)
        <div class="sale-block">
            <table>
                <thead>
                    <tr>
                        @if($options['date'])<th>Date</th>@endif
                        @if($options['order_number'])<th>Order No.</th>@endif
                        @if($options['invoice_no'])<th>Invoice No.</th>@endif
                        @if($options['party_name'])<th>Party Name</th>@endif
                        @if($options['party_phone'])<th>Party Phone No.</th>@endif
                        @if($options['total'])<th>Total</th>@endif
                        @if($options['payment_type'])<th>Payment Type</th>@endif
                        @if($options['received_paid'])<th>Received / Paid</th>@endif
                        @if($options['balance_due'])<th>Balance Due</th>@endif
                        <th>Due Date</th>
                        @if($options['payment_status'])<th>Status</th>@endif
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @if($options['date'])<td class="nowrap">{{ $sale['date'] }}</td>@endif
                        @if($options['order_number'])<td>{{ $sale['order_number'] }}</td>@endif
                        @if($options['invoice_no'])<td>{{ $sale['invoice_no'] }}</td>@endif
                        @if($options['party_name'])<td>{{ $sale['party_name'] }}</td>@endif
                        @if($options['party_phone'])<td>{{ $sale['party_phone'] }}</td>@endif
                        @if($options['total'])<td class="text-end">Rs {{ number_format($sale['total'], 2) }}</td>@endif
                        @if($options['payment_type'])<td>{{ $sale['payment_type'] }}</td>@endif
                        @if($options['received_paid'])<td class="text-end">Rs {{ number_format($sale['received_paid'], 2) }}</td>@endif
                        @if($options['balance_due'])<td class="text-end">Rs {{ number_format($sale['balance_due'], 2) }}</td>@endif
                        <td class="nowrap">{{ $sale['due_date'] }}</td>
                        @if($options['payment_status'])<td>{{ $sale['status'] }}</td>@endif
                    </tr>
                </tbody>
            </table>

            @if($options['item_details'])
                <table class="item-table">
                    <thead class="subhead">
                        <tr>
                            <th style="width:40px;">#</th>
                            <th>Item name</th>
                            <th class="text-center" style="width:120px;">Quantity</th>
                            <th class="text-end" style="width:150px;">Price / Unit</th>
                            <th class="text-end" style="width:150px;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale['items'] as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item['name'] }}</td>
                                <td class="text-center">{{ $item['quantity'] }}</td>
                                <td class="text-end">Rs {{ number_format($item['price'], 2) }}</td>
                                <td class="text-end">Rs {{ number_format($item['amount'], 2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td><strong>Total</strong></td>
                            <td class="text-center"><strong>{{ collect($sale['items'])->sum('quantity') }}</strong></td>
                            <td></td>
                            <td class="text-end"><strong>Rs {{ number_format($sale['total'], 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            @endif

            @if($options['description'])
                <div style="margin: 4px 0 8px;"><strong>Description:</strong> {{ $sale['description'] }}</div>
            @endif

            @if($options['payment_breakup'] && !empty($sale['payments']))
                <table class="item-table">
                    <thead class="subhead">
                        <tr>
                            <th style="width:40px;">#</th>
                            <th>Payment Type</th>
                            <th>Bank</th>
                            <th>Reference</th>
                            <th>Date</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale['payments'] as $index => $payment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $payment['type'] }}</td>
                                <td>{{ $payment['bank'] }}</td>
                                <td>{{ $payment['reference'] }}</td>
                                <td>{{ $payment['date'] }}</td>
                                <td class="text-end">Rs {{ number_format($payment['amount'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endforeach

    @if(!empty($autoPrint))
        <script>
            window.addEventListener('load', function () {
                setTimeout(function () { window.print(); }, 300);
            });
        </script>
    @endif
</body>
</html>
