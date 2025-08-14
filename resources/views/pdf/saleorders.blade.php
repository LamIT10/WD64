<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phiếu xuất kho - Đơn {{ $order->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 14px;
            line-height: 1.4;
            color: #000;
            background: white;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }

        .date {
            text-align: right;
            margin-bottom: 10px;
            font-style: italic;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .order-id {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .info-section {
            margin-bottom: 25px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 8px 12px;
            border: 1px solid #000;
            vertical-align: top;
        }

        .info-table .label {
            background-color: #f5f5f5;
            font-weight: bold;
            width: 30%;
        }

        .items-section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .items-table th,
        .items-table td {
            padding: 10px 8px;
            border: 1px solid #000;
            text-align: left;
        }

        .items-table th {
            background-color: #e0e0e0;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }

        .items-table .text-center {
            text-align: center;
        }

        .items-table .text-right {
            text-align: right;
        }

        .summary {
            float: right;
            width: 300px;
            margin-bottom: 30px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 8px 12px;
            border: 1px solid #000;
        }

        .summary-table .label {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .summary-table .total-row {
            background-color: #e0e0e0;
            font-weight: bold;
        }





        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        .signatures-wrap {
            /* hàng ký – bảo đảm không bị tách trang */
            clear: both;
            /* chắc chắn đứng dưới khối summary float */
            margin-top: 50px;
        }

        .signatures-table {
            width: 100%;
            table-layout: fixed;
            /* chia 3 cột đều */
            border-collapse: collapse;
        }

        .signatures-table td {
            text-align: center;
            vertical-align: bottom;
            padding: 10px 8px;
            /* không vẽ viền để giống mẫu */
        }

        .sig-title {
            font-weight: 700;
            text-transform: uppercase;

            display: block;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
            font-style: italic;
        }

        .signature-note {
            font-style: italic;
            display: block;

        }

        .page {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        @media print {

            .signatures-wrap,
            .signatures-table,
            .signatures-table tr,
            .signatures-table td {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;

        }
    </style>
</head>

<body>
    <div class="page">
        <div class="date">
            Ngày {{ date('d') }} tháng {{ date('m') }} năm {{ date('Y') }}
        </div>

        <div class="header">
            <div class="title">Phiếu xuất kho</div>
            <div class="order-id">Đơn xuất số: {{ $order->id }}</div>
        </div>

        <div class="info-section">
            <table class="info-table">
                <tr>
                    <td class="label">📞 Số điện thoại</td>
                    <td>{{ optional($order->customer)->phone ?? 'Chưa xác định' }}</td>
                </tr>
                <tr>
                    <td class="label">📧 Email</td>
                    <td>{{ optional($order->customer)->email ?? 'Chưa xác định' }}</td>
                </tr>
                <tr>
                    <td class="label">📍 Địa chỉ giao hàng</td>
                    <td>
                        @if (!empty($order->address_delivery))
                            {{ $order->address_delivery }}
                        @else
                            {{ optional($order->customer)->ward ? optional($order->customer)->ward . ', ' : '' }}
                            {{ optional($order->customer)->province ?? 'Chưa xác định' }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">📌 Trạng thái</td>
                    <td>
                        @switch($order->status)
                            @case('pending')
                                Chờ duyệt
                            @break

                            @case('shipped')
                                Đã giao hàng
                            @break

                            @case('completed')
                                Hoàn thành
                            @break

                            @case('cancelled')
                                Đã hủy
                            @break

                            @default
                                {{ $order->status }}
                        @endswitch
                    </td>
                </tr>
                @if ($order->status === 'cancelled' && $order->note)
                    <tr>
                        <td class="label">📝 Lý do từ chối</td>
                        <td style="color: #dc2626;">{{ $order->note }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <div class="items-section">
            <div class="section-title">Chi tiết đơn hàng</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-center">Đơn vị</th>
                        <th class="text-right">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>
                                {{ optional(optional($item->productVariant)->product)->name ?? '---' }}
                                @if (optional($item->productVariant)->attributes && optional($item->productVariant)->attributes->count() > 0)
                                    - {{ optional($item->productVariant)->attributes->pluck('name')->join(' - ') }}
                                @endif
                            </td>
                            <td class="text-center">{{ $item->quantity_ordered }}</td>
                            <td class="text-center">{{ optional($item->unit)->name ?? '---' }}</td>
                            <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }} ₫</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="clearfix">
            <div class="summary">
                <table class="summary-table">
                    <tr>
                        <td class="label">Tổng tiền đơn:</td>
                        <td class="text-right">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</td>
                    </tr>
                    <tr>
                        <td class="label">Đã thanh toán trước:</td>
                        <td class="text-right">{{ number_format($order->pay_before ?? 0, 0, ',', '.') }} ₫</td>
                    </tr>
                    <tr>
                        <td class="label">Đã thanh toán sau:</td>
                        <td class="text-right">{{ number_format($order->pay_after ?? 0, 0, ',', '.') }} ₫</td>
                    </tr>
                    <tr class="total-row">
                        <td class="label">Cần thanh toán:</td>
                        <td class="text-right">
                            {{ number_format(($order->total_amount ?? 0) - ($order->pay_before ?? 0) - ($order->pay_after ?? 0), 0, ',', '.') }}
                            ₫
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="signatures-wrap">
            <table class="signatures-table">
                <tr>
                    <td>
                        <span class="sig-title">Người lập phiếu</span>

                        <span class="sig-note">(Ký, ghi rõ họ tên)</span>
                    </td>
                    <td>
                        <span class="sig-title">Người nhận hàng</span>

                        <span class="sig-note">(Ký, ghi rõ họ tên)</span>
                    </td>
                    <td>
                        <span class="sig-title">Thủ kho</span>

                        <span class="sig-note">(Ký, ghi rõ họ tên)</span>
                    </td>
                </tr>
            </table>
        </div>

</body>

</html>
