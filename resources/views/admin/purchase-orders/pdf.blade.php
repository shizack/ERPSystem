    <style>
        @page { size: A4; margin: 18mm 16mm; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11pt; color: #1f2937; background: #f5f7fb; margin: 0; padding: 0; }
        .page { width: 100%; max-width: 960px; margin: 0 auto 24px; background: #fff; padding: 22px 22px 28px; border: 1px solid #e5e7eb; border-radius: 8px; }
        .header { text-align: center; border-bottom: 3px solid #0a84ff; padding-bottom: 16px; margin-bottom: 18px; }
        .title { font-size: 22pt; font-weight: 800; letter-spacing: 2px; color: #111827; margin: 8px 0 6px; }
        .company-name { font-size: 14pt; font-weight: 700; color: #0a84ff; margin-bottom: 4px; }
        .company-sub { font-size: 10.5pt; color: #6b7280; }
        .company-info { font-size: 9.5pt; color: #6b7280; margin-top: 4px; }
        .logo { height: 64px; margin-bottom: 6px; }
        /* PDF-safe card layout using tables instead of CSS grid */
        .cards { width: 100%; border-spacing: 12px 0; }
        .card { border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px; background: #f9fafb; }
        .card h3 { margin: 0 0 8px; font-size: 11pt; font-weight: 700; color: #111827; }
        .row { width: 100%; }
        .row .label { font-weight: 700; color: #374151; }
        .row .value { color: #1f2937; text-align: right; }
        .section-title { font-size: 11pt; font-weight: 800; letter-spacing: .8px; color: #0a84ff; margin: 20px 0 8px; border-left: 4px solid #0a84ff; padding-left: 10px; text-transform: uppercase; }
        table.items { width: 100%; border-collapse: collapse; border: 1px solid #e5e7eb; }
        table.items thead { background: #0a84ff; color: #fff; }
        table.items th, table.items td { padding: 9px; font-size: 10pt; }
        table.items th { text-align: left; font-weight: 700; }
        table.items td { border-top: 1px solid #e5e7eb; color: #1f2937; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .muted { color: #6b7280; font-size: 9.5pt; }
        /* Totals block without overflow/radius issues */
        .totals { margin-top: 14px; width: 100%; max-width: 340px; float: right; border: 1px solid #e5e7eb; }
        .totals table { width: 100%; border-collapse: collapse; }
        .totals td { border-top: 1px solid #e5e7eb; padding: 9px 12px; font-size: 10.5pt; }
        .totals tr:first-child td { border-top: none; background: #f9fafb; font-weight: 700; }
        .totals .grand td { background: #0a84ff; color: #fff; font-weight: 800; }
        /* Signatures simplified */
        .signatures { width: 100%; margin-top: 48px; }
        .signatures td { width: 33%; text-align: center; padding: 0 8px; }
        .sig-line { border-top: 2px solid #111827; margin-top: 28px; padding-top: 8px; font-weight: 700; font-size: 10pt; }
        .footer { text-align: center; margin-top: 28px; font-size: 9.5pt; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 12px; clear: both; }
    </style>
        .signatures { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 18px; margin-top: 60px; }
        .sig-box { text-align: center; }
        .sig-line { border-top: 2px solid #111827; margin-top: 28px; padding-top: 8px; font-weight: 700; font-size: 10pt; }
        .footer { text-align: center; margin-top: 30px; font-size: 9.5pt; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 12px; }
    </style>
</head>
<body>
    @php
        $logoPath = public_path(config('company.logo_path'));
        $hasLogo = file_exists($logoPath);
        $companyName = config('company.name', config('app.name', 'Company'));
        $companyPhone = config('company.phone', '');
        $companyEmail = config('company.email', '');
        $companyAddress = config('company.address', '');
        $contactPerson = trim(($po->user->first_name ?? 'Admin').' '.($po->user->last_name ?? ''));
    @endphp

    <div class="page">
        <div class="header">
            @if($hasLogo)
                <img src="{{ $logoPath }}" alt="Logo" class="logo">
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

        <table class="cards">
            <tr>
                <td style="width:50%;">
                    <div class="card">
                        <h3>Purchase Order Information</h3>
                        <table style="width:100%;">
                            <tr><td class="label">PO Number:</td><td class="value text-right">{{ $po->po_number ?? str_pad((string)$po->id, 6, '0', STR_PAD_LEFT) }}</td></tr>
                            <tr><td class="label">PO Date:</td><td class="value text-right">{{ $po->created_at?->format('m/d/Y') }}</td></tr>
                            <tr><td class="label">Request ID:</td><td class="value text-right">{{ str_pad((string)$po->id, 6, '0', STR_PAD_LEFT) }}</td></tr>
                            <tr><td class="label">Total Amount:</td><td class="value text-right">₱{{ number_format($po->total_amount, 2) }}</td></tr>
                        </table>
                    </div>
                </td>
                <td style="width:50%;">
                    <div class="card">
                        <h3>Buyer Information</h3>
                        <table style="width:100%;">
                            <tr><td class="label">Company:</td><td class="value text-right">{{ $companyName }}</td></tr>
                            <tr><td class="label">Department:</td><td class="value text-right">Purchasing Department</td></tr>
                            <tr><td class="label">Address:</td><td class="value text-right">{{ $companyAddress ?: 'N/A' }}</td></tr>
                            <tr><td class="label">Contact Person:</td><td class="value text-right">{{ $contactPerson ?: 'Admin' }}</td></tr>
                            <tr><td class="label">Phone:</td><td class="value text-right">{{ $companyPhone ?: '(+63) 900-000-0000' }}</td></tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <div class="card" style="margin-top: 6px;">
            <h3>Supplier / Vendor</h3>
            <table style="width:100%;">
                <tr><td class="label">Supplier Name:</td><td class="value text-right">{{ $supplier->name ?? 'N/A' }}</td></tr>
                <tr><td class="label">Address:</td><td class="value text-right">{{ $supplier->address ?? 'N/A' }}</td></tr>
                <tr><td class="label">Contact Person:</td><td class="value text-right">{{ $supplier->contact_person ?? 'N/A' }}</td></tr>
                <tr><td class="label">Email:</td><td class="value text-right">{{ $supplier->email ?? 'N/A' }}</td></tr>
                <tr><td class="label">Phone:</td><td class="value text-right">{{ $supplier->phone ?? 'N/A' }}</td></tr>
            </table>
        </div>

        <div class="section-title">Items</div>
        <table class="items">
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
                @php $grand = 0; @endphp
                @foreach($items as $index => $item)
                    @php $lineTotal = ($item->quantity ?? 0) * ($item->unit_price ?? 0); $grand += $lineTotal; @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->product?->name ?? 'N/A' }}</strong><br>
                            <span class="muted">{{ $item->product?->product_code ?? $item->product?->product_id ?? '' }}</span>
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-center">{{ $item->product?->unit ?? $item->unit ?? '' }}</td>
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
                    <td class="text-right">₱{{ number_format($po->total_amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Tax</td>
                    <td class="text-right">₱0.00</td>
                </tr>
                <tr>
                    <td>Shipping</td>
                    <td class="text-right">₱0.00</td>
                </tr>
                <tr class="grand">
                    <td>Grand Total</td>
                    <td class="text-right">₱{{ number_format($po->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        <div style="clear: both;"></div>

        <table class="signatures" cellspacing="0" cellpadding="0">
            <tr>
                <td><div class="sig-line">Property Custodian</div></td>
                <td><div class="sig-line">{{ $contactPerson ?: 'Admin' }}<br>Buyer</div></td>
                <td><div class="sig-line">Supplier</div></td>
            </tr>
        </table>

        <div class="footer">
            Thank you for your business! This is a computer-generated document. No signature is required.
        </div>
    </div>
</body>
</html>
