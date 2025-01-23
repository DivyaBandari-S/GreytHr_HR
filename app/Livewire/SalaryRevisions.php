<?php
// File Name                       : EmpLogin.php
// Description                     : This file contains the implementation employee salary revision
// Creator                         : Saragada Siva Kumar
// Email                           :
// Organization                    : PayG.
// Date                            : 2024-03-07
// Framework                       : Laravel (10.10 Version)
// Programming Language            : PHP (8.1 Version)
// Database                        : MySQL
// Models                          : EmployeeDetails,Hr,Finance,Admin,IT
namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\EmpSalaryRevision;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SalaryRevisions extends Component
{
    public $salaryRevisions;
    public $years = [];
    public $salaries = [];
    public $data = [];
    public $chartData;
    public $chartOptions;
    public $error = '';
    public $decryptedData = [];


    function formatDuration($days)
    {
        $years = intdiv($days, 365);
        $days %= 365;
        $months = intdiv($days, 30);
        $days %= 30;

        $formattedDuration = [];

        if ($years > 0) {
            $formattedDuration[] = $years . ' year' . ($years > 1 ? 's' : '');
        }
        if ($months > 0) {
            $formattedDuration[] = $months . ' month' . ($months > 1 ? 's' : '');
        }
        if ($days > 0) {
            $formattedDuration[] = $days . ' day' . ($days > 1 ? 's' : '');
        }

        return implode(' ', $formattedDuration);
    }


    public function render()
    {
        try {
            $emp_id = auth()->user()->emp_id;
            $this->salaryRevisions = EmpSalaryRevision::where('emp_id', $emp_id)->get();
            $previous_revision_date = null;


            foreach ($this->salaryRevisions as $revision) {

                $current_ctc = $revision->current_ctc;
                $revised_ctc = $revision->revised_ctc;
                $revision_date = Carbon::parse($revision->revision_date);
                $revision_type = $revision->revision_type;
                $reason = $revision->reason;
                $percentage_change = 0;
                $time_gap = 0;
                if ($current_ctc != 0) {
                    $percentage_change = round((($revised_ctc - $current_ctc) / $current_ctc) * 100, 1);
                }
                if ($previous_revision_date !== null) {
                    $time_diff_days = $previous_revision_date->diffInDays($revision_date);

                    $time_gap = $this->formatDuration($time_diff_days);
                }


                $this->decryptedData[] = [
                    'current_ctc' => $current_ctc,
                    'revised_ctc' => $revised_ctc,
                    'revision_date' => $revision_date,
                    'revision_type' => $revision_type,
                    'reason' => $reason,
                    'percentage_change' => round($percentage_change, 2),
                    'time_gap' => $time_gap,
                ];
                $previous_revision_date = $revision_date;
            }

            $this->decryptedData = array_reverse($this->decryptedData);
            $chartData = $this->getChartData();
            // dd( $chartData);

            return view('livewire.salary-revisions', [
                'chartData' => $chartData,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database errors
            return view('errors.connection-refused', ['errorMessage' => 'Database error occurred. Please try again later.']);
        } catch (\Exception $e) {
            // Handle general errors
            return view('errors.connection-refused', ['errorMessage' => 'An unexpected error occurred. Please try again.']);
        }
    }
    // Inside your Livewire component
    public function getChartData()
    {
        $dates = [];
        $revisedSalaries = [];

        foreach ($this->decryptedData as $data) {
            $dates[] = $data['revision_date']->format('Y-m-d'); // Format the date as desired
            $revisedSalaries[] = round($data['revised_ctc'] / 12, 2);
        }
        $dates = array_reverse($dates);
        $revisedSalaries = array_reverse($revisedSalaries);

        return [
            'labels' => $dates,
            'data' => $revisedSalaries,
        ];
    }
}
