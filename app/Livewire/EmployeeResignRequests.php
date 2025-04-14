<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\EmpResignations;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class EmployeeResignRequests extends Component
{
    public $activeSection = 'All';
    public $totalActiveEmpList;
    public $totalResignReq;
    public $showApproveModal = false;
    public function toggleSection($tab)
    {
        $this->activeSection = $tab;
    }
    public function mount()
    {
        $this->getEmployeeResignRequests();
    }
    public function getEmployeeResignRequests()
    {
        $employeeId = auth()->guard('hr')->user()->emp_id;
        $companyId = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');
        $this->totalResignReq = EmpResignations::with('employee')->get();
    }

    public $actionType = '';
    public $selectedRequestId;
    //toggle approve model
    public function toggleApproveModal($type = null, $id = null)
    {
        $this->showApproveModal = !$this->showApproveModal;
        $this->actionType = $type;
        $this->selectedRequestId = $id;
    }
    public function closeModal()
    {
        $this->showApproveModal = false;
    }

    public $comment;
    public $comments;

    //approve resign request
    public function confirmAction()
    {
        try {
            $request = EmpResignations::find($this->selectedRequestId);
            if (!$request) {
                FlashMessageHelper::flashError('request not found');
            }
            if ($this->actionType === 'approve') {
                // handle approve logic
                $request->status = 2;
                $request->approved_date = now();
            } elseif ($this->actionType === 'reject') {
                // handle reject logic
                $request->status = 3;
            }
            $request->comments = $this->comment;
            $request->save();
            FlashMessageHelper::flashSuccess(ucfirst($this->actionType).'ed' . 'successfully!');
            // Reset modal state
            $this->reset(['showApproveModal', 'selectedRequestId', 'actionType', 'comment']);
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\QueryException) {
                // Handle database query exceptions
                Log::error("Database error getting leaves of employee: " . $e->getMessage());
                FlashMessageHelper::flashError('Database connection error occurred. Please try again later.');
            } elseif (strpos($e->getMessage(), 'Call to a member function store() on null') !== false) {
                // Display a user-friendly error message for null image
                FlashMessageHelper::flashError('An error occured while getting leave data.');
            } elseif ($e instanceof \Illuminate\Http\Client\RequestException) {
                // Handle network request exceptions
                Log::error("Network error registering employee: " . $e->getMessage());
                FlashMessageHelper::flashError('Network error occurred. Please try again later.');
            } elseif ($e instanceof \PDOException) {
                // Handle database connection errors
                Log::error("Database connection error registering employee: " . $e->getMessage());
                FlashMessageHelper::flashError('Database connection error. Please try again later.');
            } else {
                // Handle other generic exceptions
                Log::error("Error registering employee: " . $e->getMessage());
                FlashMessageHelper::flashError('Failed to get leave data. Please try again later.');
            }
            // Redirect the user back to the registration page or any other appropriate action
            return redirect()->back();
        }
    }
    public function render()
    {
        return view('livewire.employee-resign-requests', [
            'totalResignReq' => $this->totalResignReq
        ]);
    }
}
