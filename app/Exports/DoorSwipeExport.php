<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DoorSwipeExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $SignedInEmployees;

    public function __construct($SignedInEmployees)
    {
        $this->SignedInEmployees = $SignedInEmployees;
    }

    /**
     * Define Excel headings.
     */
    public function headings(): array
    {
        return [
            'Employee Name',
            'Employee ID',
            'Swipe Time',
            'Swipe Date',
            'Shift Time',
            'Swipe Direction',
            'Swipe Type',
        ];
    }

    /**
     * Fetch and format the data.
     */
    public function collection()
    {
        $data = collect();

        foreach ($this->SignedInEmployees as $swipe) {
            foreach ($swipe['swipe_log'] as $log) {
                $data->push([
                    ucwords(strtolower($swipe['employee']->first_name)) . ' ' . ucwords(strtolower($swipe['employee']->last_name)),
                    $swipe['employee']->emp_id,
                    Carbon::parse($log->logDate)->format('H:i:s'),
                    Carbon::parse($log->logDate)->format('jS F Y'),
                    Carbon::parse($swipe['employee']->shift_start_time)->format('H:i a') . ' to ' . Carbon::parse($swipe['employee']->shift_end_time)->format('H:i a'),
                    strtoupper($log->Direction), // IN or OUT
                    $log->Direction === 'in' ? 'Door Swipe In' : 'Door Swipe Out',
                ]);
            }
        }

        return $data;
    }
}
