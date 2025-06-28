<?php

namespace App\Exports;

use App\Models\SaleOrder;
use App\Models\SaleOrderItem;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SaleOrderExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Sale Orders' => new SaleOrdersSheet(),
            'Order Items' => new OrderItemsSheet(),
        ];
    }
}

class SaleOrdersSheet implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        return SaleOrder::select(
            'sale_orders.id',
            'customers.name as customer_name',
            'sale_orders.created_at',
            'sale_orders.status',
            'sale_orders.total_amount',
            'sale_orders.address_delivery'
        )
            ->leftJoin('customers', 'sale_orders.customer_id', '=', 'customers.id')
            ->get()
            ->map(function ($order) {
                $order->total_quantity = $order->items()->sum('quantity_ordered');
                $order->status = $this->translateStatus($order->status);
                $order->created_at = $order->created_at
                    ? Carbon::parse($order->created_at)->format('d/m/Y')
                    : 'Không xác định';
                return $order;
            });
    }

    public function headings(): array
    {
        return [
            'Mã đơn xuất',
            'Tên khách hàng',
            'Ngày đặt hàng',
            'Trạng thái',
            'Tổng tiền',
            'Địa chỉ giao hàng',
            'Tổng số lượng',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->mergeCells('A1:G1');
                $sheet->setCellValue('A1', 'BÁO CÁO ĐƠN HÀNG XUẤT BÁN');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $headerRange = 'A2:G2';
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => '1E90FF'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $highestRow = $sheet->getHighestRow() >= 3 ? $sheet->getHighestRow() : 3;
                $dataRange = 'A3:G' . $highestRow;
                $sheet->getStyle($dataRange)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $sheet->getStyle('E3:E' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');
                foreach (range('A', 'G') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
                $sheet->getRowDimension(1)->setRowHeight(30);
                $sheet->getRowDimension(2)->setRowHeight(20);
            },
        ];
    }

    private function translateStatus($status)
    {
        switch ($status) {
            case 'pending':
                return 'Chờ duyệt';
            case 'shipped':
                return 'Đang giao hàng';
            case 'completed':
                return 'Hoàn thành';
            case 'rejected':
                return 'Từ chối';
            default:
                return 'Không xác định';
        }
    }
}

class OrderItemsSheet implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        $items = SaleOrderItem::select(
            'sale_order_items.id',
            'sale_order_items.sales_order_id',
            'products.name as product_name',
            DB::raw("GROUP_CONCAT(attribute_values.name SEPARATOR ' - ') as attributes"),
            'sale_order_items.quantity_ordered',
            'units.name as unit_name',
            'sale_order_items.unit_price',
            'sale_order_items.subtotal'
        )
            ->leftJoin('product_variants', 'sale_order_items.product_variant_id', '=', 'product_variants.id')
            ->leftJoin('products', 'product_variants.product_id', '=', 'products.id')
            ->leftJoin('units', 'sale_order_items.unit_id', '=', 'units.id')
            ->leftJoin('product_variant_attribute_values', 'product_variants.id', '=', 'product_variant_attribute_values.product_variant_id')
            ->leftJoin('attribute_values', 'product_variant_attribute_values.attribute_value_id', '=', 'attribute_values.id')
            ->groupBy(
                'sale_order_items.id',
                'sale_order_items.sales_order_id',
                'products.name',
                'sale_order_items.quantity_ordered',
                'units.name',
                'sale_order_items.unit_price',
                'sale_order_items.subtotal'
            )
            ->get();

        return $items;
    }

    public function headings(): array
    {
        return [
            'Mã mục đơn hàng',
            'Mã đơn xuất',
            'Tên sản phẩm',
            'Thuộc tính',
            'Số lượng',
            'Đơn vị',
            'Đơn giá',
            'Thành tiền',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->mergeCells('A1:H1');
                $sheet->setCellValue('A1', 'CHI TIẾT MỤC ĐƠN HÀNG XUẤT BÁN');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $headerRange = 'A2:H2';
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => '1E90FF'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $highestRow = $sheet->getHighestRow() >= 3 ? $sheet->getHighestRow() : 3;
                $dataRange = 'A3:H' . $highestRow;
                $sheet->getStyle($dataRange)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $sheet->getStyle('G3:G' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('H3:H' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');
                foreach (range('A', 'H') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
                $sheet->getRowDimension(1)->setRowHeight(30);
                $sheet->getRowDimension(2)->setRowHeight(20);
            },
        ];
    }
}