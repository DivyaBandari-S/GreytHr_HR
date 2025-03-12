<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;

class ShiftSummaryExport implements FromArray, WithHeadings, WithStyles, WithEvents, WithColumnFormatting
{
    protected $data;
    protected $fromDate;
    protected $toDate;
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct(array $data, $fromDate, $toDate)
    {
        $this->data = $data;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public function array(): array
    {
        return collect($this->data)->map(function ($item) {
            return [
                'emp_id' => $item['emp_id'],
                'name' => $item['name'],
                'total_days' => $item['total_days'],
                'work_days' => $item['work_days'],
                'off_day' => $item['off_day'],
                'shift_schedule' => $item['shift_schedule'],
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return [
            ['Shift Summary Report from ' . Carbon::parse($this->fromDate)->format('jS F, Y') . ' to ' . Carbon::parse($this->toDate)->format('jS F, Y')],
            ['Employee ID', 'Name', 'Total Days', 'Work Days', 'Off Days', 'Shift Schedule'],
        ];
    }
    public function styles($sheet)
    {
        $styleArray = [
            // Apply bold and larger font to the first heading row (Report Title)
            1    => ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => 'right']], // Heading 1 (Report Title)
            2    => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],  // Heading 2 (Column Headers)
            
            // Style for the second heading (Employee ID, Name, etc.)
            'A2:F2' => [
                'font' => ['bold' => true,  'color' => ['argb' => '000000'], ],
                'alignment' => ['horizontal' => 'center'],
                'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFFF00']], // Yellow background
            ],
        ];
        $row = 3; // Start from row 2 (after the headings)
        foreach ($this->data as $item) {
            if ($item['employee_status'] == 'Resigned' || $item['employee_status'] == 'Terminated'||$item['employee_status'] == 'resigned' || $item['employee_status'] == 'terminated') {
                // Apply red font color to the row
                $sheet->getStyle("A$row:F$row")->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
            }
            $row++;
        }
        return $styleArray;
    }

    public function columnFormats(): array
    {
        return [
            'C' => '0',  // Format 'Total Days', 'Work Days', 'Off Days' as numbers
            'D' => '0',
            'E' => '0',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                
                // Merge cells for the first row (Report Title)
                $sheet->mergeCells('A1:F1');
                
                // Apply additional styles to the first row
                $sheet->getDelegate()->getStyle('A1:F1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                ]);

                // Apply background color for the title row
                $sheet->getDelegate()->getStyle('A1:F1')->applyFromArray([
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF99']], // Light yellow
                ]);
                $sheet->getColumnDimension('A')->setWidth(30); // Adjust this value based on the width you need
                $sheet->getColumnDimension('B')->setWidth(30);
                $sheet->getColumnDimension('C')->setWidth(15);
                $sheet->getColumnDimension('D')->setWidth(15);
                $sheet->getColumnDimension('E')->setWidth(15);
                $sheet->getColumnDimension('F')->setWidth(30);
            },
        ];
    }
   
}
