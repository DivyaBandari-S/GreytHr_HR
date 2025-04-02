<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class LateArrivalsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'Employee ID',
            'Name',
            'Swipe_Time',
            'Late_By',
        ];
    }
}
