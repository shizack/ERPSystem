<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Purchase Order - {{ $purchaseOrder->po_number }}</title>
    <style>
        @page { size: A4; margin: 18mm 16mm; }
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11pt; color: #1f2937; background: #f5f7fb; margin: 0; padding: 0; }
        .no-print { margin: 0 auto; max-width: 960px; padding: 16px; text-align: right; }
        .no-print button { background: #0a84ff; color: #fff; padding: 10px 16px; border: none; border-radius: 8px; cursor: pointer; font-size: 11pt; margin-left: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); }
        .no-print button.secondary { background: #6b7280; }
        .page { max-width: 960px; margin: 0 auto 32px; background: #fff; padding: 24px 24px 32px; border: 1px solid #e5e7eb; border-radius: 14px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); }
        .header { text-align: center; border-bottom: 3px solid #0a84ff; padding-bottom: 18px; margin-bottom: 20px; }
        .title { font-size: 22pt; font-weight: 800; letter-spacing: 2px; color: #111827; margin: 10px 0 6px; }
        .company-name { font-size: 14pt; font-weight: 700; color: #0a84ff; margin-bottom: 4px; }
        .company-sub { font-size: 10.5pt; color: #6b7280; }
        .company-info { font-size: 9.5pt; color: #6b7280; margin-top: 6px; }
        .logo { height: 70px; margin-bottom: 6px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; margin-bottom: 18px; }
        .card { border: 1px solid #e5e7eb; border-radius: 12px; padding: 14px 16px; background: #f9fafb; }
        .card h3 { margin: 0 0 10px; font-size: 11pt; font-weight: 700; color: #111827; letter-spacing: .5px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 10pt; }
        .row:last-child { margin-bottom: 0; }
        .label { font-weight: 700; color: #374151; min-width: 130px; }
        .value { color: #1f2937; text-align: right; }
        .section-title { font-size: 11pt; font-weight: 800; letter-spacing: .8px; color: #0a84ff; margin: 24px 0 10px; border-left: 4px solid #0a84ff; padding-left: 10px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; border: 1px solid #e5e7eb; margin-top: 6px; }
        thead { background: #0a84ff; color: #fff; }
        th, td { padding: 10px; font-size: 10pt; }
        th { text-align: left; font-weight: 700; letter-spacing: .3px; }
        td { border-top: 1px solid #e5e7eb; color: #1f2937; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .muted { color: #6b7280; font-size: 9.5pt; }
        .totals { margin-top: 18px; width: 100%; max-width: 360px; float: right; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
        .totals table { border: none; margin: 0; }
        .totals td { border-top: 1px solid #e5e7eb; padding: 10px 14px; font-size: 10.5pt; }
        .totals tr:first-child td { border-top: none; }
        .totals .heading { background: #f9fafb; font-weight: 700; }
        .totals .grand { background: #0a84ff; color: #fff; font-weight: 800; font-size: 11pt; }
        .signatures { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-top: 80px; }
        .sig-box { text-align: center; }
        .sig-line { border-top: 2px solid #111827; margin-top: 36px; padding-top: 8px; font-weight: 700; font-size: 10pt; }
        .footer { text-align: center; margin-top: 40px; font-size: 9.5pt; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 14px; }
        @media print {
            body { background: #fff; }
            .no-print { display: none; }
            .page { box-shadow: none; border: none; margin: 0; padding: 0; width: auto; }
        }
    </style>
</head>
<body>
    @php
        $logoPath = public_path(config('company.logo_path'));
        $logoUrl = asset(config('company.logo_path'));
        $hasLogo = file_exists($logoPath);
        $companyName = config('company.name', config('app.name', 'Company'));
        $companyPhone = '09664666762';
        $companyEmail = config('company.email', '');
        $companyAddress = 'Bantayan Island, Cebu, Philippines';
        $adminUser = auth('admin')->user();
        $currentUser = $adminUser ?: $purchaseOrder->user;
        $contactPerson = $adminUser->full_name ?? ($currentUser->name ?? '');
        $inChargeTitle = $adminUser->job_title ?? 'General Manager';
    @endphp

    <div class="no-print">
        <button onclick="window.print()">Print</button>
        <button class="secondary" onclick="window.close()">Close</button>
    </div>

    <div class="page">
        <div class="header">
            @if($hasLogo)
                <img src="{{ $logoUrl }}" alt="Logo" class="logo">
            @endif
            <div class="company-name">{{ $companyName }}</div>
            @if($companyAddress)
                <div class="company-sub">{{ $companyAddress }}</div>
            @endif
            <div class="company-info">
                @if($companyPhone) Phone: {{ $companyPhone }} @endif
                @if($companyPhone && $companyEmail) | @endif
                @if($companyEmail) Email: {{ $companyEmail }} @endif
            </div>
            <div class="title">PURCHASE ORDER</div>
        </div>

        <div class="grid">
            <div class="card">
                <h3>Purchase Order Information</h3>
                <div class="row"><span class="label">PO Number:</span><span class="value">{{ $purchaseOrder->po_number }}</span></div>
                <div class="row"><span class="label">PO Date:</span><span class="value">{{ $purchaseOrder->created_at?->format('m/d/Y') }}</span></div>
                <div class="row"><span class="label">Request ID:</span><span class="value">{{ str_pad((string)$purchaseOrder->id, 6, '0', STR_PAD_LEFT) }}</span></div>
                <div class="row"><span class="label">Total Amount:</span><span class="value">₱{{ number_format($purchaseOrder->total_amount, 2) }}</span></div>
            </div>
            <div class="card">
                <h3>Buyer Information</h3>
                <div class="row"><span class="label">Company:</span><span class="value">{{ $companyName }}</span></div>
                <div class="row"><span class="label">In-Charge:</span><span class="value">{{ $inChargeTitle }}</span></div>
                <div class="row"><span class="label">Address:</span><span class="value">{{ $companyAddress }}</span></div>
                <div class="row"><span class="label">Contact Person:</span><span class="value">{{ $contactPerson ?: 'N/A' }}</span></div>
                <div class="row"><span class="label">Phone:</span><span class="value">{{ $companyPhone }}</span></div>
            </div>
        </div>

        <div class="card" style="margin-top: 6px;">
            <h3>Supplier / Vendor</h3>
            <div class="row"><span class="label">Supplier Name:</span><span class="value">{{ $purchaseOrder->supplier->name ?? 'N/A' }}</span></div>
            <div class="row"><span class="label">Address:</span><span class="value">{{ $purchaseOrder->supplier->address ?? 'N/A' }}</span></div>
            <div class="row"><span class="label">Contact Person:</span><span class="value">{{ $purchaseOrder->supplier->contact_person ?? 'N/A' }}</span></div>
            <div class="row"><span class="label">Email:</span><span class="value">{{ $purchaseOrder->supplier->email ?? 'N/A' }}</span></div>
            <div class="row"><span class="label">Phone:</span><span class="value">{{ $purchaseOrder->supplier->phone ?? 'N/A' }}</span></div>
        </div>

        <div class="section-title">Items</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 6%;" class="text-center">No.</th>
                    <th style="width: 44%;">Description</th>
                    <th style="width: 10%;" class="text-center">Qty</th>
                    <th style="width: 10%;" class="text-center">Unit</th>
                    <th style="width: 15%;" class="text-right">Unit Price</th>
                    <th style="width: 15%;" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchaseOrder->items as $index => $item)
                    @php $lineTotal = ($item->quantity ?? 0) * ($item->unit_price ?? 0); @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->product->name ?? 'N/A' }}</strong><br>
                            <span class="muted">{{ $item->product->product_code ?? $item->product->product_id ?? '' }}</span>
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-center">{{ $item->product->unit ?? $item->unit ?? '' }}</td>
                        <td class="text-right">₱{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">₱{{ number_format($lineTotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <table>
                <tr class="heading">
                    <td>Summary</td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">₱{{ number_format($purchaseOrder->total_amount, 2) }}</td>
                </tr>
                <tr class="grand">
                    <td>Grand Total</td>
                    <td class="text-right">₱{{ number_format($purchaseOrder->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        <div style="clear: both;"></div>

        <div class="signatures">
            <div class="sig-box">
                <div class="sig-line">General Manager</div>
            </div>
            <div class="sig-box">
                <div class="sig-line">Supplier</div>
            </div>
        </div>

    </div>
</body>
</html>
