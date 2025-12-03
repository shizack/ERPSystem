<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order - {{ $purchaseOrder->po_number }}</title>
    <style>
        @page { size: A4; margin: 20mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11pt; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #4a5568; padding-bottom: 20px; }
        .header h1 { font-size: 24pt; color: #2d3748; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 2px; }
        .company-logo { margin-bottom: 15px; }
        .company-name { font-size: 18pt; font-weight: bold; color: #3b49df; margin-bottom: 5px; }
        .company-info { font-size: 9pt; color: #666; line-height: 1.6; }
        .document-info { display: flex; justify-content: space-between; margin-bottom: 25px; }
        .document-info div { width: 48%; }
        .document-info h3 { font-size: 10pt; color: #2d3748; text-transform: uppercase; margin-bottom: 8px; border-bottom: 2px solid #e2e8f0; padding-bottom: 4px; }
        .info-row { margin-bottom: 6px; font-size: 10pt; }
        .info-label { font-weight: bold; color: #4a5568; display: inline-block; width: 140px; }
        .section { margin-bottom: 25px; }
        .section-title { font-size: 11pt; font-weight: bold; color: #2d3748; text-transform: uppercase; margin-bottom: 12px; background: #f7fafc; padding: 8px 12px; border-left: 4px solid #3b49df; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table thead { background: #4a5568; color: white; }
        table th { padding: 10px; text-align: left; font-size: 10pt; font-weight: 600; }
        table td { padding: 10px; border-bottom: 1px solid #e2e8f0; font-size: 10pt; }
        table tbody tr:hover { background: #f7fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals { margin-top: 20px; float: right; width: 40%; }
        .totals table { margin-bottom: 0; }
        .totals td { padding: 8px 12px; }
        .totals .total-row { font-weight: bold; font-size: 12pt; background: #f7fafc; }
        .signatures { margin-top: 80px; display: flex; justify-content: space-between; clear: both; }
        .signature-box { width: 30%; text-align: center; }
        .signature-line { border-top: 2px solid #2d3748; padding-top: 8px; margin-top: 50px; font-size: 10pt; font-weight: bold; }
        .footer { margin-top: 40px; text-align: center; font-size: 9pt; color: #666; border-top: 1px solid #e2e8f0; padding-top: 15px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 11pt;">
            Print Purchase Order
        </button>
        <button onclick="window.close()" style="background: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 11pt; margin-left: 10px;">
            Close
        </button>
    </div>

    <!-- Header -->
    <div class="header">
        <h1>PURCHASE ORDER</h1>
        <div class="company-logo">
            <div class="company-name">{{ config('app.name', 'St. William') }}</div>
            <div class="company-name" style="font-size: 14pt; color: #666;">Funeral Homes</div>
        </div>
        <div class="company-info">
            Phone: (23) 456-7890 | Email: st-william@gmail.com
        </div>
    </div>

    <!-- Document Info -->
    <div class="document-info">
        <div>
            <h3>Purchase Order Information</h3>
            <div class="info-row">
                <span class="info-label">PO Number:</span> {{ $purchaseOrder->po_number }}
            </div>
            <div class="info-row">
                <span class="info-label">PO Date:</span> {{ $purchaseOrder->created_at->format('m/d/Y') }}
            </div>
            <div class="info-row">
                <span class="info-label">Request ID:</span> {{ $purchaseOrder->id }}
            </div>
            <div class="info-row">
                <span class="info-label">Total Amount:</span> ₱{{ number_format($purchaseOrder->total_amount, 2) }}
            </div>
        </div>

        <div>
            <h3>Buyer Information</h3>
            <div class="info-row">
                <span class="info-label">Company:</span> {{ config('app.name', 'St. William Funeral Homes') }}
            </div>
            <div class="info-row">
                <span class="info-label">Department:</span> Purchasing Department
            </div>
            <div class="info-row">
                <span class="info-label">Address:</span> Poblacion Dalaguete, Cebu
            </div>
            <div class="info-row">
                <span class="info-label">Contact Person:</span> {{ $purchaseOrder->user->first_name ?? 'Admin' }} {{ $purchaseOrder->user->last_name ?? '' }}
            </div>
            <div class="info-row">
                <span class="info-label">Phone:</span> (+63) 900-000-0000
            </div>
        </div>
    </div>

    <!-- Supplier/Vendor Section -->
    <div class="section">
        <div class="section-title">SUPPLIER / VENDOR</div>
        <div class="info-row">
            <span class="info-label">Supplier Name:</span> {{ $purchaseOrder->supplier->name ?? 'N/A' }}
        </div>
        <div class="info-row">
            <span class="info-label">Address:</span> {{ $purchaseOrder->supplier->address ?? 'N/A' }}
        </div>
        <div class="info-row">
            <span class="info-label">Contact Person:</span> {{ $purchaseOrder->supplier->contact_person ?? 'N/A' }}
        </div>
        <div class="info-row">
            <span class="info-label">Email:</span> {{ $purchaseOrder->supplier->email ?? 'N/A' }}
        </div>
        <div class="info-row">
            <span class="info-label">Phone:</span> {{ $purchaseOrder->supplier->phone ?? 'N/A' }}
        </div>
    </div>

    <!-- Items Table -->
    <table>
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="45%">Description</th>
                <th width="10%" class="text-center">Qty</th>
                <th width="10%">Unit</th>
                <th width="15%" class="text-right">Unit Price</th>
                <th width="15%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchaseOrder->items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $item->product->name ?? 'N/A' }}</strong><br>
                    <span style="font-size: 9pt; color: #666;">{{ $item->product->product_id ?? '' }}</span>
                </td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td>{{ $item->product->unit ?? 'kg' }}</td>
                <td class="text-right">₱{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right">₱{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">₱{{ number_format($purchaseOrder->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Tax (0.00%):</td>
                <td class="text-right">₱0.00</td>
            </tr>
            <tr>
                <td>Shipping:</td>
                <td class="text-right">₱0.00</td>
            </tr>
            <tr class="total-row">
                <td>Grand Total:</td>
                <td class="text-right">₱{{ number_format($purchaseOrder->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    <!-- Signatures -->
    <div class="signatures">
        <div class="signature-box">
            <div class="signature-line">Property Custodian</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">
                {{ $purchaseOrder->user->first_name ?? '' }} {{ $purchaseOrder->user->last_name ?? '' }}<br>
                Admin
            </div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Supplier</div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        Thank you for your business!<br>
        This is a computer-generated document. No signature is required.
    </div>

    <script>
        // Auto print when opened in new window
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>
</html>
