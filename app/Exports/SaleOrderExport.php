<?php

namespace App\Exports;

use App\Models\SaleOrder;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class SaleOrderExport implements FromCollection, WithHeadings, WithEvents, WithCustomStartCell
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return SaleOrder::select(
            'sale_orders.id',
            'customers.id as customer_id',
            'customers.name',
            'customers.address',
            'sale_orders.status',
            'sale_orders.total_amount'
        )->leftJoin('customers', 'sale_orders.customer_id', '=', 'customers.id')
            ->get();
    }
    public function headings(): array
    {
        return ['ID đơn đặt hàng', 'Id khách hàng', 'Tên khách hàng', 'Địa chỉ khách hàng', 'Trạng thái đơn hàng', 'Tổng tiền'];
    }
    public function startCell(): string
    {
        return 'A2';
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->mergeCells('A1:F1');
                $sheet->setCellValue('A1', 'BÁO CÁO ĐƠN HÀNG XUẤT BÁN');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $headerRange = 'A2:F2';
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
                $dataRange = 'A3:F' . $highestRow;
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
                $sheet->getStyle('F3:F' . $highestRow)->getNumberFormat()
                    ->setFormatCode('#,##0.00');


                foreach (range('A', 'F') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
                $sheet->getRowDimension(1)->setRowHeight(30);
                $sheet->getRowDimension(2)->setRowHeight(20);
            },
        ];
    }
}
