<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class QuickSalaryExport implements FromCollection, WithHeadings
{
    protected $salaryData;
    protected $allSalaryDetails;

    public function __construct($salaryData, $allSalaryDetails)
    {
        $this->salaryData = $salaryData;
        $this->allSalaryDetails = $allSalaryDetails;
    }

    public function collection()
    {
        $exportData = [];
    
        foreach ($this->salaryData as $empId => $data) {
            // Prevent null error by setting a default empty object
            $employee = $this->allSalaryDetails->firstWhere('emp_id', $empId) ?? (object) [];
    
            $exportData[] = [
                'S.No'              => count($exportData) + 1,
                'Employee ID'       => $empId,
                'Employee Name'     => ($employee->first_name ?? 'N/A') . ' ' . ($employee->last_name ?? ''),
                'Joining Date'      => !empty($employee->hire_date) ? Carbon::parse($employee->hire_date)->format('d-m-Y') : 'N/A',
                'Days in Month'     => $employee->total_working_days ?? 'N/A',
                'LOP Days'          => $employee->lop_days ?? 'N/A',
                'Basic'             => $data['salary']['basic'] ?? 0,
                'HRA'               => $data['salary']['hra'] ?? 0,
                'Conveyance'        => $data['salary']['conveyance'] ?? 0,
                'Special Allowance' => $data['salary']['special_allowance'] ?? 0,
                'Gross Salary'      => $data['salary']['gross'] ?? 0,
                'PF'                => $data['salary']['pf'] ?? 0,
                'Income Tax'        => $data['salary']['esi'] ?? 0,
                'Pro Tax'           => $data['salary']['professional_tax'] ?? 0,
                'Total Deductions'  => $data['salary']['total_deductions'] ?? 0,
                'Net Pay'           => $data['salary']['net_pay'] ?? 0,
            ];
        }
    
        return new Collection($exportData);
    }
    

    public function headings(): array
    {
        return [
            'S.No', 'Employee ID', 'Employee Name', 'Joining Date', 'Days in Month', 'LOP Days',
            'Basic', 'HRA', 'Conveyance', 'Special Allowance', 'Gross Salary', 'PF', 'Income Tax', 
            'Pro Tax', 'Total Deductions', 'Net Pay'
        ];
    }
}
