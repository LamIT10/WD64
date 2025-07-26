<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Barcode</title>
    <style>
        /* Giữ nguyên toàn bộ phần CSS như trước */
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --text-color: #333;
            --light-gray: #f5f5f5;
            --border-radius: 4px;
            --box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            color: var(--text-color);
        }

        .page {
            height: 277mm;
            position: relative;
        }

        .page:not(:last-child) {
            page-break-after: always;
        }

        .header {
            text-align: center;
            margin-bottom: 5mm;
            padding-bottom: 2mm;
            border-bottom: 1px solid var(--light-gray);
        }

        .header h1 {
            color: var(--primary-color);
            margin-bottom: 0;
            font-size: 18px;
        }

        .header p {
            color: var(--secondary-color);
            margin-top: 2px;
            font-size: 12px;
        }

        .barcode-container {
            width: 100%;
            margin-bottom: 10mm;
        }

        .barcode-row {
            display: table;
            width: 100%;
            margin-bottom: 5mm;
        }

        .barcode {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 1mm;
            box-sizing: border-box;
        }

        .barcode-inner {
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius);
            padding: 2mm;
            box-shadow: var(--box-shadow);
            height: 30mm;
        }

        .barcode svg {
            width: 100% !important;
            height: 15mm !important;
            display: block;
            margin: 0 auto;
        }

        .barcode-code {
            font-weight: bold;
            margin-bottom: 1mm;
            font-size: 10px;
            height: 4mm;
            overflow: hidden;
        }

        .barcode-number {
            font-size: 9px;
            margin-top: 1mm;
            word-break: break-all;
            height: 4mm;
        }

        .footer {
            text-align: center;
            padding-top: 2mm;
            border-top: 1px solid var(--light-gray);
            font-size: 10px;
            color: var(--secondary-color);
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        .barcode-empty {
            display: table-cell;
            width: 50%;
            visibility: hidden;
        }
    </style>
</head>

<body>
    @php
        $barcodes = $product->productVariants->filter(fn($v) => !empty($v->barcode))->values();
        $totalItems = $barcodes->count();
        $itemsPerPage = 10;
        $totalPages = ceil($totalItems / $itemsPerPage);
    @endphp

    @for ($page = 0; $page < $totalPages; $page++)
        @php
            $pageItems = $barcodes->slice($page * $itemsPerPage, $itemsPerPage);
        @endphp

        <div class="page">
            <div class="header">
                <h1>Quản lý mã vạch</h1>
                <p>Giấy Suvan - Hệ thống quản lý sản phẩm</p>
            </div>

            <div class="barcode-container">
                @foreach ($pageItems->chunk(2) as $row)
                    <div class="barcode-row">
                        @foreach ($row as $variant)
                            <div class="barcode">
                                <div class="barcode-inner">
                                    <div class="barcode-code">{{ $variant->code }}</div>
                                    <div>{!! \DNS1D::getBarcodeHTML($variant->barcode, 'C128') !!}</div>
                                    <div class="barcode-number">{{ $variant->barcode }}</div>
                                </div>
                            </div>
                        @endforeach
                        @if ($row->count() < 2)
                            <div class="barcode-empty"></div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="footer">
                <p>Trang {{ $page + 1 }}/{{ $totalPages }} | Hệ thống quản lý giấy Suvan © {{ date('Y') }}</p>
            </div>
        </div>
    @endfor


</body>

</html>
