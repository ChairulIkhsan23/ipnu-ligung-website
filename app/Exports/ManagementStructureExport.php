<?php

namespace App\Exports;

use App\Models\ManagementStructure;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ManagementStructureExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
{
    public function collection()
    {
        return ManagementStructure::orderBy('position')->orderBy('name')->get();
    }

    public function headings(): array
    {
        return [
            'NO ANGGOTA',
            'NAMA LENGKAP',
            'JABATAN',
            'ALAMAT',
            'NO TELEPON',
            'MOTTO',
            'TAHUN MULAI',
            'TAHUN SELESAI',
            'STATUS',
            'TANGGAL DIBUAT',
        ];
    }

    public function map($structure): array
    {
        return [
            $structure->nomor_anggota ?? '-',
            $structure->name,
            $structure->position,
            $structure->alamat,
            $structure->no_telp ?? '-',
            $structure->motto ?? '-',
            $structure->start_year,
            $structure->end_year ?: 'Sekarang',
            $structure->status ? 'AKTIF' : 'TIDAK AKTIF',
            $structure->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk header (baris pertama)
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => '2C3E50'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
            
            // Style untuk seluruh sel
            'A:J' => [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, // NO ANGGOTA
            'B' => 30, // NAMA
            'C' => 25, // JABATAN
            'D' => 40, // ALAMAT
            'E' => 20, // NO TELEPON
            'F' => 40, // MOTTO
            'G' => 15, // TAHUN MULAI
            'H' => 15, // TAHUN SELESAI
            'I' => 15, // STATUS
            'J' => 20, // TANGGAL DIBUAT
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Auto size semua kolom
                foreach (range('A', 'J') as $column) {
                    $event->sheet->getColumnDimension($column)->setAutoSize(false);
                }
                
                // Set tinggi baris header
                $event->sheet->getRowDimension(1)->setRowHeight(30);
                
                // Set wrap text untuk kolom tertentu
                $event->sheet->getStyle('D')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('F')->getAlignment()->setWrapText(true);
                
                // Freeze header row
                $event->sheet->freezePane('A2');
            },
        ];
    }
}