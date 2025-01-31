<?php

namespace App\Exports;

use App\Models\EmpSalaryRevision;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeePeersExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $employees;

    public function __construct($employees)
    {
        $this->employees = $employees;
    }
    public function collection()
    {
        return collect($this->employees)->map(function ($employee, $index) {
            return [
                'S.No' => $index + 1,
                'Emp ID' => $employee['emp_id'],
                'Employee Name' => ucwords(strtolower($employee['emp_name'])),
                'Experience' => $employee['experience'],
                'Designation' => $employee['designation'],
                'Revision Date' => \Carbon\Carbon::parse($employee['revision_date'])->format('d M, Y'),
                'Revised CTC' => number_format($employee['revised_ctc'], 2),
                'Current CTC' => number_format($employee['current_ctc'], 2),
                'Percentage Change' => $employee['percentage_change'] >= 0
                    ? "+{$employee['percentage_change']} (" . number_format($employee['difference_amount'], 2) . ")"
                    : "{$employee['percentage_change']} (" . number_format($employee['difference_amount'], 2) . ")"
            ];
        });
    }
    public function headings(): array
    {
        return [
            'S.No',
            'Emp ID',
            'Employee Name',
            'Experience',
            'Designation',
            'Revision Date',
            'Revised CTC',
            'Current CTC',
            'Percentage Change'
        ];
    }
}
