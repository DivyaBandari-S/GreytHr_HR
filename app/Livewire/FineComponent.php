<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Helpers\FormatHelper;
use App\Models\EmployeeDetails;
use App\Models\FineAndDamage;
use Carbon\Carbon;
use Livewire\Component;

class FineComponent extends Component
{
    public $fineAndDamage;

    public $openFineAndDamagePopup=false;

    public $viewFineAndDamageEmpId;
    public $viewFineAndDamageId;

    public $viewFineAndDamageEmpName;

    public $viewFineAndDamageOffenceDate;

    public $viewFineAndDamageisshowCause;

    public $selectedCategory="Fine Realized: All";
    public $viewFineAndDamageshowCauseDate;

    public $selectedOptionForEmployee='All';
    public $deleteModal=false;

    public $idfordeletingrecord;

    public $viewFineAndDamageActOrOmission;
    public $viewFineAndDamageRemarks;
    public $viewFineAndDamageFineRealised;
    public $viewFineAndDamageFineImposed;

    public $employeePicker=false;
    public $selectedOption = 'all';
    public $startDate = null;
    public $endDate = null;
    public $showPicker = false;

  
    public $search = '';
    public $applyFilter=false;
    protected $queryString = ['selectedOption','search'];

    public $finepage;

    public $damagepage;
    public function mount($finepage, $damagepage)
    {
        $this->selectedOption='all';
        $this->search='';
        $this->fineAndDamage=FineAndDamage::orderByDesc('created_at')->get();
        $this->finepage = $finepage;
        $this->damagepage = $damagepage;
    }
  

    public function searchEmployee()
    {
        if ($this->search) {
            $this->fineAndDamage = FineAndDamage::whereHas('employee', function ($query) {
                $query->where('emp_id', 'like', '%' . $this->search . '%')
                      ->orWhere('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%');
            })->get();
        } else {
            $this->fineAndDamage = FineAndDamage::all();
        }
    }

    public function closeSearchDropdown()  {

        $this->employeePicker=false;
        $this->search='';
        $this->fineAndDamage = FineAndDamage::all();
        
        
    }
    public function togglePicker()
    {
        $this->showPicker = !$this->showPicker;
    }

    public function applyDateRange()
    {
        // Apply your date range logic here
     
        $this->applyFilter=true;
        $this->showPicker = false;

    }
    public function showPickerForEmployee()
    {
        $this->employeePicker = !$this->employeePicker;   
    }

    public function openFinePage()
    {
        
    }
    public function viewFineAndDamagePopup($id)
    {
       
        $this->openFineAndDamagePopup=true;
        $this->viewFineAndDamageId=$id;
        $viewFineAndDamageDetails=FineAndDamage::find($id);
        $this->viewFineAndDamageEmpId=$viewFineAndDamageDetails->emp_id;
        $viewFineAndDamageFName=EmployeeDetails::where($viewFineAndDamageDetails->emp_id)->value('first_name');
        $viewFineAndDamageLName=EmployeeDetails::where($viewFineAndDamageDetails->emp_id)->value('last_name');
        $this->viewFineAndDamageEmpName=ucwords(strtolower($viewFineAndDamageFName)).' '.ucwords(strtolower($viewFineAndDamageLName));
        $this->viewFineAndDamageOffenceDate=Carbon::parse($viewFineAndDamageDetails->offence_date)->format('jS F,Y');
        $this->viewFineAndDamageActOrOmission=$viewFineAndDamageDetails->act_or_omission;
        $this->viewFineAndDamageisshowCause=$viewFineAndDamageDetails->is_show_cause;
        $this->viewFineAndDamageshowCauseDate=Carbon::parse($viewFineAndDamageDetails->show_cause_date)->format('jS F,Y');
        $this->viewFineAndDamageFineImposed=FormatHelper::formatAmount($viewFineAndDamageDetails->amount_of_fine);
        $this->viewFineAndDamageFineRealised=Carbon::parse($viewFineAndDamageDetails->fine_realized_date)->format('jS F,Y');
        $this->viewFineAndDamageRemarks=$viewFineAndDamageDetails->remarks;

    }

    
    public function closeModal()
    {
        $this->openFineAndDamagePopup = false;
    }

    public function deleteFineAndDamageRecord($id)
    {
        // Find the record by ID
        $fineAndDamageRecord = FineAndDamage::find($id);

        if ($fineAndDamageRecord) {
            // Perform the soft delete
            $fineAndDamageRecord->delete();

            // Optionally, add a success message or refresh the data
            FlashMessageHelper::flashSuccess( 'Fine And Damage Record deleted successfully.');
        } else {
            // Handle the case where the record doesn't exist
            FlashMessageHelper::flashSuccess('Fine And Damage Record  not found.');
        }
    }

    public function closeFineAndDamageRecord()
    {
        $this->deleteModal=false;
       $this->idfordeletingrecord=null;
    }

    public function openDeleteModal($id)
    {
       $this->deleteModal=true;
       $this->idfordeletingrecord=$id;
      
    }
    public function closePicker(){
        $this->showPicker=false;
        $this->selectedOption='all';
        $this->startDate=null;
        $this->endDate=null;
    }
    public function render()
    {
        if($this->startDate&&$this->endDate&&$this->applyFilter==true)
        {
            $this->selectedOption=Carbon::parse($this->startDate)->format('jS F,Y').' and '.Carbon::parse($this->endDate)->format('jS F,Y');
            $this->fineAndDamage = FineAndDamage::whereBetween(
                'fine_realized_date',
                [$this->startDate, $this->endDate]
            )->get();
          
        }
      
        return view('livewire.fine-component');
    }
}
