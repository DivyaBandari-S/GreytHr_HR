<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Loans extends Component
{
    public $activeButton = 'General';
    public $employeeType = 'active';
    public $isShowHelp = true;
    public $showContainer = false;
    public $showSearch = true;
    public $search = null;


    public $selectedEmployee = null;

    public $employees;

    public function toogleHelp()
    {
        $this->isShowHelp = !$this->isShowHelp;
    }

    public function setActiveTab($tab)
    {

        $this->activeButton = $tab;
    }
    public function hideContainer()
    {
        $this->showSearch = true;
        $this->showContainer = false;
    }
    public function filterEmployeeType()
    {
        $this->loadEmployees(); // Reload employees when type changes
    }
    public function loadEmployees()
    {

        if ($this->employeeType === 'active') {
            $this->employees = EmployeeDetails::whereIn('employee_status', ['active', 'on-probation'])->get();
        } else {
            $this->employees = EmployeeDetails::whereIn('employee_status', ['terminated', 'resigned'])->get();
        }
    }

    public function searchFilter()
    {
        if ($this->search !== '') {
            $this->showContainer = true; // Show the container when the search is triggered

            // Search with the existing term
            $this->employees = EmployeeDetails::where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('emp_id', 'like', '%' . $this->search . '%'); // Ensure `like` for partial match
            })
                ->where(function ($query) {
                    if ($this->employeeType === 'active') {
                        $query->whereIn('employee_status', ['active', 'on-probation']);
                    } else {
                        $query->whereIn('employee_status', ['terminated', 'resigned']);
                    }
                })
                ->get();

            // If no results found, the container should still be shown to display the message

        } else {
            // If search term is empty, hide the container and reload the employees
            $this->showContainer = false; // Hide the container
            $this->loadEmployees(); // Reload current employees
        }
    }
    public function mount()
    {
        $previousUrl = Session::get('previous_url');
        //    dd('url',$previousUrl);
        // $selectedEmployee = session('selectedEmployee', null);
        // $this->selectEmployee($selectedEmployee);
    }

    public function selectEmployee($employeeId)
    {
        // Check if the employee is already selected
        $this->selectedEmployee = $employeeId; // Change here
        Log::info('Selected Employee: ', [$this->selectedEmployee]);
        if (is_null($this->selectedEmployee)) {
            $this->showSearch = true;
            $this->search = null;
            $this->selectedEmployee = null;
        } else {
            $this->showSearch = false;
            $this->showContainer = false;
        }
    }


    public function render()
    {
        $selectedEmployeesDetails = $this->selectedEmployee ?
            EmployeeDetails::where('emp_id', $this->selectedEmployee)->get() : [];

        return view('livewire.loans', [
            'selectedEmployeesDetails' => $selectedEmployeesDetails,
        ]);
    }
}
