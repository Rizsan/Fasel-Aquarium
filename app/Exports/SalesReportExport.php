<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    ShouldAutoSize
{
    protected Collection $orders;
    protected array $summary;

    public function __construct(Collection $orders, array $summary)
    {
        $this->orders  = $orders;
        $this->summary = $summary;
    }

    public function collection(): Collection
    {
        return $this->orders;
    }

    public function title(): string
    {
        return 'Laporan Penjualan';
    }

    public function headings(): array
    {
        return [
            'No',
            'Order Number',
            'Customer',
            'Email',
            'Tanggal',
            'Total',
            'Status',
            'Metode Pembayaran',
            'Jumlah Item',
        ];
    }

    public function map($order): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $order->order_number,
            $order->user->name ?? '-',
            $order->user->email ?? '-',
            $order->created_at->format('d/m/Y H:i'),
            $order->total_price,
            strtoupper($order->status),
            $order->payment_type ?? '-',
            $order->items->sum('quantity'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '1E40AF']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
