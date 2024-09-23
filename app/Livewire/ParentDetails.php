<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\HelpDesks;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;
use  App\Models\ParentDetail;
class ParentDetails extends Component
{
    use WithFileUploads;

    public $searchTerm = '';
    public $selected_equipment;
  
   
    public $isNames = false;
    public $record;
    public $subject;
    public $FatherFirstName;
    public $FatherLastName;
    public $FatherDateOfBirth;
    public $MotherFirstName;
    public $MotherLastName;
    public $MotherDateOfBirth;
    public $MotherBloodGroup;
    public $MotherAddress;
   
    public $hrempid;
    public $description;
    public $showDetails = true;
    public $priority;
    public $activeTab = 'active';
    public $image;
    public $selectedPerson = null;
    public $cc_to;
    public $peoples;
    public $filteredPeoples;
    public $selectedPeopleNames = [];
    public $selectedPeople = [];
    public $records;
    public $peopleFound = true;
    public $file_path;

    public $editingParentProfile=false;
    public $BankName;

    public $name;
    public $AccountNumber;

    public $Branch;
    public $showSuccessMessage = false;
   
    public $wishMeOn;
    public $selectedTimeZone;
    public $timeZones;
    public $biography;
    public $faceBook;
    public $FatherAddress;
    public $Email;
    public $recentHires = [];
    public $FatherBloodGroup;
    public $editingBankProfile = false;
    public $editingTimeZone = false;
    public $editingBiography = false;
    public $editingSocialMedia = false;
    public $employees;
    public $oldPassword;
    public $newPassword;
    public $confirmNewPassword;
    public $passwordChanged = false;
    public $employeeDetails = [];
    public $editingField = false;
    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }

    public function filter()
    {
        $companyId = Auth::user()->company_id;
        $trimmedSearchTerm = trim($this->searchTerm);

        $this->filteredPeoples = EmployeeDetails::where('company_id', $companyId)
            ->where(function ($query) use ($trimmedSearchTerm) {
                $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $trimmedSearchTerm . '%')
                    ->orWhere('emp_id', 'like', '%' . $trimmedSearchTerm . '%');
            })
            ->get();

        $this->peopleFound = count($this->filteredPeoples) > 0;
    }

    public function updatedSelectedPeople()
    {
        $this->cc_to = implode(', ', array_unique($this->selectedPeopleNames));
    }
   
    
    public function NamesSearch()
    {
        $this->isNames = true;
        $this->selectedPeopleNames = [];
        $this->cc_to = '';
    }

    public function closePeoples()
    {
        $this->isNames = false;
    }

    public function selectPerson($emp_id)
    {
        $selectedPerson = EmployeeDetails::with('parentDetails')->find($emp_id);
   
        if ($selectedPerson) {
            $this->employeeDetails[$emp_id] = $selectedPerson;
    
            if (!in_array($emp_id, array_keys($this->selectedPeopleNames))) {
                $this->selectedPeopleNames[$emp_id] = '<img src="' . asset($selectedPerson->image) . '" alt="' . $selectedPerson->first_name . ' ' . $selectedPerson->last_name . '" height="50" width="50">' . ' #(' . $selectedPerson->emp_id . ')';
            } else {
                unset($this->selectedPeopleNames[$emp_id]);
            }
    
            $this->cc_to = implode(', ', array_unique($this->selectedPeopleNames));
        }
    }
    
    
    
    public function editParentProfile($emp_id)
    {
        $selectedPerson = EmployeeDetails::with('parentDetails')->find($emp_id);
      
        $this->editingParentProfile = true;
    
        if ($selectedPerson && $selectedPerson->parentDetails) {
            $parentDetails = $selectedPerson->parentDetails;
            $this->FatherFirstName = $parentDetails->father_first_name ?? '';
            $this->FatherLastName = $parentDetails->father_last_name ?? '';
            $this->FatherDateOfBirth = $parentDetails->father_dob ?? '';

            $this->FatherBloodGroup = $parentDetails->father_blood_group ?? '';
            $this->FatherAddress = $parentDetails->father_address ?? '';
            $this->MotherFirstName = $parentDetails->mother_first_name ?? '';
            $this->MotherLastName = $parentDetails->mother_last_name ?? '';
            $this->MotherDateOfBirth = $parentDetails->mother_dob ?? '';

            $this->MotherBloodGroup = $parentDetails->mother_blood_group ?? '';
            $this->MotherAddress = $parentDetails->mother_address ?? '';
        }
    }
    
  

    public function cancelParentProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = ParentDetail::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
        if ($selectedPerson) {
        $this->editingParentProfile= false;
        }
       
    }
    
    public function saveParentProfile($emp_id)
    {
        $selectedPerson = EmployeeDetails::with('parentDetails')->find($emp_id);
        
        if ($selectedPerson) {
            $parentDetails = $selectedPerson->parentDetails ?? new ParentDetail();
            $parentDetails->emp_id = $emp_id; // Ensure emp_id is set if creating a new record
            $parentDetails->father_first_name = $this->FatherFirstName;
            $parentDetails->father_last_name = $this->FatherLastName;
            $parentDetails->father_dob = $this->FatherDateOfBirth;
            $parentDetails->father_blood_group = $this->FatherBloodGroup;
            $parentDetails->father_address = $this->FatherAddress;
            $parentDetails->mother_first_name = $this->MotherFirstName;
            $parentDetails->mother_last_name = $this->MotherLastName;
            $parentDetails->mother_dob = $this->MotherDateOfBirth;
            $parentDetails->mother_blood_group = $this->MotherBloodGroup;
            $parentDetails->mother_address = $this->MotherAddress;
            $parentDetails->save();
    
            $this->editingParentProfile = false;
           
        }
    }
    

    

    public function mount()
    {
        $hrempid = auth()->guard('hr')->user()->hr_emp_id;
    
        $companyId = auth()->guard('hr')->user()->company_id;
    
        $this->peoples = EmployeeDetails::where('company_id', $companyId)
            ->orderBy('hire_date', 'desc')
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->orderBy('hire_date', 'asc')
            ->pluck('hire_date');
           
    }
    

    public function render()
    {
        $hrempid = auth()->guard('hr')->user()->hr_emp_id;
        $companyId = auth()->guard('hr')->user()->company_id;

        $this->peoples = EmployeeDetails::where('company_id', $companyId)->get();
       
        $peopleData = $this->filteredPeoples ?: $this->peoples;

        $this->record = HelpDesks::all();
        $employeeName = auth()->guard('hr')->user()->employee_name . ' #(' . $hrempid . ')';

        $this->records = HelpDesks::with('emp')
            ->where(function ($query) use ($hrempid, $employeeName) {
                $query->where('emp_id', $hrempid)
                    ->orWhere('cc_to', 'LIKE', "%$employeeName%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.parent-details', [
            'peopleData' => $peopleData,
            'records' => $this->records
        ]);
    }

}