<?php

namespace App\Exports;

use App\Models\EmployeeDetails;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SwipeLogsExport implements FromArray,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data;
    protected $headers;
    protected $title;

    public function __construct(array $data, array $headers, $title)
    {
        $this->data = $data;
        $this->headers = $headers;
        $this->title = $title;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headers;
    }
}
