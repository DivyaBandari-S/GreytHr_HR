<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WebSignInExport implements FromCollection,WithHeadings
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
     * Define headings for Excel.
     */
    public function headings(): array
    {
        return [
            'Employee Name',
            'Employee ID',
            'Swipe Time',
            'Swipe Date',
            'Shift Time',
            'Sign In/Out',
            'Device Used',
            'Swipe Location',
            'Remarks',
        ];
    }

    /**
     * Fetch and format data.
     */
    public function collection()
    {
        $data = collect();

        foreach ($this->SignedInEmployees as $swipe) {
            foreach ($swipe['swipe_log'] as $log) {
                $data->push([
                    ucwords(strtolower($swipe['employee']->first_name)) . ' ' . ucwords(strtolower($swipe['employee']->last_name)),
                    $swipe['employee']->emp_id,
                    Carbon::parse($log->swipe_time)->format('H:i:s'),
                    Carbon::parse($log->created_at)->format('jS F, Y'),
                    Carbon::parse($swipe['employee']->shift_start_time)->format('H:i a') . ' to ' . Carbon::parse($swipe['employee']->shift_end_time)->format('H:i a'),
                    $log->in_or_out === 'IN' ? 'IN' : 'OUT',
                    $this->getDeviceUsed($log),
                    !empty($log->swipe_location) ? ucwords(strtolower(preg_replace('/[^A-Za-z0-9]/', ' ', $log->swipe_location))) : 'NA',
                    !empty($log->swipe_remarks) ? $log->swipe_remarks : 'NA',
                ]);
            }
        }

        return $data;
    }

    /**
     * Determine the sign-in device used.
     */
    private function getDeviceUsed($log)
    {
        if ($log->in_or_out === 'IN' && ($log->sign_in_device === 'Laptop/Desktop' || $log->sign_in_device === 'Laptop')) {
            return 'Web Sign In';
        } elseif ($log->in_or_out === 'IN' && $log->sign_in_device === 'Mobile') {
            return 'Mobile Sign In';
        } elseif ($log->in_or_out === 'OUT' && ($log->sign_in_device === 'Laptop/Desktop' || $log->sign_in_device === 'Laptop')) {
            return 'Web Sign Out';
        } elseif ($log->in_or_out === 'OUT' && $log->sign_in_device === 'Mobile') {
            return 'Mobile Sign Out';
        } else {
            return 'Web Sign In';
        }
    }
}
