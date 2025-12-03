<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Purchase Order</title>
    <style>
        @page { size: A4; margin: 28mm 20mm; }
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #111; font-size: 12px; }
        .header { text-align: center; margin-bottom: 16px; }
        .logo { height: 80px; }
        .title { font-size: 24px; font-weight: 700; margin: 10px 0 16px; }
        .meta { width: 100%; margin-bottom: 12px; }
        .meta td { vertical-align: top; }
        .meta .label { font-weight: 700; }
        .section-title { text-align: center; font-weight: 700; margin: 10px 0; }
        .info-table { width: 100%; }
        .info-table td { width: 50%; vertical-align: top; }
        .boxed { border: 1px solid #000; padding: 8px; border-radius: 0; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; }
        .table th { text-align: left; }
        .right { text-align: right; }
        .center { text-align: center; }
        .note { margin-top: 18px; }
        .footer { margin-top: 36px; }
        .signature { width: 40%; text-align: center; display: inline-block; }
        .line { border-top: 1px solid #000; margin-top: 40px; }
    </style>
</head>
<body>
    <div class="header">
        @php
            $logo = public_path(config('company.logo_path'));
        @endphp
        @if(file_exists($logo))
            <img src="{{ $logo }}" alt="Logo" class="logo">
        @endif
        <div>Phone : {{ config('company.phone') }} &nbsp;&nbsp; Gmail: {{ config('company.email') }}</div>
        <div class="title">PURCHASE ORDER</div>
    </div>

    <table class="meta">
        <tr>
            <td class="label">Purchase Order Number:</td>
            <td>{{ str_pad((string)$po->id, 6, '0', STR_PAD_LEFT) }}</td>
            <td class="label">PO ID:</td>
            <td>{{ str_pad((string)$po->id, 6, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td class="label">Date:</td>
            <td>{{ $po->created_at?->format('Y-m-d') }}</td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <div class="section-title">Supplier:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Buyer Info:</div>
    <table class="info-table">
        <tr>
            <td>
                <div class="boxed">
                    <div><strong>Supplier Name:</strong> {{ $supplier->name ?? '' }}</div>
                    <div><strong>Address:</strong> {{ $supplier->address ?? '' }}</div>
                    <div><strong>Number:</strong> {{ $supplier->phone ?? '' }}</div>
                    <div><strong>Email:</strong> {{ $supplier->email ?? '' }}</div>
                </div>
            </td>
            <td>
                <div class="boxed">
                    <div><strong>Buyer Name:</strong> {{ config('company.manager.name') ?: ($buyer?->name ?? 'Admin') }}</div>
                    <div><strong>Address:</strong> {{ config('company.manager.address') ?: ($buyer?->address ?? '') }}</div>
                    <div><strong>Contact Per:</strong> {{ config('company.manager.contact_person') ?: ($buyer?->contact_person ?? '') }}</div>
                    <div><strong>Contact No:</strong> {{ config('company.manager.phone') ?: ($buyer?->phone ?? '') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th class="center" style="width:40px;">No.</th>
                <th>Description</th>
                <th class="center" style="width:60px;">QTY</th>
                <th class="center" style="width:80px;">Unit</th>
                <th class="right" style="width:120px;">Unit Price</th>
                <th class="right" style="width:140px;">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $grand = 0; @endphp
            @foreach($items as $i => $item)
                @php
                    $lineTotal = ($item->unit_price ?? 0) * ($item->quantity ?? 0);
                    $grand += $lineTotal;
                @endphp
                <tr>
                    <td class="center">{{ $i + 1 }}.</td>
                    <td>{{ $item->product?->name ?? '' }}</td>
                    <td class="center">{{ $item->quantity }}</td>
                    <td class="center">{{ $item->unit ?? $item->product?->unit ?? '' }}</td>
                    <td class="right">₱ {{ number_format($item->unit_price ?? 0, 2) }}</td>
                    <td class="right">₱ {{ number_format($lineTotal, 2) }}</td>
                </tr>
            @endforeach
            @for($r = count($items); $r < 4; $r++)
                <tr>
                    <td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td>
                </tr>
            @endfor
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="right"><strong>Grand TOTAL:</strong></td>
                <td class="right"><strong>₱ {{ number_format($grand, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="note">
        <strong>Notes:</strong>
        <div>[Add any specific instructions or notes here.]</div>
    </div>

    <div class="footer">
        <div class="signature">
            <div class="line"></div>
            Buyer
        </div>
        <div class="signature" style="float: right;">
            <div class="line"></div>
            Supplier
        </div>
    </div>
</body>
</html>
