<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\DamageDetails;
use App\Models\EmployeeDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CreateDamageComponent extends Component
{
    public $searchTerm='';
    public $searchEmployee=0;

    public $show_cause_notice_date;
    public $show_cause;
    public $selectedEmployeeId;

    public $installment_number;

    public $remarks;
    public $recovery_last_installment_date;
    public $amount;
    public $name;

    public $viewMode;
    public $damage_or_loss_date;
    public $selectedEmployeeFirstName;

  
    public $recovery_first_installment_date;

    
    public $damage_or_loss_reason;
    public $selectedEmployeeLastName;

    public $employees;

    public $fineId;

    public $no_of_installments;
    public $empName;
    public $empId;
    protected $rules = [
        'remarks' => ['required', 'regex:/^[a-zA-Z\s]+$/'], // Only letters and spaces allowed
        'name' => ['required', 'regex:/^[a-zA-Z\s]+$/'],
    ];

    public function mount($id = null,$viewMode = false)
    {
     
        if ($id) {
            // Fetch the fine for editing
            $damage = DamageDetails::findOrFail($id);
            $this->viewMode = $viewMode;
        
            $this->fineId = $damage->id;

            $this->empId = $damage->emp_id;
            $fname=EmployeeDetails::where('emp_id',$this->empId)->value('first_name');
            $lname=EmployeeDetails::where('emp_id',$this->empId)->value('last_name');
            $this->empName=$fname.' '.$lname;
            $this->amount = $damage->amount_of_damage;
            $this->damage_or_loss_reason=$damage->damage_or_loss_reason;
            $this->damage_or_loss_date = Carbon::parse($damage->damage_or_loss_date)->format('Y-m-d');
            $this->installment_number=$damage->no_of_installments;
            $this->show_cause = $damage->show_cause;
            $this->name=$damage->name_of_the_person;
            $this->show_cause_notice_date = Carbon::parse($damage->show_cause_date)->format('Y-m-d');
            $this->no_of_installments = Carbon::parse($damage->no_of_installments)->format('Y-m-d');
            $this->recovery_first_installment_date = Carbon::parse($damage->recovery_first_installment_date)->format('Y-m-d');
            $this->recovery_last_installment_date = Carbon::parse($damage->recovery_last_installment_date)->format('Y-m-d');
            $this->remarks = $damage->remarks;
        }
    }
    public function searchforEmployee()
    {
          $this->searchEmployee=1;
       
    }
    public function updatesearchTerm()
    {
        $this->searchTerm=$this->searchTerm;
    }

    public function updateShowCauseNoticeDate()
    {
        $this->show_cause_notice_date=$this->show_cause_notice_date;
    }

    public function updateName()
    {
        $this->name=$this->name;
    }

    public function updateRemarks()
    {
        $this->remarks=$this->remarks;
    }
    public function updateRecoveryFirstInstallmentDate()
    {
        $this->recovery_first_installment_date=$this->recovery_first_installment_date;
    }

    public function updateRecoveryLastInstallmentDate()
    {
        $this->recovery_last_installment_date=$this->recovery_last_installment_date;
    }
    public function updateInstallmentNumber()
    {
        $this->installment_number=$this->installment_number;
    }
    public function updateAmount()
    {
        $this->amount=$this->amount;
    }
    public function updateShowCause()
    {
        $this->show_cause=$this->show_cause;
    }
    public function updateDamageOrLossReason()
    {
        $this->damage_or_loss_reason=$this->damage_or_loss_reason;
    }
    public function closeFineDetails()
    {
        return redirect()->route('damage-page');
    }

    
  
    public function submitFineDetails(): void
    {
      $this->validate();
      
    if ($this->fineId) {
        // Update the existing fine record
        Log::info('submitFineDetails: Updating fine record', ['fineId' => $this->fineId]);

        
            $damage = DamageDetails::findOrFail($this->fineId);

            // Log the data about the fine
            Log::info('submitFineDetails: Found fine record', ['damage' => $damage]);

            $damage->update([
               
                'amount_of_damage' => $this->amount,
                'damage_or_loss_date' => $this->damage_or_loss_date,
                'name_of_the_person'=>$this->name,
                'damage_or_loss_reason' => $this->damage_or_loss_reason,
                'show_cause' => $this->show_cause,
                'no_of_installments' => $this->installment_number,
                'show_cause_notice_date' => $this->show_cause_notice_date,
                'recovery_first_installment_date'=>$this->recovery_first_installment_date,
                'recovery_last_installment_date'=>$this->recovery_last_installment_date,
                'remarks' => $this->remarks,     
            ]);

            Log::info('submitFineDetails: Fine record updated successfully', ['fineId' => $this->fineId]);

            FlashMessageHelper::flashSuccess('Fine Record Updated Successfully');
        
    } else {
        // Store the new fine record
        Log::info('submitFineDetails: Creating new fine record');

        
        DamageDetails::create([
            'emp_id' => $this->selectedEmployeeId,
            'amount_of_damage' => $this->amount,
            'damage_or_loss_date' => $this->damage_or_loss_date,
            'name_of_the_person'=>$this->name,
            'damage_or_loss_reason' => $this->damage_or_loss_reason,
            'show_cause' => $this->show_cause,
            'no_of_installments' => $this->installment_number,
            'show_cause_notice_date' => $this->show_cause_notice_date,
            'recovery_first_installment_date'=>$this->recovery_first_installment_date,
            'recovery_last_installment_date'=>$this->recovery_last_installment_date,
            'remarks' => $this->remarks,
        ]);

            Log::info('submitFineDetails: New fine record created successfully');

            FlashMessageHelper::flashSuccess('Fine Record Created Successfully');
        
    }

           
    }
    public function updateselectedEmployee($empId)
    {
    
        $this->selectedEmployeeId=$empId;
      
        $this->selectedEmployeeFirstName= EmployeeDetails::where('emp_id',$empId)->value('first_name');
        $this->selectedEmployeeLastName= EmployeeDetails::where('emp_id',$empId)->value('last_name');
        $this->searchEmployee=0;
    }

    public function updatedamageOrLossDate()
    {
        $this->damage_or_loss_date=$this->damage_or_loss_date;
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
    public function render()
    {
        $this->employees = $this->getEmployeesByType();
        return view('livewire.create-damage-component');
    }
}
