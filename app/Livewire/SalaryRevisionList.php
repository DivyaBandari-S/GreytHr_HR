<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmpSalaryRevision;
use App\Models\SalaryRevision;
use Livewire\Component;

class SalaryRevisionList extends Component
{
    public $isShowHelp = true;
    public $isPageOne = true;
    public $salaryRevisions;
    public $search = '';
    public $status = '';

    public $selectedEmployees = [];
    public $selectAll = false;

    public function toogleHelp()
    {
        $this->isShowHelp = !$this->isShowHelp;
    }

    public function updateSelectAll($value)
    {

        if ($this->selectAll) {
            $this->selectedEmployees = array_column($this->salaryRevisions, 'id');
        } else {
            $this->selectedEmployees = [];
        }
        // dd($this->selectedEmployees);
    }
    public function updateSelectedEmployees()
    {
        if (count($this->selectedEmployees) === count($this->salaryRevisions)) {
            $this->selectAll = true;
        } else {
            $this->selectAll = false;
        }
        // dd( $this->selectedEmployees);
    }
    public function approveSalaryRevisions()
    {

        if ($this->selectedEmployees) {
            foreach ($this->selectedEmployees as $employee_id) {
                $salaryRevision = EmpSalaryRevision::findorfail($employee_id);
                if ($salaryRevision->status == 0) {
                    $salaryRevision->status = 1;
                    $salaryRevision->save();
                }
            }
            $this->getSalaryRevisions();
            FlashMessageHelper::flashSuccess('Accepted Salary Revision For Selected Employee Successfully');
        } else {
            FlashMessageHelper::flashWarning('Please Select Atleast One Salary Revision');
        }
        $this->selectedEmployees = [];
    }

    public function rejectSalaryRevisions()
    {

        if ($this->selectedEmployees) {
            foreach ($this->selectedEmployees as $employee_id) {
                $salaryRevision = EmpSalaryRevision::findorfail($employee_id);
                if ($salaryRevision->status == 0) {
                    $salaryRevision->status = 2;
                    $salaryRevision->save();
                }
            }
            $this->getSalaryRevisions();
            FlashMessageHelper::flashSuccess('Rejected Salary Revision For Selected Employee Successfully');
        } else {
            FlashMessageHelper::flashWarning('Please Select Atleast One Salary Revision');
        }
        $this->selectedEmployees = [];
    }

    public function mount()
    {
        $this->getSalaryRevisions();
    //     session()->put('greeting', 'Hello, Livewire!');
    
    }
    public function getSalaryRevisions()
    {
        // dd('ok');
        $query = EmpSalaryRevision::join('employee_details', 'employee_details.emp_id', 'salary_revisions.emp_id')
            ->select('salary_revisions.*', 'employee_details.first_name', 'employee_details.last_name');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('employee_details.first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('employee_details.last_name', 'like', '%' . $this->search . '%');
            });
        }
        if ($this->status !== null && $this->status !== ''){
            $query->where('salary_revisions.status', $this->status);
        }

        $this->salaryRevisions = $query->orderBy('salary_revisions.created_at', 'desc')->get()->toArray();
    }

    public function render()
    {
        return view('livewire.salary-revision-list');
    }
}
