<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\DamageDetails;
use Carbon\Carbon;
use Livewire\Component;

class DamageComponent extends Component
{
    public $damageDetails;


    public $employeePicker=false;
    public $applyFilter=false;
    public $showPicker = false;

    public $selectedOptionForEmployee='all';
    public $search='';
    public $selectedOption = 'all';
    public $startDate = null;
    public $endDate = null;
    public $deleteModal=false;

    public $idfordeletingrecord=null;

    public function mount()
    {
        $this->damageDetails=DamageDetails::all();
    }
    public function openDeleteModal($id)
    {
       $this->deleteModal=true;
       $this->idfordeletingrecord=$id;
      
    }

    public function showPickerForEmployee()
    {
        $this->employeePicker = !$this->employeePicker;   
    }
    public function searchEmployee()
    {
        if ($this->search) {
            $this->damageDetails = DamageDetails::whereHas('employee', function ($query) {
                $query->where('emp_id', 'like', '%' . $this->search . '%')
                      ->orWhere('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%');
            })->get();
        } else {
            $this->damageDetails = DamageDetails::all();
        }
    }

   
    public function closeFineAndDamageRecord()
    {
        $this->deleteModal=false;
       $this->idfordeletingrecord=null;
    }

    public function closeSearchDropdown()  {

        $this->employeePicker=false;
        $this->search='';
        $this->damageDetails = DamageDetails::all();
        
        
    }
    public function closePicker(){
        $this->showPicker=false;
        $this->selectedOption='all';
        $this->startDate=null;
        $this->endDate=null;
    }
    public function deleteFineAndDamageRecord($id)
    {
        // Find the record by ID
        $fineAndDamageRecord = DamageDetails::find($id);

        if ($fineAndDamageRecord) {
            // Perform the soft delete
            $fineAndDamageRecord->delete();

            // Optionally, add a success message or refresh the data
            FlashMessageHelper::flashSuccess( 'Fine And Damage Record deleted successfully.');
        } else {
            // Handle the case where the record doesn't exist
            FlashMessageHelper::flashSuccess('Fine And Damage Record  not found.');
        }
        $this->deleteModal=false;

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
    public function render()
    {
        if($this->startDate&&$this->endDate&&$this->applyFilter==true)
        {
            $this->selectedOption=Carbon::parse($this->startDate)->format('jS F,Y').' and '.Carbon::parse($this->endDate)->format('jS F,Y');
            $this->damageDetails = DamageDetails::where('recovery_first_installment_date', $this->startDate)
            ->where('recovery_last_installment_date', '<=', $this->endDate)
            ->get();
          
        }
        
        return view('livewire.damage-component');
    }
}
