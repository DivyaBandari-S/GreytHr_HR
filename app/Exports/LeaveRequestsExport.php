<?php

namespace App\Exports;

use App\Models\LeaveRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeaveRequestsExport implements FromCollection, WithHeadings
{
    protected $leaveRequests;

    public function __construct($leaveRequests)
    {
        $this->leaveRequests = $leaveRequests;
    }

    public function collection()
    {
        return $this->leaveRequests->map(function ($leaveRequest) {
            return [
                'id' => $leaveRequest->id,
                'created_at' => $leaveRequest->created_at->format('d M Y H:i'), 
                'from_date' => $leaveRequest->from_date,
                'to_date' => $leaveRequest->to_date,
                'days' => $leaveRequest->days,
                'leave_type' => $leaveRequest->leave_type,
                'status' => $leaveRequest->leave_status,
                'reason' => $leaveRequest->reason,
               
              
                // Ensure this attribute exists in your model
               // Format if necessary
            ];
        });
    }

    public function headings(): array
    {
        return [
            'SI.No',
            'Posted Date',
            'From Date',
            'To Date',
            'Days',
            'Leave Type',
            'Transaction Type',
            'Reason',
        ];
    }
}
