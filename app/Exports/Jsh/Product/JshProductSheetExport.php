<?php

namespace  App\Exports\Jsh\Product;

use App\Enums\EnumTypeMat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JshProductSheetExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{

    protected $data;
    protected $dateRange;
    protected $title;
    protected $shift;
    protected $type;
    // protected $product;

    public function __construct($data, $dateRange, $title, $shift, $type)
    {
        $this->data = $data;
        $this->dateRange = $dateRange;
        $this->title = $title;
        $this->shift = $shift;
        $this->type = $type;
        // $this->product = $product;
    }

    public function collection()
    {
        return $this->data;
    }

    public function title(): string
    {
        return $this->title;
    }
    public function headings(): array
    {
        $isAdditive = ($this->type === EnumTypeMat::Additive->value);

        $row1 = ['Shift: ' . ($this->shift ?: 'D & N')];
        $row2 = ['Product: ' . ($this->data->first()->product_name ?: '-')];
        $row3 = ['Material Name'];
        $row4 = [''];
        foreach ($this->dateRange as $date) {
            $formattedDate = \Carbon\Carbon::parse($date)->format('d/m/Y');
            if ($isAdditive) {
                // Tambah 2 kolom untuk Additive
                $row3[] = $formattedDate;
                $row3[] = '';
                $row4[] = 'P.ADJ';
                $row4[] = 'ADJ';
            } else {
                // Tambah 1 kolom untuk Raw Material
                $row3[] = $formattedDate;
                $row4[] = '';
            }
        }
        $row3[] = 'Subtotal';
        return [
            $row1,
            $row2,
            [],
            $row3,
            $row4
        ];
    }

    public function map($row): array
    {
        $isAdditive = ($this->type === EnumTypeMat::Additive->value);
        $mapped = [
            $row->material_name,
        ];

        foreach ($this->dateRange as $date) {
            $dateKey = str_replace('-', '_', $date);
            if ($isAdditive) {
                // Untuk Additive, kita harus mengirim 2 nilai (P.ADJ dan ADJ)
                // Menggunakan alias yang sudah kita buat di Repository
                $preAlias = 'pre_date_' . $dateKey;
                $adjAlias = 'date_' . $dateKey;

                $mapped[] = $row->$preAlias ?? 0; // Masuk ke kolom P.ADJ
                $mapped[] = $row->$adjAlias ?? 0; // Masuk ke kolom ADJ
            } else {
                // Untuk Raw Material, tetap 1 nilai saja
                $alias = 'date_' . $dateKey;
                $mapped[] = $row->$alias ?? 0;
            }
        }

        $mapped[] = $row->subtotal;
        return $mapped;
    }
    public function styles(Worksheet $sheet)
    {
        $isAdditive = ($this->type === EnumTypeMat::Additive->value);
        $lastCol = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        // 1. Logika Merging Dinamis untuk Additive
        if ($isAdditive) {
            $columnIndex = 2; // Mulai dari kolom B (Material Name adalah kolom A)

            foreach ($this->dateRange as $date) {
                // Konversi indeks ke huruf kolom (2 -> B, 3 -> C, dst)
                $startCol = Coordinate::stringFromColumnIndex($columnIndex);
                $endCol = Coordinate::stringFromColumnIndex($columnIndex + 1);

                // Gabungkan baris ke-3 (Tanggal) untuk dua kolom (P.ADJ & ADJ)
                $sheet->mergeCells("{$startCol}4:{$endCol}4");

                // Loncat 2 kolom untuk tanggal berikutnya
                $columnIndex += 2;
            }
        }

        // 2. Styling Header dan Tabel
        $styleArray = [
            // Baris 1: Informasi Shift
            1 => [
                'font' => ['bold' => true, 'size' => 12],
            ],
            2 => ['font' => ['bold' => true, 'size' => 12]],
            // Baris 3 & 4: Header Tabel
            'A4:' . $lastCol . ($isAdditive ? '5' : '4') => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID, // Dark background seperti di web
                ],
            ],
        ];

        // 3. Menambahkan Border ke seluruh data
        $sheet->getStyle("A4:{$lastCol}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        return $styleArray;
    }
}
