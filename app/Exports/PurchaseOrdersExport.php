<?php

namespace App\Exports;

use App\Models\PurchaseOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;


class PurchaseOrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, WithColumnWidths, WithEvents
{
    protected $filters;

public function __construct(array $filters = [])
{
    $this->filters = $filters;
}
   public function collection()
{
    $query = PurchaseOrder::with(['supplier', 'user'])->orderByDesc('id');

    if (!empty($this->filters['code'])) {
        $query->where('code', 'like', '%' . $this->filters['code'] . '%');
    }

    if (!empty($this->filters['supplier'])) {
        $query->whereHas('supplier', function ($q) {
            $q->where('name', 'like', '%' . $this->filters['supplier'] . '%');
        });
    }

    if (!empty($this->filters['order_status']) || $this->filters['order_status'] === 0) {
        $query->where('order_status', $this->filters['order_status']);
    }

    if (!empty($this->filters['start'])) {
        $query->whereDate('created_at', '>=', $this->filters['start']);
    }

    if (!empty($this->filters['end'])) {
        $query->whereDate('created_at', '<=', $this->filters['end']);
    }

    return $query->get();
}
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
       
                $event->sheet->mergeCells('A1:H1');
                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

              
                $event->sheet->getStyle('A2:H2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '0070C0'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
            },
        ];
    }

    public function headings(): array
    {
        return [
            ['DANH SÁCH ĐƠN NHẬP HÀNG'],
            [
                'STT',
                'Mã đơn nhập',
                'Nhà cung cấp',
                'Ngày tạo đơn',
                'Người tạo',
                'Trạng thái',
                'Ngày giao dự kiến',
                'Tổng tiền',
            ]
        ];
    }

    public function map($order): array
    {
        static $index = 0;

        return [
            ++$index,
            $order->code ?? 'Không có mã',
            $order->supplier->name ?? 'Không rõ',
            optional($order->created_at)->format('d/m/Y'),
            $order->user->name ?? 'Không rõ',
            match ((int) $order->order_status) {
                0 => 'Chờ duyệt',
                1 => 'Đã duyệt',
                2 => 'Nhập một phần',
                3 => 'Đã hoàn thành',
                4 => 'Đã từ chối',
                default => ucfirst($order->order_status),
            },
            optional($order->order_date)->format('d/m/Y'),
            number_format($order->total_amount, 0, ',', '.') . ' ₫',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }

    public function title(): string
    {
        return 'Đơn nhập';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 15,
            'C' => 25,
            'D' => 15,
            'E' => 20,
            'F' => 18,
            'G' => 20,
            'H' => 18,
        ];
    }
}
