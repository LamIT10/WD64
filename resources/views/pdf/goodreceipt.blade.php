<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Phiếu nhập - {{ $receipt->code }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #111;
            margin: 20px;
        }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .mb-2 { margin-bottom: 8px; }
        .mb-4 { margin-bottom: 16px; }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th, .table td {
            border: 1px solid #333;
            padding: 6px;
        }
     
        .right { text-align: right; }
        .box {
            border: 1px solid #aaa;
            padding: 8px;
            margin-bottom: 16px;
        }
        .grid {
            display: table;
            width: 100%;
        }
        .grid .row {
            display: table-row;
        }
        .grid .cell {
            display: table-cell;
            padding: 4px 8px;
            vertical-align: top;
        }
        .footer-sign {
            margin-top: 30px;
            display: table;
            width: 100%;
            text-align: center;
        }
        .footer-sign > div {
            display: table-cell;
            width: 33%;
        }
    </style>
</head>
<body>
    @php
        $supplier = $receipt->purchaseOrder?->supplier;
        $fmt = fn($v) => number_format((float)$v, 0, ',', '.') . ' ₫';
        $attrsToStr = fn($attrs) => collect($attrs)->map(fn($a) => $a['name'].' '.$a['value'])->implode(' - ');
    @endphp

    <!-- Ngày in phiếu (góc trên phải) -->
    <div style="text-align:right; font-size:13px; margin-bottom:10px;">
        Ngày {{ now()->format('d') }} tháng {{ now()->format('m') }} năm {{ now()->format('Y') }}
    </div>

    <div class="text-center mb-4">
        <h2>PHIẾU NHẬP KHO</h2>
        <p class="mb-2">Mã phiếu: <strong>{{ $receipt->code }}</strong></p>
    </div>

    <!-- Box thông tin nhà cung cấp -->
    <div class="box">
        <div class="grid">
            <div class="row">
                <div class="cell bold"> Số điện thoại:</div>
                <div class="cell">{{ $supplier?->phone ?? '—' }}</div>
            </div>
            <div class="row">
                <div class="cell bold"> Email:</div>
                <div class="cell">{{ $supplier?->email ?? '—' }}</div>
            </div>
            <div class="row">
                <div class="cell bold"> Địa chỉ:</div>
                <div class="cell">{{ $supplier?->address ?? '—' }}</div>
            </div>
            <div class="row">
                <div class="cell bold"> Ngày nhận:</div>
                <div class="cell">{{ optional($receipt->receipt_date)->format('d/m/Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Chi tiết đơn -->
    <h4 class="mb-2">Chi tiết đơn hàng</h4>
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn vị</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receipt->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{ $item->productVariant->product->name }}
                        @if($item->productVariant->attributes)
                            - {{ $attrsToStr($item->productVariant->attributes) }}
                        @endif
                    </td>
                    <td class="right">{{ (float)$item->quantity_received }}</td>
                    <td class="text-center">{{ $item->unit?->name ?? '' }}</td>
                    <td class="right">{{ $fmt($item->subtotal) }}</td>
                </tr>
            @endforeach
</tbody>
    </table>

    <!-- Tổng tiền -->
    @php
        $total = (float)$receipt->total_amount;
    @endphp
    <div style="text-align:right; margin-top: 14px; font-weight: bold; font-size: 14px;">
        Tổng tiền đơn hàng: {{ $fmt($total) }}
    </div>

    <!-- Chữ ký -->
    <div class="footer-sign">
        <div>
            <div class="bold">Người lập phiếu</div>
            <div>(Ký, ghi rõ họ tên)</div>
        </div>
        <div>
            <div class="bold">Thủ kho</div>
            <div>(Ký, ghi rõ họ tên)</div>
        </div>
        <div>
            <div class="bold">Kế toán</div>
            <div>(Ký, ghi rõ họ tên)</div>
        </div>
    </div>
</body>
</html>
