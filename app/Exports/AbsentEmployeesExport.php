<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsentEmployeesExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $missingData;

    public function __construct($missingData)
    {
        $this->missingData = $missingData;
    }

    public function collection()
    {
        return new Collection($this->missingData);
    }

    public function headings(): array
    {
        return ['Emp ID', 'Absent Date'];
    }
}
