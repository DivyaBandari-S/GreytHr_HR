<?php

namespace App\Exports;

use App\Models\EmployeeDetails;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeeDirectoryExport implements FromCollection, WithHeadings, WithMapping,WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return EmployeeDetails::select(
            'emp_id',
            'first_name',
            'last_name',
            'hire_date',
            'employee_status',
            'emergency_contact',
            'email'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Employee ID',
            'Employee Name',
            'Employee Join Date',
            'Employee Status',
            'Employee Phone Number',
            'Employee Email'
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->emp_id ?? 'NA',
            ucwords(strtolower($employee->first_name ?? 'NA')) . ' ' . ucwords(strtolower($employee->last_name ?? 'NA')),
            !empty($employee->hire_date) ? Carbon::parse($employee->hire_date)->format('jS F, Y') : 'NA',
            ucwords(strtolower($employee->employee_status)) ?? 'NA',
            !empty($employee->emergency_contact) ? " " . $employee->emergency_contact : 'NA', // Ensures the number is stored as text
            $employee->email ?? 'NA',
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getColumnDimension('A')->setWidth(15); // Employee ID
        $sheet->getColumnDimension('B')->setWidth(25); // Employee Name
        $sheet->getColumnDimension('C')->setWidth(20); // Employee Join Date
        $sheet->getColumnDimension('D')->setWidth(15); // Employee Status
        $sheet->getColumnDimension('E')->setWidth(20); // Employee Phone Number
        $sheet->getColumnDimension('F')->setWidth(60); // Employee Email
        foreach ($sheet->getRowIterator() as $row) {
            $rowIndex = $row->getRowIndex();
            $statusCell = $sheet->getCell("D" . $rowIndex)->getValue(); // Column "D" is Employee Status

            if (in_array(strtolower($statusCell), ['resigned', 'terminated'])) {
                $sheet->getStyle("A{$rowIndex}:G{$rowIndex}")->getFont()->getColor()->setARGB('FF8C00'); // Red color
            }
        }
    }
}
