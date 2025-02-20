<?php

namespace App\Exports;

use App\Models\GenerateLetter;
use App\Models\EmployeeDetails;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LettersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * Fetches the collection of GenerateLetter records
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return GenerateLetter::all(); // Or apply filters here as per your needs
    }

    /**
     * Define the headers for the Excel file
     * @return array
     */
    public function headings(): array
    {
        return [
            'Template Name',
            'Serial No',
            'Employee Name',
            'Prepared By',
            'Status',
            'Created At',
        ];
    }
    public function styles(Worksheet $sheet)
    {
        // Apply bold styling to the first row (headers)
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        // Optional: You can also adjust column width, etc.
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(20);
    }

    /**
     * Map the data to Excel columns
     * @param $letter
     * @return array
     */
    public function map($letter): array
    {
        // Decode the 'employees' field from JSON into an array
        $employees = json_decode($letter->employees, true);

        // Get employee names and join them with commas
        $employeeNames = array_map(function ($employee) {
            return $employee['name'];
        }, $employees);

        $employeeNamesStr = ucwords(strtolower(implode(', ', $employeeNames))); // Format employee names

       // Assuming 'prepared_by' is stored in the letter record
       $preparedBy = Auth::user()->emp_id;
        $name = EmployeeDetails::where('emp_id', $preparedBy)->first();
        $preparedByName = $name ? $name->first_name . ' ' . $name->last_name : 'Unknown';

        return [
            $letter->template_name,
            $letter->serial_no,
            $employeeNamesStr,
            $preparedByName,
            $letter->status,
            $letter->created_at->format('d M, Y'),
        ];
    }
}
