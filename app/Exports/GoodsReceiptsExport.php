<?php

namespace App\Exports;

use App\Models\GoodReceipt;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GoodsReceiptsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithCustomStartCell
{
    public function collection()
    {
        return GoodReceipt::with(['purchaseOrder.supplier', 'createBy', 'approvedBy'])
            ->orderByDesc('id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'STT',
            'Mã phiếu nhập',
            'Mã đơn nhập',
            'Nhà cung cấp',
            'Ngày nhận hàng',
            'Người tạo',
            'Đã thanh toán',
        ];
    }

   public function map($receipt): array
{
    static $index = 0;

    return [
        ++$index,
        $receipt->code ?? 'Không có mã',
        $receipt->purchaseOrder->code ?? 'Không rõ',
        $receipt->purchaseOrder->supplier->name ?? 'Không rõ',
        $receipt->receipt_date ? \Carbon\Carbon::parse($receipt->receipt_date)->format('d/m/Y') : 'Không rõ',
        $receipt->createBy->name ?? 'Không rõ',
        number_format($receipt->total_amount, 0, ',', '.') . ' ₫',
    ];
}

    public function startCell(): string
    {
        return 'A2';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'DANH SÁCH PHIẾU NHẬP');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        $sheet->getStyle('A2:I2')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A2:I2')->getFill()->setFillType('solid')->getStartColor()->setRGB('0070C0');
        $sheet->getStyle('A2:I2')->getAlignment()->setHorizontal('center');

        $sheet->getStyle('A2:I100')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2:I100')->getAlignment()->setVertical('center');

        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }

    public function title(): string
    {
        return 'Danh sách phiếu nhập';
    }
}
