<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\DamageDetails;
use App\Models\EmployeeDetails;
use App\Models\FineAndDamage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CreateFineComponent extends Component
{
    public $employees;

    public $offence_date;

    public $act_or_omission;

    public $show_cause;

    public $show_cause_notice_date;

    public $name;

    public $amount;

    public $fine_realised_date;

    public $remarks;
    public $searchTerm='';
    public $searchEmployee=0;

    public $selectedEmployeeId;

    public $selectedEmployeeFirstName;

    public $selectedEmployeeLastName;

    public $fineId;

    public $empId;

    public $offenceDate;

    public $actOrOmission;

    public $empName;

    public $viewMode;

    public $showCauseDate;
    public function mount($id = null,$viewMode = false)
    {
        if ($id) {
            // Fetch the fine for editing
            $fine = DamageDetails::findOrFail($id);
            $this->viewMode = $viewMode;
        
            $this->fineId = $fine->id;

            $this->empId = $fine->emp_id;
            $fname=EmployeeDetails::where('emp_id',$this->empId)->value('first_name');
            $lname=EmployeeDetails::where('emp_id',$this->empId)->value('last_name');
            $this->empName=$fname.' '.$lname;
            $this->amount = $fine->amount_of_fine;
            $this->act_or_omission=$fine->act_or_omission;
            $this->offence_date = Carbon::parse($fine->offence_date)->format('Y-m-d');
            $this->actOrOmission = $fine->act_or_omission;
            $this->show_cause = $fine->is_show_cause;
            $this->show_cause_notice_date = Carbon::parse($fine->show_cause_date)->format('Y-m-d');
            $this->fine_realised_date = Carbon::parse($fine->fine_realized_date)->format('Y-m-d');
            $this->remarks = $fine->remarks;
        }
    }

    protected $rules = [
        'remarks' => ['required', 'regex:/^[a-zA-Z\s]+$/'], // Only letters and spaces allowed
    ];
    public function updateselectedEmployee($empId)
    {
    
        $this->selectedEmployeeId=$empId;
      
        $this->selectedEmployeeFirstName= EmployeeDetails::where('emp_id',$empId)->value('first_name');
        $this->selectedEmployeeLastName= EmployeeDetails::where('emp_id',$empId)->value('last_name');
        $this->searchEmployee=0;
    }

    public function updateoffenceDate()
    {
        $this->offence_date=$this->offence_date;
    }

    public function updateActOrOmission()
    {
        $this->act_or_omission=$this->act_or_omission;
    }

    public function updateShowCause()
    {
        $this->show_cause=$this->show_cause;
    }

    public function updateShowCauseNoticeDate()
    {
        $this->show_cause_notice_date=$this->show_cause_notice_date;
    }

    public function updateName()
    {
        $this->name=$this->name;
    }

    public function updateAmount()
    {
        $this->amount=$this->amount;
    }

    public function updatefineRealizedDate()
    {
        $this->fine_realised_date=$this->fine_realised_date;
    }

    public function updateRemarks()
    {
        $this->remarks=$this->remarks;
    }

    public function submitFineDetails()
{
    Log::info('Welcome to submitFineDetails Method');
    // Log incoming data
    $this->validate();
    Log::info('submitFineDetails: Start', [
        'fineId' => $this->fineId,
        'empId' => $this->empId,
        'amount' => $this->amount,
        'offence_date' => $this->offence_date,
        'act_or_omission' => $this->act_or_omission,
        'name_of_the_person'=>$this->name,
        'show_cause' => $this->show_cause,
        'show_cause_notice_date' => $this->show_cause_notice_date,
        'remarks' => $this->remarks,
        'fine_realised_date' => $this->fine_realised_date
    ]);

    if ($this->fineId) {
        // Update the existing fine record
        Log::info('submitFineDetails: Updating fine record', ['fineId' => $this->fineId]);

        
            $fine = FineAndDamage::findOrFail($this->fineId);

            // Log the data about the fine
            Log::info('submitFineDetails: Found fine record', ['fine' => $fine]);

            $fine->update([
                'emp_id' => $this->empId,
                'amount_of_fine' => $this->amount,
                'offence_date' => $this->offence_date,
                'act_or_omission' => $this->act_or_omission,
                'is_show_cause' => $this->show_cause,
                'show_cause_date' => $this->show_cause_notice_date,
                'remarks' => $this->remarks,
                'fine_realized_date' => $this->fine_realised_date,
            ]);

            Log::info('submitFineDetails: Fine record updated successfully', ['fineId' => $this->fineId]);

            FlashMessageHelper::flashSuccess('Fine Record Updated Successfully');
        
    } else {
        // Store the new fine record
        Log::info('submitFineDetails: Creating new fine record');

        
            FineAndDamage::create([
                'emp_id' => $this->selectedEmployeeId,
                'amount_of_fine' => $this->amount,
                'offence_date' => $this->offence_date,
                'name_of_the_person'=>$this->name,
                'act_or_omission' => $this->act_or_omission,
                'is_show_cause' => $this->show_cause,
                'show_cause_date' => $this->show_cause_notice_date,
                'remarks' => $this->remarks,
                'fine_realized_date' => $this->fine_realised_date,
            ]);

            Log::info('submitFineDetails: New fine record created successfully');

            FlashMessageHelper::flashSuccess('Fine Record Created Successfully');
        
    }

    // Log the redirect action
    Log::debug('submitFineDetails: Redirecting to fine-and-damage route');

    return redirect()->route('fine-and-damage');
}

    public function closeFineDetails()
    {
        return redirect()->route('fine-and-damage');
    }
    public function getEmployeesByType()
    {
       
        $query = EmployeeDetails::query();
        // Example logic to fetch employees based on the selected type
        $query->where('employee_status', 'active');
        if (!empty($this->searchTerm)) {
            $query->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('emp_id', 'like', '%' . $this->searchTerm . '%');
            });
        }
        
        // Get the filtered employees
        return $query->get();
    
    }
    public function closeEmployeeBox()
    {
        $this->searchEmployee=0;
       
       
    }

    
    public function clearSelectedEmployee()
    {
        $this->selectedEmployeeId='';
        $this->selectedEmployeeFirstName='';
        $this->selectedEmployeeLastName='';
        $this->searchTerm='';
    }
    public function searchforEmployee()
    {
          $this->searchEmployee=1;
       
    }
    public function updatesearchTerm()
    {
        $this->searchTerm=$this->searchTerm;
    }
    public function render()
    {
        $this->employees = $this->getEmployeesByType();
        return view('livewire.create-fine-component');
    }
}
