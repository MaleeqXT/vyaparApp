<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>Payment In PDF</title>
  <style>
    @page { margin: 28px; }
    body {
      font-family: DejaVu Sans, Arial, sans-serif;
      font-size: 12px;
      color: #111827;
      margin: 0;
    }
    .sheet {
      border: 1px solid #d1d5db;
      padding: 18px;
    }
    .top {
      display: flex;
      justify-content: space-between;
      gap: 16px;
      border-bottom: 1px solid #e5e7eb;
      padding-bottom: 14px;
      margin-bottom: 18px;
    }
    .title {
      text-align: center;
      font-size: 22px;
      font-weight: 700;
      margin: 8px 0 18px;
    }
    .company {
      font-size: 18px;
      font-weight: 700;
      margin-bottom: 4px;
    }
    .muted { color: #6b7280; }
    .grid {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 16px;
    }
    .grid td {
      border: 1px solid #d1d5db;
      padding: 10px 12px;
      vertical-align: top;
    }
    .label {
      color: #374151;
      font-weight: 700;
      margin-bottom: 6px;
      display: block;
    }
    .value {
      font-weight: 600;
    }
    .details {
      width: 100%;
      border-collapse: collapse;
      margin-top: 14px;
    }
    .details th, .details td {
      border: 1px solid #d1d5db;
      padding: 10px 8px;
      text-align: left;
    }
    .details th {
      background: #f3f4f6;
      font-weight: 700;
    }
    .summary {
      margin-top: 16px;
      width: 100%;
      border-collapse: collapse;
    }
    .summary td {
      border: 1px solid #e5e7eb;
      padding: 8px 10px;
    }
    .summary .amount {
      text-align: right;
      font-weight: 700;
    }
    .footer-note {
      margin-top: 14px;
      color: #6b7280;
      font-size: 11px;
    }
  </style>
</head>
<body>
  @php
    $payment = $paymentIn ?? null;
    $linkedRows = $payment?->links?->load('sale') ?? collect();
  @endphp

  <div class="sheet">
    <div class="title">Payment In Receipt</div>

    <table class="grid">
      <tr>
        <td style="width:50%;">
          <span class="company">{{ config('app.name', 'My Company') }}</span>
          <div class="muted">Payment received receipt</div>
          <div class="muted" style="margin-top:8px;">Phone: {{ $payment?->party?->phone ?: 'N/A' }}</div>
        </td>
        <td style="width:50%; text-align:right;">
          <span class="label">Receipt No.</span>
          <div class="value">{{ $payment?->receipt_no ?: '-' }}</div>
          <span class="label" style="margin-top:10px;">Date</span>
          <div class="value">{{ $payment?->date ? \Carbon\Carbon::parse($payment->date)->format('d/m/Y') : '-' }}</div>
        </td>
      </tr>
      <tr>
        <td>
          <span class="label">Party Name</span>
          <div class="value">{{ $payment?->party?->name ?: '-' }}</div>
          <div class="muted" style="margin-top:6px;">{{ $payment?->party?->billing_address ?: '' }}</div>
        </td>
        <td style="text-align:right;">
          <span class="label">Payment Type</span>
          <div class="value">{{ ucfirst($payment?->payment_type ?: '-') }}</div>
          <span class="label" style="margin-top:10px;">Bank Account</span>
          <div class="value">{{ $payment?->bankAccount?->display_name ?: 'Cash / Not selected' }}</div>
        </td>
      </tr>
    </table>

    <table class="details">
      <thead>
        <tr>
          <th style="width:42%;">Description</th>
          <th style="width:16%;">Reference No.</th>
          <th style="width:16%;">Amount</th>
          <th style="width:26%;">Linked Sales</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>{{ $payment?->description ?: 'Payment received.' }}</td>
          <td>{{ $payment?->reference_no ?: '-' }}</td>
          <td style="text-align:right;">Rs {{ number_format((float) ($payment?->amount ?? 0), 2) }}</td>
          <td>
            @if($linkedRows->count())
              @foreach($linkedRows as $link)
                <div>
                  {{ $link->sale?->bill_number ?: ('Sale #' . $link->sale_id) }}
                  - Rs {{ number_format((float) ($link->linked_amount ?? 0), 2) }}
                </div>
              @endforeach
            @else
              -
            @endif
          </td>
        </tr>
      </tbody>
    </table>

    <table class="summary">
      <tr>
        <td style="width:75%;">Total Received</td>
        <td class="amount">Rs {{ number_format((float) ($payment?->amount ?? 0), 2) }}</td>
      </tr>
      <tr>
        <td>Balance Due</td>
        <td class="amount">Rs 0.00</td>
      </tr>
    </table>

    <div class="footer-note">
      Generated on {{ now()->format('d/m/Y h:i A') }}
    </div>
  </div>
</body>
</html>
