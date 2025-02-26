<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class AccountsJVExport implements FromCollection, WithHeadings
{
    protected $totals;

    public function __construct($totals)
    {
        $this->totals = $totals;
    }

    // Data to be exported
    public function collection(): Collection
    {
        return collect([
            ['BASIC', 'BASIC', $this->formatAmount($this->totals['basic']), '0.00', ''],
            ['HRA', 'HRA', $this->formatAmount($this->totals['hra']), '0.00', ''],
            ['CONVEYANCE', 'CONVEYANCE', $this->formatAmount($this->totals['conveyance']), '0.00', ''],
            ['SPECIAL ALLOWANCE', 'SPECIAL ALLOWANCE', $this->formatAmount($this->totals['special_allowance']), '0.00', ''],
            ['PF', 'PF', '0.00', $this->formatAmount($this->totals['pf']), ''],
            ['EMPLOYEER PF', 'EMPLOYEER PF', '0.00', $this->formatAmount($this->totals['employeer_pf']), ''],
            ['SALARY PAYABLE', 'SALARY PAYABLE', '0.00', $this->formatAmount($this->totals['salary_payable']), ''],
        ]);
    }

    // Define headings
    public function headings(): array
    {
        return ['JV Item', 'Account Code', 'Debit', 'Credit', 'Employee Name'];
    }

    // Helper function to format numbers
    private function formatAmount($amount)
    {
        return '₹' . number_format($amount, 2);
    }
}
