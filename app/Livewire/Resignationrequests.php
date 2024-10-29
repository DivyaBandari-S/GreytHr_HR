<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EmployeeDetails;
use App\Models\EmpResignations;
use App\Models\Hr;

class Resignationrequests extends Component
{
    public $hrRequests;
    public $loginEmployee;
    public $showImageDialog=false;

    public function mount()
    {
        $employeeId = auth()->guard('hr')->user()->emp_id;

        // $this->loginEmployee = Hr::where('emp_id', $employeeId)->select('emp_id', 'employee_name')->first();
        $companyId = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');

        $this->getHrRequests($companyId);
    }

    public function render()
    {
        return view('livewire.resignationrequests');
    }

    public function getHrRequests($companyIds)
    {
        // Retrieve HR requests where the company_id contains any of the given company IDs
        $this->hrRequests = EmpResignations::join('employee_details', 'employee_details.emp_id', '=', 'emp_resignations.emp_id')
            ->where('emp_resignations.status', 'Pending')
            ->where(function ($query) use ($companyIds) {
                foreach ($companyIds as $companyId) {
                    $query->orWhereRaw('JSON_CONTAINS(employee_details.company_id, ?)', [json_encode($companyId)]);
                }
            }) ->select(
                'emp_resignations.emp_id',
                'emp_resignations.resignation_date',
                'emp_resignations.reason',
                'emp_resignations.signature',
                'emp_resignations.created_at',
                'employee_details.first_name',
                'employee_details.last_name'
            )
            ->get();
        // dd( $this->hrRequests);
        // Count the number of HR requests
        // $this->hrRequestsCount = $this->hrRequests->count();
    }
}
