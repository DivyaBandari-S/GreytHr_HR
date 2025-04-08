<?php

namespace App\Livewire;

use App\Exports\EmployeeDirectoryExport;
use App\Models\EmployeeDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\SimpleExcel\SimpleExcelWriter;

class EmployeeDirectory extends Component
{
    public $companyId;

    public $records;

    public $records_excel;

    public $showHelp=false;

    public $selectedCategory = 'all';

    public $selectedEmploymentStatus='all';

    public $selectedEmploymentFilter='all';

    public $employeeFilter;

    public $selectedOption = 'all'; 
    public $employeeStatus;

   

   
    
    public function updateSelectedEmploymentStatus()
    {
        try {
            $this->employeeStatus = $this->selectedEmploymentStatus;
        } catch (\Exception $e) {
            Log::error('Error in updateSelectedEmploymentStatus method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while updating the employment status. Please try again later.');
        }
    }
    public function updateselectedEmploymentFilter()
    {
        try {
            $this->employeeFilter = $this->selectedEmploymentFilter;
        } catch (\Exception $e) {
            Log::error('Error in updateselectedEmploymentFilter method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while updating the employment filter. Please try again later.');
        }
    }

    public function updateSelected($option)
    {
        $this->selectedOption = $option;
    }
    public function hideHelp()
    {
   
        try {
            $this->showHelp=true;
            $this->employeeFilter = $this->selectedEmploymentFilter;
        } catch (\Exception $e) {
            Log::error('Error in updateselectedEmploymentFilter method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while updating the employment filter. Please try again later.');
        }
    }
   

    public function showhelp()
    {
        try {
            $this->showHelp = false;
        } catch (\Exception $e) {
            Log::error('Error in showHelp method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while trying to show the help section. Please try again later.');
        }

    }
    public function exportToExcel()
    {
        try {
            return Excel::download(new EmployeeDirectoryExport, 'employee_directory_export.xlsx');
        } catch (\Exception $e) {
            Log::error('Error in exportToExcel method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while exporting to Excel. Please try again later.');
            return redirect()->back();
        }
    }
    public function render()
    {
                try {
                   
                    

                    if ($this->employeeFilter == 'past_employees') {
                        $this->records = EmployeeDetails::where('employee_details.employee_status', 'resigned')
                            ->orWhere('employee_details.employee_status', 'terminated')
                            ->get();
                    } elseif ($this->employeeFilter == 'current_employees') {
                        $this->records = EmployeeDetails::where('employee_details.employee_status', 'active')
                            ->get();
                    } else {
                        $this->records = EmployeeDetails::get();
                        
                    }

                    if ($this->employeeStatus == 'consultant') {
                        $this->records = EmployeeDetails::where(function ($query) {
                                $query->where('employee_details.job_role', 'LIKE', '%consultant%')
                                    ->orWhere('employee_details.job_role', 'LIKE', '%Consultant%');
                            })
                            ->get();
                    } elseif ($this->employeeStatus == 'resigned') {
                        $this->records = EmployeeDetails::select('employee_details.*')
                            ->join('hr', 'employee_details.company_id', '=', 'hr.company_id')
                            ->where('employee_status', 'resigned')
                            ->get();
                    } elseif ($this->employeeStatus == 'active') {
                        $this->records = EmployeeDetails::where('employee_status', 'active')
                            ->get();
                    } elseif ($this->employeeStatus == 'new_joinee') {
                        $this->records = EmployeeDetails::select('employee_details.*')
                            ->join('hr', 'employee_details.company_id', '=', 'hr.company_id')
                            ->whereYear('employee_details.hire_date', 2024)
                            ->get();
                    } elseif ($this->employeeStatus == 'interns') {
                        $this->records = EmployeeDetails::select('employee_details.*')
                            ->join('hr', 'employee_details.company_id', '=', 'hr.company_id')
                            ->where('employee_details.job_role', 'intern')
                            ->get();
                    }

                  
                    return view('livewire.employee-directory');
                } catch (\Exception $e) {
                    Log::error('Error in render method: ' . $e->getMessage());
                    session()->flash('error', 'An error occurred while loading employee records. Please try again later.');
                    $this->records = collect(); // Set records to an empty collection in case of error
                    return view('livewire.employee-directory');
                }
    }

}
