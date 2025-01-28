<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;

class AssetExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $selectedEmployeeId;

    public function __construct($selectedEmployeeId)
    {
        $this->selectedEmployeeId = $selectedEmployeeId;
    }

    public function collection()
    {
        return Asset::where('emp_id', $this->selectedEmployeeId)->get();
    }
  
}
