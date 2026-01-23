<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receipt - {{ $bill->bill_number }}</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Arial', sans-serif;
      padding: 20px;
      max-width: 800px;
      margin: 0 auto;
    }
    .receipt-header {
      text-align: center;
      margin-bottom: 30px;
      border-bottom: 3px solid #333;
      padding-bottom: 20px;
    }
    .hospital-name {
      font-size: 28px;
      font-weight: bold;
      color: #333;
      margin-bottom: 5px;
    }
    .hospital-info {
      font-size: 12px;
      color: #666;
    }
    .receipt-title {
      font-size: 24px;
      font-weight: bold;
      margin: 20px 0;
      text-align: center;
      color: #444;
    }
    .info-section {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    .info-box {
      flex: 1;
    }
    .info-box h4 {
      font-size: 14px;
      margin-bottom: 10px;
      color: #333;
      border-bottom: 2px solid #eee;
      padding-bottom: 5px;
    }
    .info-box p {
      font-size: 12px;
      margin: 5px 0;
      color: #555;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
    }
    table th {
      background: #f0f0f0;
      padding: 12px 8px;
      text-align: left;
      font-size: 12px;
      font-weight: bold;
      border-bottom: 2px solid #ddd;
    }
    table td {
      padding: 10px 8px;
      font-size: 12px;
      border-bottom: 1px solid #eee;
    }
    .text-right {
      text-align: right;
    }
    .total-section {
      margin-top: 20px;
      border-top: 2px solid #333;
      padding-top: 15px;
    }
    .total-row {
      display: flex;
      justify-content: space-between;
      margin: 8px 0;
      font-size: 14px;
    }
    .grand-total {
      font-size: 18px;
      font-weight: bold;
      background: #f0f0f0;
      padding: 15px;
      margin-top: 10px;
      border-radius: 5px;
    }
    .payment-status {
      text-align: center;
      margin: 20px 0;
      padding: 15px;
      border-radius: 5px;
      font-weight: bold;
      font-size: 16px;
    }
    .status-paid {
      background: #d1fae5;
      color: #065f46;
    }
    .status-unpaid {
      background: #fee2e2;
      color: #991b1b;
    }
    .status-partial {
      background: #fef3c7;
      color: #92400e;
    }
    .footer {
      margin-top: 40px;
      text-align: center;
      font-size: 11px;
      color: #666;
      border-top: 1px solid #ddd;
      padding-top: 20px;
    }
    .signature-section {
      display: flex;
      justify-content: space-between;
      margin-top: 60px;
    }
    .signature-box {
      text-align: center;
    }
    .signature-line {
      border-top: 1px solid #333;
      margin-top: 60px;
      padding-top: 5px;
      font-size: 12px;
    }
    @media print {
      body {
        padding: 0;
      }
      .no-print {
        display: none;
      }
    }
  </style>
</head>
<body>
  <div class="receipt-header">
    <div class="hospital-name">Hospital Management System</div>
    <div class="hospital-info">
      123 Medical Street, Healthcare City<br>
      Phone: (555) 123-4567 | Email: info@hospital.com
    </div>
  </div>

  <div class="receipt-title">RECEIPT / INVOICE</div>

  <div class="info-section">
    <div class="info-box">
      <h4>Bill To:</h4>
      <p><strong>{{ $bill->patient->first_name }} {{ $bill->patient->last_name }}</strong></p>
      <p>Patient ID: {{ $bill->patient->patient_id }}</p>
      <p>Phone: {{ $bill->patient->phone }}</p>
      @if($bill->patient->email)
      <p>Email: {{ $bill->patient->email }}</p>
      @endif
    </div>
    <div class="info-box" style="text-align: right;">
      <h4>Bill Details:</h4>
      <p><strong>Bill #:</strong> {{ $bill->bill_number }}</p>
      <p><strong>Date:</strong> {{ $bill->bill_date->format('M d, Y h:i A') }}</p>
      <p><strong>Type:</strong> {{ strtoupper($bill->bill_type) }}</p>
      <p><strong>Billed By:</strong> {{ $bill->billedBy->name }}</p>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Service Description</th>
        <th class="text-right">Qty</th>
        <th class="text-right">Unit Price</th>
        <th class="text-right">Discount</th>
        <th class="text-right">Tax</th>
        <th class="text-right">Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach($bill->billItems as $item)
      <tr>
        <td>
          <strong>{{ $item->service_name }}</strong><br>
          <small style="color: #666;">{{ $item->service_code }}</small>
        </td>
        <td class="text-right">{{ $item->quantity }}</td>
        <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
        <td class="text-right">
          @if($item->discount_amount > 0)
            ${{ number_format($item->discount_amount, 2) }}
          @else
            -
          @endif
        </td>
        <td class="text-right">
          @if($item->tax_amount > 0)
            ${{ number_format($item->tax_amount, 2) }}
          @else
            -
          @endif
        </td>
        <td class="text-right"><strong>${{ number_format($item->total_amount, 2) }}</strong></td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="total-section">
    <div class="total-row">
      <span>Subtotal:</span>
      <span>${{ number_format($bill->sub_total, 2) }}</span>
    </div>
    @if($bill->discount_amount > 0)
    <div class="total-row">
      <span>
        Discount ({{ $bill->discount_percentage }}%)
        @if($bill->discount_reason)
          <br><small style="color: #666;">{{ $bill->discount_reason }}</small>
        @endif
      </span>
      <span style="color: #dc2626;">-${{ number_format($bill->discount_amount, 2) }}</span>
    </div>
    @endif
    <div class="total-row">
      <span>Tax:</span>
      <span>${{ number_format($bill->tax_amount, 2) }}</span>
    </div>
    <div class="grand-total total-row">
      <span>TOTAL AMOUNT:</span>
      <span>${{ number_format($bill->total_amount, 2) }}</span>
    </div>
    <div class="total-row" style="color: #059669;">
      <span>Amount Paid:</span>
      <span><strong>${{ number_format($bill->paid_amount, 2) }}</strong></span>
    </div>
    <div class="total-row" style="color: #dc2626;">
      <span>Balance Due:</span>
      <span><strong>${{ number_format($bill->balance_amount, 2) }}</strong></span>
    </div>
  </div>

  <div class="payment-status status-{{ $bill->payment_status }}">
    @if($bill->payment_status === 'paid')
      ✓ PAID IN FULL
    @elseif($bill->payment_status === 'partial')
      ⚠ PARTIALLY PAID
    @else
      ✗ UNPAID
    @endif
  </div>

  @if($bill->payments->count() > 0)
  <div style="margin: 30px 0;">
    <h4 style="margin-bottom: 10px; font-size: 14px;">Payment History:</h4>
    <table style="margin: 0;">
      <thead>
        <tr>
          <th>Date</th>
          <th>Method</th>
          <th>Reference</th>
          <th class="text-right">Amount</th>
        </tr>
      </thead>
      <tbody>
        @foreach($bill->payments as $payment)
        <tr>
          <td>{{ $payment->payment_date->format('M d, Y H:i') }}</td>
          <td>{{ strtoupper($payment->payment_method) }}</td>
          <td>{{ $payment->payment_reference ?? '-' }}</td>
          <td class="text-right"><strong>${{ number_format($payment->amount, 2) }}</strong></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif

  @if($bill->notes)
  <div style="margin: 20px 0; padding: 15px; background: #f9fafb; border-left: 4px solid #667eea;">
    <strong>Notes:</strong> {{ $bill->notes }}
  </div>
  @endif

  <div class="signature-section">
    <div class="signature-box">
      <div class="signature-line">Patient/Representative Signature</div>
    </div>
    <div class="signature-box">
      <div class="signature-line">Authorized Signatory</div>
    </div>
  </div>

  <div class="footer">
    <p>Thank you for choosing our hospital!</p>
    <p>This is a computer-generated receipt. For any queries, please contact our billing department.</p>
    <p>Generated on: {{ now()->format('M d, Y h:i A') }}</p>
  </div>

  <div class="no-print" style="text-align: center; margin-top: 30px;">
    <button onclick="window.print()" style="padding: 12px 30px; background: #667eea; color: white; border: none; border-radius: 8px; font-size: 16px; cursor: pointer;">
      Print Receipt
    </button>
    <button onclick="window.close()" style="padding: 12px 30px; background: #6b7280; color: white; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; margin-left: 10px;">
      Close
    </button>
  </div>
</body>
</html>
