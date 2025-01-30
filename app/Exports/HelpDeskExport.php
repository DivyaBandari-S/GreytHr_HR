<?php

namespace App\Exports;

use App\Models\HelpDesks;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HelpDeskExport implements FromCollection, WithHeadings, WithMapping
{
    protected $helpDesks;

    // Constructor to accept the filtered HelpDesks data
    public function __construct($helpDesks)
    {
        $this->helpDesks = $helpDesks;
    }

    public function collection()
    {
        return $this->helpDesks;
    }

    // Set the headings for the Excel sheet
    public function headings(): array
    {
        return [
            'ID', 'Employee Name', 'Employee ID', 'Company ID', 'Category', 'Subject', 'Description','active_comment' ,'Priority', 'Status', 'Created At'
        ];
    }

    // Map the data to the required columns
    public function map($helpDesk): array
    {
        return [
            $helpDesk->id,
            $helpDesk->emp ? $helpDesk->emp->first_name . ' ' . $helpDesk->emp->last_name : 'Unknown',
            $helpDesk->emp ? $helpDesk->emp->emp_id : 'N/A',
            $helpDesk->emp ? $helpDesk->emp->company_id : 'N/A',
            $helpDesk->category,
            $helpDesk->subject,
            $helpDesk->description,
            $helpDesk->active_comment,
            $helpDesk->priority,
            $helpDesk->status_name,
            $helpDesk->created_at->toDateTimeString()
        ];
    }
}
