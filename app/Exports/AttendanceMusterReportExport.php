<?php

namespace App\Exports;

use App\Models\EmployeeDetails;
use App\Models\HolidayCalendar;
use App\Models\SwipeRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class AttendanceMusterReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $fromDate;
    protected $toDate;

    protected $employees;

    public function __construct($fromDate, $toDate,$employees)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->employees=$employees;
    }

    public function collection()
    {
        Log::info('Starting collection for AttendanceMusterReportExport');

        $fromDate = Carbon::parse($this->fromDate);
        $toDate = Carbon::parse($this->toDate);

        $data = [];


       
        

        $sno = 1;
        for ($date = $fromDate; $date->lte($toDate); $date->addDay()) {
            $attendanceDate = $date->toDateString();
            Log::info('Processing date: ' . $attendanceDate);

            $holiday = HolidayCalendar::where('date', $attendanceDate)->exists();
            $day = $date->format('l');

            foreach ($this->employees as $emp) {
                $swiperecordin = SwipeRecord::where('emp_id', $emp->emp_id)->whereDate('created_at', $attendanceDate)->where('in_or_out', 'IN')->first();
                $swiperecordout = SwipeRecord::where('emp_id', $emp->emp_id)->whereDate('created_at', $attendanceDate)->where('in_or_out', 'OUT')->first();

                $status1 = 'A';
                $status2 = 'A';
                $inTime = 'NA';
                $outTime = 'NA';

                if ($day == 'Saturday' || $day == 'Sunday') {
                    $status1 = $status2 = 'Off';
                } elseif ($holiday) {
                    $status1 = $status2 = 'H';
                } else {
                    if ($swiperecordin) {
                        $status1 = 'P';
                        $inTime = $swiperecordin->swipe_time;
                    }
                    if ($swiperecordout) {
                        $status2 = 'P';
                        $outTime = $swiperecordout->swipe_time;
                    }
                }

                $manager = EmployeeDetails::where('emp_id', $emp->manager_id)->first(['first_name', 'last_name','emp_id']);
                $manager_name = $manager ? ucwords(strtolower($manager->first_name)) . ' ' . ucwords(strtolower($manager->last_name)) : 'NA';
                $manager_id= $manager ? $manager->emp_id : 'NA';
                $data[] = [
                    $sno++,
                    ucwords(strtolower($emp->first_name)) . ' ' . ucwords(strtolower($emp->last_name)),
                    $emp->emp_id,
                    Carbon::parse($emp->hire_date)->format('jS F Y'),
                    $manager_id,
                    $manager_name,
                    Carbon::parse($attendanceDate)->format('jS F Y'),
                    $day,
                    $status1,
                    $status2,
                    $inTime,
                    $outTime,
                    // Add other fields as needed
                ];
            }
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            ['Attendance Muster Report from ' . Carbon::parse($this->fromDate)->format('jS F, Y') . ' to ' . Carbon::parse($this->toDate)->format('jS F, Y')],
            ['SNO', 'Name', 'Employee No', 'Date of Joining', 'Manager No', 'Manager Name', 'Attendance Date', 'Day', 'Session1', 'Session2', 'In Time [Asia/Kolkata]', 'Out Time [Asia/Kolkata]'],
            // Add other headings as needed
        ];
    }

    public function styles($sheet)
    {
        $styleArray = [
            // Apply bold and larger font to the first heading row (Report Title)
            1    => ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => 'right']], // Heading 1 (Report Title)
            2    => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],  // Heading 2 (Column Headers)
            
            // Style for the second heading (Employee ID, Name, etc.)
            'A2:F2' => [
                'font' => ['bold' => true,  'color' => ['argb' => '000000'], ],
                'alignment' => ['horizontal' => 'center'],
                'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFFF00']], // Yellow background
            ],
        ];
        
        return $styleArray;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                // Merge cells for the first row (Report Title)
                $sheet->mergeCells('A1:L1');
                
                // Apply additional styles to the first row
                $sheet->getDelegate()->getStyle('A1:L1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                ]);

                // Apply background color for the title row
                $sheet->getDelegate()->getStyle('A1:L1')->applyFromArray([
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF99']], // Light yellow
                ]);
                $sheet->getDelegate()->getStyle('A2:L2')->applyFromArray([
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF00']], // Light yellow
                ]);
                $event->sheet->getStyle('A1')->getFont()->setBold(true);
            },
        ];
    }
}
